<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::with('country');

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('quote', 'like', "%{$search}%");
            });
        }

        /* ---------- Filters ---------- */
        switch ($request->input('filter', 'all')) {
            case 'active':
                $query->where('is_active', true);
                break;

            case 'inactive':
                $query->where('is_active', false);
                break;

            case 'featured':
                $query->where('is_featured', true);
                break;
        }

        $testimonials = $query
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        /* ---------- Stats ---------- */
        $stats = [
            'total'    => Testimonial::count(),
            'active'   => Testimonial::where('is_active', true)->count(),
            'featured' => Testimonial::where('is_featured', true)->count(),
            'avg'      => round(Testimonial::where('is_active', true)->avg('rating') ?? 0, 1),
        ];

        return view('admin.testimonials.index', compact('testimonials', 'stats', 'search'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('admin.testimonials.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateTestimonial($request);

        /* ---------- Avatar upload ---------- */
        if ($request->hasFile('avatar_file')) {
            $validated['avatar'] = $this->storeAvatar($request->file('avatar_file'));
        }
        unset($validated['avatar_file']);

        /* ---------- Booleans ---------- */
        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        /* ---------- Rating fallback ---------- */
        $validated['rating'] = (int) ($validated['rating'] ?? 5);

        Testimonial::create($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('status', "Testimonial from \"{$validated['client_name']}\" has been created.");
    }

    public function edit(Testimonial $testimonial)
    {
        $countries = Country::orderBy('name')->get();

        return view('admin.testimonials.edit', compact('testimonial', 'countries'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $this->validateTestimonial($request, $testimonial);

        /* ---------- Avatar upload ---------- */
        if ($request->hasFile('avatar_file')) {
            if ($testimonial->avatar && Storage::disk('public_uploads')->exists($testimonial->avatar)) {
                Storage::disk('public_uploads')->delete($testimonial->avatar);
            }
            $validated['avatar'] = $this->storeAvatar($request->file('avatar_file'));
        }
        unset($validated['avatar_file']);

        /* ---------- Remove avatar ---------- */
        if ($request->boolean('remove_avatar') && $testimonial->avatar) {
            if (Storage::disk('public_uploads')->exists($testimonial->avatar)) {
                Storage::disk('public_uploads')->delete($testimonial->avatar);
            }
            $validated['avatar'] = null;
        }

        /* ---------- Booleans ---------- */
        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        /* ---------- Rating fallback ---------- */
        $validated['rating'] = (int) ($validated['rating'] ?? 5);

        $testimonial->update($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('status', "Testimonial from \"{$testimonial->client_name}\" has been updated.");
    }

    public function destroy(Testimonial $testimonial)
    {
        $name = $testimonial->client_name;

        if ($testimonial->avatar && Storage::disk('public_uploads')->exists($testimonial->avatar)) {
            Storage::disk('public_uploads')->delete($testimonial->avatar);
        }

        $testimonial->delete();

        return back()->with('status', "Testimonial from \"{$name}\" has been deleted.");
    }

    public function toggle(Request $request, Testimonial $testimonial)
    {
        $field = $request->input('field');

        if (! in_array($field, ['is_active', 'is_featured'])) {
            abort(400, 'Invalid field.');
        }

        $testimonial->update([$field => ! $testimonial->{$field}]);

        $labels = [
            'is_active'   => $testimonial->is_active ? 'active' : 'hidden',
            'is_featured' => $testimonial->is_featured ? 'featured' : 'no longer featured',
        ];

        return back()->with('status', "\"{$testimonial->client_name}\" is now {$labels[$field]}.");
    }

    /* ============================================================
       HELPERS
       ============================================================ */

    private function validateTestimonial(Request $request, ?Testimonial $testimonial = null): array
    {
        return $request->validate([
            'client_name'   => ['required', 'string', 'max:120'],
            'company'       => ['nullable', 'string', 'max:120'],
            'position'      => ['nullable', 'string', 'max:120'],
            'country_id'    => ['nullable', 'exists:countries,id'],
            'quote'         => ['required', 'string', 'max:2000'],
            'rating'        => ['nullable', 'integer', 'min:1', 'max:5'],
            'avatar_file'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sort_order'    => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'     => ['nullable', 'boolean'],
            'is_featured'   => ['nullable', 'boolean'],
        ]);
    }

    private function storeAvatar($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $base      = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename  = $base . '-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $extension;
        $path      = 'images/testimonials/' . $filename;

        $disk = Storage::disk('public_uploads');
        if (! $disk->exists('images/testimonials')) {
            $disk->makeDirectory('images/testimonials');
        }
        $disk->put($path, file_get_contents($file));

        return $path;
    }
}