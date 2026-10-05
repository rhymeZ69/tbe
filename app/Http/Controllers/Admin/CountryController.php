<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('short_label', 'like', "%{$search}%");
            });
        }

        /* ---------- Filters ---------- */
        $filter = $request->input('filter', 'all');
        match ($filter) {
            'gcc'      => $query->where('is_gcc', true),
            'featured' => $query->where('is_featured', true),
            'active'   => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            default    => null,
        };

        $countries = $query->orderBy('sort_order')->orderBy('name')->paginate(20)->withQueryString();

        /* ---------- Stats ---------- */
        $stats = [
            'total'    => Country::count(),
            'gcc'      => Country::where('is_gcc', true)->count(),
            'featured' => Country::where('is_featured', true)->count(),
            'active'   => Country::where('is_active', true)->count(),
        ];

        return view('admin.countries.index', compact('countries', 'stats', 'search', 'filter'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        Country::create($validated);

        return redirect()
            ->route('admin.countries.index')
            ->with('status', "{$validated['name']} has been added.");
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate($this->rules($country));

        $country->update($validated);

        return redirect()
            ->route('admin.countries.index')
            ->with('status', "{$country->name} has been updated.");
    }

    public function destroy(Country $country)
    {
        $name = $country->name;
        $country->delete();

        return back()->with('status', "{$name} has been removed.");
    }

    /**
     * Quick inline toggle for boolean fields.
     */
    public function toggle(Request $request, Country $country)
    {
        $field = $request->input('field');

        if (! in_array($field, ['is_gcc', 'is_featured', 'is_active'])) {
            abort(400, 'Invalid field.');
        }

        $country->update([$field => ! $country->{$field}]);

        $labels = [
            'is_gcc'      => 'GCC',
            'is_featured' => 'featured',
            'is_active'   => 'active',
        ];

        $state = $country->{$field} ? 'marked as' : 'removed from';

        return back()->with('status', "{$country->name} {$state} {$labels[$field]}.");
    }

    /**
     * Validation rules shared by store and update.
     */
    private function rules(?Country $country = null): array
    {
        return [
            'name'         => ['required', 'string', 'max:120'],
            'code'         => ['required', 'string', 'size:2', Rule::unique('countries', 'code')->ignore($country?->id)],
            'code3'        => ['nullable', 'string', 'size:3'],
            'flag_emoji'   => ['nullable', 'string', 'max:16'],
            'short_label'  => ['nullable', 'string', 'max:8'],
            'is_gcc'       => ['boolean'],
            'is_featured'  => ['boolean'],
            'is_active'    => ['boolean'],
            'sort_order'   => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }
}