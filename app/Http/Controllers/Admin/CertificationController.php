<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Certification::query();

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('issued_by', 'like', "%{$search}%")
                  ->orWhere('certificate_number', 'like', "%{$search}%");
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

            case 'expired':
                $query->whereNotNull('expires_on')
                      ->where('expires_on', '<', now());
                break;

            case 'expiring':
                $query->whereNotNull('expires_on')
                      ->whereBetween('expires_on', [now(), now()->addDays(60)]);
                break;

            // 'all' — no additional filtering
        }

        $certifications = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        /* ---------- Stats ---------- */
        $stats = [
            'total'    => Certification::count(),
            'active'   => Certification::where('is_active', true)->count(),
            'expiring' => Certification::whereNotNull('expires_on')
                                ->whereBetween('expires_on', [now(), now()->addDays(60)])
                                ->count(),
            'expired'  => Certification::whereNotNull('expires_on')
                                ->where('expires_on', '<', now())
                                ->count(),
        ];

        return view('admin.certifications.index', compact('certifications', 'stats', 'search'));
    }

    public function create()
    {
        return view('admin.certifications.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCertification($request);

        /* ---------- Handle logo upload ---------- */
        if ($request->hasFile('logo_file')) {
            $validated['logo'] = $this->storeLogo($request->file('logo_file'));
        }
        unset($validated['logo_file']);

        /* ---------- Explicit is_active (unchecked checkbox sends nothing) ---------- */
        $validated['is_active'] = $request->boolean('is_active');

        Certification::create($validated);

        return redirect()
            ->route('admin.certifications.index')
            ->with('status', "Certification \"{$validated['name']}\" has been created.");
    }

    public function edit(Certification $certification)
    {
        return view('admin.certifications.edit', compact('certification'));
    }

    public function update(Request $request, Certification $certification)
    {
        $validated = $this->validateCertification($request, $certification);

        /* ---------- Handle logo upload ---------- */
        if ($request->hasFile('logo_file')) {
            // Delete old logo
            if ($certification->logo && Storage::disk('public_uploads')->exists($certification->logo)) {
                Storage::disk('public_uploads')->delete($certification->logo);
            }
            $validated['logo'] = $this->storeLogo($request->file('logo_file'));
        }
        unset($validated['logo_file']);

        /* ---------- Remove logo ---------- */
        if ($request->boolean('remove_logo') && $certification->logo) {
            if (Storage::disk('public_uploads')->exists($certification->logo)) {
                Storage::disk('public_uploads')->delete($certification->logo);
            }
            $validated['logo'] = null;
        }

        /* ---------- Explicit is_active (unchecked checkbox sends nothing) ---------- */
        $validated['is_active'] = $request->boolean('is_active');

        $certification->update($validated);

        return redirect()
            ->route('admin.certifications.index')
            ->with('status', "Certification \"{$certification->name}\" has been updated.");
    }

    public function destroy(Certification $certification)
    {
        $name = $certification->name;

        // Delete the logo file from disk
        if ($certification->logo && Storage::disk('public_uploads')->exists($certification->logo)) {
            Storage::disk('public_uploads')->delete($certification->logo);
        }

        $certification->delete();

        return back()->with('status', "Certification \"{$name}\" has been deleted.");
    }

    public function toggle(Request $request, Certification $certification)
    {
        $field = $request->input('field');

        if (! in_array($field, ['is_active'])) {
            abort(400, 'Invalid field.');
        }

        $certification->update([$field => ! $certification->{$field}]);

        $state = $certification->is_active ? 'active' : 'hidden';

        return back()->with('status', "\"{$certification->name}\" is now {$state}.");
    }

    /* ============================================================
       HELPERS
       ============================================================ */

    /**
     * Shared validation rules for store & update.
     */
    private function validateCertification(Request $request, ?Certification $cert = null): array
    {
        return $request->validate([
            'name'               => ['required', 'string', 'max:160'],
            'description'        => ['nullable', 'string', 'max:2000'],
            'logo_file'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'issued_by'          => ['nullable', 'string', 'max:160'],
            'certificate_number' => ['nullable', 'string', 'max:120'],
            'issued_on'          => ['nullable', 'date'],
            'expires_on'         => ['nullable', 'date', 'after_or_equal:issued_on'],
            'sort_order'         => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active'          => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Save the uploaded logo to public/images/certifications/.
     */
    private function storeLogo($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $base      = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename  = $base . '-' . time() . '-' . bin2hex(random_bytes(3)) . '.' . $extension;
        $path      = 'images/certifications/' . $filename;

        $disk = Storage::disk('public_uploads');

        // Ensure the folder exists
        if (! $disk->exists('images/certifications')) {
            $disk->makeDirectory('images/certifications');
        }

        $disk->put($path, file_get_contents($file));

        return $path;
    }
}