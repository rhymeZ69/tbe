<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BannerController extends Controller
{
    private const POSITIONS = ['hero', 'top', 'middle', 'footer'];

    public function index(Request $request)
    {
        $query = Banner::query();

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%")
                  ->orWhere('cta_text', 'like', "%{$search}%");
            });
        }

        /* ---------- Filter ---------- */
        switch ($request->input('filter', 'all')) {
            case 'active':
                $query->where('is_active', true);
                break;

            case 'inactive':
                $query->where('is_active', false);
                break;

            case 'scheduled':
                $query->where('is_active', true)
                      ->whereNotNull('starts_at')
                      ->where('starts_at', '>', now());
                break;

            case 'expired':
                $query->whereNotNull('ends_at')
                      ->where('ends_at', '<', now());
                break;
        }

        /* ---------- Position filter ---------- */
        if ($position = $request->input('position')) {
            if (in_array($position, self::POSITIONS)) {
                $query->where('position', $position);
            }
        }

        $banners = $query
            ->orderByRaw("FIELD(position, 'top', 'hero', 'middle', 'footer')")
            ->orderBy('sort_order')
            ->paginate(15)
            ->withQueryString();

        /* ---------- Stats ---------- */
        $stats = [
            'total'     => Banner::count(),
            'active'    => Banner::active()->count(),
            'scheduled' => Banner::where('is_active', true)
                                ->whereNotNull('starts_at')
                                ->where('starts_at', '>', now())
                                ->count(),
            'expired'   => Banner::whereNotNull('ends_at')
                                ->where('ends_at', '<', now())
                                ->count(),
        ];

        return view('admin.banners.index', compact('banners', 'stats', 'search'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateBanner($request);

        /* ---------- Timezone conversion for scheduled fields ---------- */
        $validated['starts_at'] = $this->parseLocalDateTime($request->input('starts_at'));
        $validated['ends_at']   = $this->parseLocalDateTime($request->input('ends_at'));

        /* ---------- Image uploads ---------- */
        if ($request->hasFile('image_file')) {
            $validated['image'] = $this->storeImage($request->file('image_file'), 'desktop');
        }
        unset($validated['image_file']);

        if ($request->hasFile('image_mobile_file')) {
            $validated['image_mobile'] = $this->storeImage($request->file('image_mobile_file'), 'mobile');
        }
        unset($validated['image_mobile_file']);

        /* ---------- Booleans ---------- */
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        Banner::create($validated);

        return redirect()
            ->route('admin.banners.index')
            ->with('status', "Banner \"" . ($validated['title'] ?? 'Untitled') . "\" has been created.");
    }

    public function edit(Banner $banner)
    {
        // Pre-format the dates for datetime-local inputs in the display timezone
        $banner->starts_at_display = $this->formatForForm($banner->starts_at);
        $banner->ends_at_display   = $this->formatForForm($banner->ends_at);

        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $this->validateBanner($request, $banner);

        /* ---------- Timezone conversion for scheduled fields ---------- */
        $validated['starts_at'] = $this->parseLocalDateTime($request->input('starts_at'));
        $validated['ends_at']   = $this->parseLocalDateTime($request->input('ends_at'));

        /* ---------- Desktop image ---------- */
        if ($request->hasFile('image_file')) {
            if ($banner->image && Storage::disk('public_uploads')->exists($banner->image)) {
                Storage::disk('public_uploads')->delete($banner->image);
            }
            $validated['image'] = $this->storeImage($request->file('image_file'), 'desktop');
        }
        unset($validated['image_file']);

        if ($request->boolean('remove_image') && $banner->image) {
            if (Storage::disk('public_uploads')->exists($banner->image)) {
                Storage::disk('public_uploads')->delete($banner->image);
            }
            $validated['image'] = null;
        }

        /* ---------- Mobile image ---------- */
        if ($request->hasFile('image_mobile_file')) {
            if ($banner->image_mobile && Storage::disk('public_uploads')->exists($banner->image_mobile)) {
                Storage::disk('public_uploads')->delete($banner->image_mobile);
            }
            $validated['image_mobile'] = $this->storeImage($request->file('image_mobile_file'), 'mobile');
        }
        unset($validated['image_mobile_file']);

        if ($request->boolean('remove_image_mobile') && $banner->image_mobile) {
            if (Storage::disk('public_uploads')->exists($banner->image_mobile)) {
                Storage::disk('public_uploads')->delete($banner->image_mobile);
            }
            $validated['image_mobile'] = null;
        }

        /* ---------- Booleans ---------- */
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        $banner->update($validated);

        return redirect()
            ->route('admin.banners.index')
            ->with('status', "Banner \"" . ($banner->title ?? 'Untitled') . "\" has been updated.");
    }

    public function destroy(Banner $banner)
    {
        $title = $banner->title ?? 'Untitled banner';

        if ($banner->image && Storage::disk('public_uploads')->exists($banner->image)) {
            Storage::disk('public_uploads')->delete($banner->image);
        }
        if ($banner->image_mobile && Storage::disk('public_uploads')->exists($banner->image_mobile)) {
            Storage::disk('public_uploads')->delete($banner->image_mobile);
        }

        $banner->delete();

        return back()->with('status', "\"{$title}\" has been deleted.");
    }

    public function toggle(Request $request, Banner $banner)
    {
        $field = $request->input('field');

        if (! in_array($field, ['is_active'])) {
            abort(400, 'Invalid field.');
        }

        $banner->update([$field => ! $banner->{$field}]);

        $state = $banner->is_active ? 'activated' : 'hidden';

        return back()->with('status', "\"{$banner->title}\" has been {$state}.");
    }

    /* ============================================================
       HELPERS
       ============================================================ */

    private function validateBanner(Request $request, ?Banner $banner = null): array
    {
        return $request->validate([
            'title'              => ['nullable', 'string', 'max:160'],
            'subtitle'           => ['nullable', 'string', 'max:300'],
            'cta_text'           => ['nullable', 'string', 'max:60'],
            'cta_url'            => ['nullable', 'string', 'max:255'],

            // Desktop: recommended 1920×800 (accept 1600–2560 wide, 700–1200 tall)
            'image_file'         => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:6144',
                'dimensions:min_width=1600,min_height=700,max_width=2560,max_height=1200',
            ],

            // Mobile: recommended 800×1000 (accept 600–1200 wide, 800–1400 tall)
            'image_mobile_file'  => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
                'dimensions:min_width=600,min_height=800,max_width=1200,max_height=1400',
            ],

            'position'           => ['required', 'in:hero,top,middle,footer'],
            'sort_order'         => ['nullable', 'integer', 'min:0', 'max:9999'],
            'starts_at'          => ['nullable', 'date'],
            'ends_at' => [
                'nullable',
                'date',
                // Only enforce order when starts_at is also present
                Rule::when(
                    ! empty($request->input('starts_at')),
                    ['after_or_equal:starts_at']
                ),
            ],
            'is_active'          => ['nullable', 'boolean'],
        ], [
            // Custom error messages
            'image_file.dimensions' => 'The desktop image must be between 1600×700 and 2560×1200 pixels. Recommended: 1920×800.',
            'image_file.max'        => 'The desktop image must not exceed 6 MB.',
            'image_file.mimes'      => 'The desktop image must be a JPG, PNG, or WebP file.',

            'image_mobile_file.dimensions' => 'The mobile image must be between 600×800 and 1200×1400 pixels. Recommended: 800×1000.',
            'image_mobile_file.max'        => 'The mobile image must not exceed 4 MB.',
            'image_mobile_file.mimes'      => 'The mobile image must be a JPG, PNG, or WebP file.',
        ]);
    }
    private function storeImage($file, string $variant = 'desktop'): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $base      = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename  = $base . '-' . $variant . '-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $extension;
        $path      = 'images/banners/' . $filename;

        $disk = Storage::disk('public_uploads');
        if (! $disk->exists('images/banners')) {
            $disk->makeDirectory('images/banners');
        }
        $disk->put($path, file_get_contents($file));

        return $path;
    }

    /**
     * Parse a datetime-local string (assumed to be in the app's display timezone)
     * and return a Carbon instance in UTC for storage.
     */
    private function parseLocalDateTime(?string $value): ?\Carbon\Carbon
    {
        if (empty($value)) {
            return null;
        }

        // Interpret the input as being in the display timezone, then store as UTC
        return \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $value, config('app.display_timezone'))
            ->setTimezone(config('app.timezone'));
    }

    /**
     * Format a stored UTC datetime as a datetime-local string
     * in the app's display timezone.
     */
    private function formatForForm(?\Carbon\Carbon $value): string
    {
        if (! $value) {
            return '';
        }

        return $value->copy()
            ->setTimezone(config('app.display_timezone'))
            ->format('Y-m-d\TH:i');
    }
}