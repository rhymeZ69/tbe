<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query();

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        /* ---------- Filter ---------- */
        switch ($request->input('filter', 'all')) {
            case 'published':
                $query->where('is_published', true);
                break;

            case 'draft':
                $query->where('is_published', false);
                break;

            case 'scheduled':
                $query->where('is_published', true)
                      ->whereNotNull('published_at')
                      ->where('published_at', '>', now());
                break;
        }

        $pages = $query
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        /* ---------- Stats ---------- */
        $stats = [
            'total'     => Page::count(),
            'published' => Page::where('is_published', true)->count(),
            'draft'     => Page::where('is_published', false)->count(),
            'scheduled' => Page::where('is_published', true)
                                ->whereNotNull('published_at')
                                ->where('published_at', '>', now())
                                ->count(),
        ];

        return view('admin.pages.index', compact('pages', 'stats', 'search'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePage($request);

        /* ---------- Featured image ---------- */
        if ($request->hasFile('featured_image_file')) {
            $validated['featured_image'] = $this->storeFeaturedImage($request->file('featured_image_file'));
        }
        unset($validated['featured_image_file']);

        /* ---------- Booleans ---------- */
        $validated['is_published'] = $request->boolean('is_published');

        /* ---------- Publish date ---------- */
        if (! empty($validated['published_at'])) {
            $validated['published_at'] = \Carbon\Carbon::parse($validated['published_at']);
        } elseif ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        Page::create($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('status', "Page \"{$validated['title']}\" has been created.");
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $this->validatePage($request, $page);

        /* ---------- Featured image ---------- */
        if ($request->hasFile('featured_image_file')) {
            if ($page->featured_image && Storage::disk('public_uploads')->exists($page->featured_image)) {
                Storage::disk('public_uploads')->delete($page->featured_image);
            }
            $validated['featured_image'] = $this->storeFeaturedImage($request->file('featured_image_file'));
        }
        unset($validated['featured_image_file']);

        /* ---------- Remove image ---------- */
        if ($request->boolean('remove_featured_image') && $page->featured_image) {
            if (Storage::disk('public_uploads')->exists($page->featured_image)) {
                Storage::disk('public_uploads')->delete($page->featured_image);
            }
            $validated['featured_image'] = null;
        }

        /* ---------- Booleans ---------- */
        $validated['is_published'] = $request->boolean('is_published');

        /* ---------- Publish date ---------- */
        if (! empty($validated['published_at'])) {
            $validated['published_at'] = \Carbon\Carbon::parse($validated['published_at']);
        } elseif ($validated['is_published'] && ! $page->published_at) {
            $validated['published_at'] = now();
        }

        $page->update($validated);

        return redirect()
            ->route('admin.pages.index')
            ->with('status', "Page \"{$page->title}\" has been updated.");
    }

    public function destroy(Page $page)
    {
        $title = $page->title;

        if ($page->featured_image && Storage::disk('public_uploads')->exists($page->featured_image)) {
            Storage::disk('public_uploads')->delete($page->featured_image);
        }

        $page->delete();

        return back()->with('status', "Page \"{$title}\" has been deleted.");
    }

    public function toggle(Request $request, Page $page)
    {
        $field = $request->input('field');

        if (! in_array($field, ['is_published'])) {
            abort(400, 'Invalid field.');
        }

        $page->update([$field => ! $page->{$field}]);

        // Auto-set published_at when first published
        if ($page->is_published && ! $page->published_at) {
            $page->update(['published_at' => now()]);
        }

        $state = $page->is_published ? 'published' : 'moved to drafts';

        return back()->with('status', "\"{$page->title}\" has been {$state}.");
    }

    /* ============================================================
       HELPERS
       ============================================================ */

    private function validatePage(Request $request, ?Page $page = null): array
    {
        return $request->validate([
            'title'                => ['required', 'string', 'max:200'],
            'slug'                 => ['nullable', 'string', 'max:200', Rule::unique('pages', 'slug')->ignore($page?->id)],
            'excerpt'              => ['nullable', 'string', 'max:500'],
            'content'              => ['nullable', 'string', 'max:200000'],
            'featured_image_file'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'meta_title'           => ['nullable', 'string', 'max:255'],
            'meta_description'     => ['nullable', 'string', 'max:500'],
            'published_at'         => ['nullable', 'date'],
            'is_published'         => ['nullable', 'boolean'],
        ]);
    }

    private function storeFeaturedImage($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $base      = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename  = $base . '-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $extension;
        $path      = 'images/pages/' . $filename;

        $disk = Storage::disk('public_uploads');
        if (! $disk->exists('images/pages')) {
            $disk->makeDirectory('images/pages');
        }
        $disk->put($path, file_get_contents($file));

        return $path;
    }
}