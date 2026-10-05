<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductSpec;
use App\Models\ProductVariety;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images'])->withCount(['varieties', 'specs']);

        // Search
        if ($search = trim($request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($categoryId = $request->input('category')) {
            $query->where('category_id', $categoryId);
        }

        // Filter by status
        match ($request->input('filter', 'all')) {
            'active'   => $query->where('is_active', true),
            'inactive' => $query->where('is_active', false),
            'featured' => $query->where('is_featured', true),
            default    => null,
        };

        $products = $query->orderBy('sort_order')->orderBy('name')->paginate(15)->withQueryString();

        $stats = [
            'total'    => Product::count(),
            'active'   => Product::where('is_active', true)->count(),
            'featured' => Product::where('is_featured', true)->count(),
            'with_images' => Product::has('images')->count(),
        ];

        $categories = ProductCategory::orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'stats', 'categories', 'search'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        DB::transaction(function () use ($request, $validated) {
            $product = Product::create($validated);

            $this->syncVarieties($product, $request);
            $this->syncSpecs($product, $request);
            $this->syncImages($product, $request);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('status', "Product \"{$validated['name']}\" has been created.");
    }

    public function edit(Product $product)
    {
        $product->load(['varieties', 'specs', 'images']);

        $categories = ProductCategory::orderBy('sort_order')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function () use ($request, $product, $validated) {
            $product->update($validated);

            $this->syncVarieties($product, $request);
            $this->syncSpecs($product, $request);
            $this->syncImages($product, $request);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('status', "Product \"{$product->name}\" has been updated.");
    }

    public function destroy(Request $request, Product $product)
    {
        $name = $product->name;

        DB::transaction(function () use ($product) {
            // Delete image files from disk
            foreach ($product->images as $image) {
                if ($image->path && Storage::disk('public_uploads')->exists($image->path)) {
                    Storage::disk('public_uploads')->delete($image->path);
                }
            }

            $product->images()->delete();
            $product->varieties()->delete();
            $product->specs()->delete();
            $product->delete();
        });

        return back()->with('status', "Product \"{$name}\" has been deleted.");
    }

    public function toggle(Request $request, Product $product)
    {
        $field = $request->input('field');

        if (! in_array($field, ['is_active', 'is_featured'])) {
            abort(400, 'Invalid field.');
        }

        $product->update([$field => ! $product->{$field}]);

        $msg = $field === 'is_active'
            ? ($product->is_active
                ? "{$product->name} is now visible on the website."
                : "{$product->name} is now hidden.")
            : ($product->is_featured
                ? "{$product->name} is now featured."
                : "{$product->name} is no longer featured.");

        return back()->with('status', $msg);
    }

    /* ============================================================
       HELPERS
       ============================================================ */

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'category_id'       => ['required', 'exists:product_categories,id'],
            'name'              => ['required', 'string', 'max:120'],
            'slug'              => ['nullable', 'string', 'max:120', Rule::unique('products', 'slug')->ignore($product?->id)],
            'tagline'           => ['nullable', 'string', 'max:60'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description'       => ['nullable', 'string', 'max:5000'],
            'sort_order'        => ['nullable', 'integer', 'min:0', 'max:9999'],
            'meta_title'        => ['nullable', 'string', 'max:255'],
            'meta_description'  => ['nullable', 'string', 'max:500'],
            'is_active'         => ['boolean'],
            'is_featured'       => ['boolean'],
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        return $validated;
    }

    private function syncVarieties(Product $product, Request $request): void
    {
        $product->varieties()->delete();

        $rows = $request->input('varieties', []);

        $order = 0;
        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') continue;

            ProductVariety::create([
                'product_id' => $product->id,
                'name'       => $name,
                'code'       => $row['code'] ?? null,
                'sort_order' => $order++,
                'is_active'  => true,
            ]);
        }
    }

    private function syncSpecs(Product $product, Request $request): void
    {
        $product->specs()->delete();

        $rows = $request->input('specs', []);

        $order = 0;
        foreach ($rows as $row) {
            $label = trim($row['label'] ?? '');
            $value = trim($row['value'] ?? '');
            if ($label === '' || $value === '') continue;

            ProductSpec::create([
                'product_id' => $product->id,
                'label'      => $label,
                'value'      => $value,
                'icon'       => $row['icon'] ?? null,
                'sort_order' => $order++,
            ]);
        }
    }

    private function syncImages(Product $product, Request $request): void
    {
        $disk = Storage::disk('public_uploads');

        // Remove images flagged for deletion
        $removeIds = $request->input('remove_images', []);
        if (! empty($removeIds)) {
            $toRemove = $product->images()->whereIn('id', $removeIds)->get();
            foreach ($toRemove as $img) {
                if ($img->path && $disk->exists($img->path)) {
                    $disk->delete($img->path);
                }
                $img->delete();
            }
        }

        // Handle new uploads
        $newFiles  = $request->file('images', []);
        $newAlts   = $request->input('image_alts', []);
        $primaryNew = $request->input('primary_image'); // "existing-{id}" or "new-{index}"

        foreach ($newFiles as $index => $file) {
            if (! $file || ! $file->isValid()) continue;

            $filename = time() . '-' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path     = 'images/products/' . $product->slug . '/' . $filename;

            $disk->put($path, file_get_contents($file));

            ProductImage::create([
                'product_id' => $product->id,
                'path'       => $path,
                'alt'        => $newAlts[$index] ?? null,
                'is_primary' => $primaryNew === "new-{$index}",
                'sort_order' => $index,
            ]);
        }

        // Update primary flag on existing images
        if ($primaryNew && str_starts_with($primaryNew, 'existing-')) {
            $primaryId = (int) str_replace('existing-', '', $primaryNew);
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('id', $primaryId)->update(['is_primary' => true]);
        }

        // Ensure there's always exactly one primary if any images exist
        if ($product->images()->count() > 0 && $product->images()->where('is_primary', true)->count() === 0) {
            $product->images()->first()->update(['is_primary' => true]);
        }
    }
}