<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductCategory::withCount('products');

        /* ---------- Search ---------- */
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        /* ---------- Filter ---------- */
        $filter = $request->input('filter', 'all');
        match ($filter) {
            'active'   => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            'featured' => $query->where('is_featured', true),
            default    => null,
        };

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(15)->withQueryString();

        /* ---------- Stats ---------- */
        $stats = [
            'total'       => ProductCategory::count(),
            'active'      => ProductCategory::where('is_active', true)->count(),
            'featured'    => ProductCategory::where('is_featured', true)->count(),
            'with_products' => ProductCategory::has('products')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats', 'search', 'filter'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        // Auto-slug from name if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        ProductCategory::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', "Category \"{$validated['name']}\" has been created.");
    }

    public function edit(ProductCategory $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $validated = $request->validate($this->rules($category));

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('status', "Category \"{$category->name}\" has been updated.");
    }

    public function destroy(ProductCategory $category)
    {
        $name = $category->name;

        // Prevent deletion if it has products attached
        if ($category->products()->count() > 0) {
            return back()->withErrors([
                'delete' => "Cannot delete \"{$name}\" — it still has {$category->products()->count()} product(s) attached. Move or delete them first.",
            ]);
        }

        $category->delete();

        return back()->with('status', "Category \"{$name}\" has been deleted.");
    }

    /**
     * Inline toggle for is_active / is_featured.
     */
    public function toggle(Request $request, ProductCategory $category)
    {
        $field = $request->input('field');

        if (! in_array($field, ['is_active', 'is_featured'])) {
            abort(400, 'Invalid field.');
        }

        $category->update([$field => ! $category->{$field}]);

        $labels = [
            'is_active'   => 'active',
            'is_featured' => 'featured',
        ];

        $state = $category->{$field} ? 'marked as' : 'removed from';
        $msg = "{$category->name} {$state} {$labels[$field]}.";

        if ($field === 'is_active') {
            $msg = $category->{$field}
                ? "{$category->name} is now visible on the website."
                : "{$category->name} is now hidden from the website.";
        }

        return back()->with('status', $msg);
    }

    /**
     * Shared validation rules.
     */
    private function rules(?ProductCategory $category = null): array
    {
        return [
            'name'              => ['required', 'string', 'max:120'],
            'slug'              => ['nullable', 'string', 'max:120', Rule::unique('product_categories', 'slug')->ignore($category?->id)],
            'tagline'           => ['nullable', 'string', 'max:60'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description'       => ['nullable', 'string', 'max:5000'],
            'icon'              => ['nullable', 'string', 'max:255'],
            'image'             => ['nullable', 'string', 'max:255'],
            'gradient_class'    => ['nullable', 'string', 'max:80'],
            'sort_order'        => ['nullable', 'integer', 'min:0', 'max:9999'],
            'meta_title'        => ['nullable', 'string', 'max:255'],
            'meta_description'  => ['nullable', 'string', 'max:500'],
            'is_active'         => ['boolean'],
            'is_featured'       => ['boolean'],
        ];
    }
}