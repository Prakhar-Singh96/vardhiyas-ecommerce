<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FilterType;
use App\Models\FilterValue;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'primaryImage')
            ->withCount('variants');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories  = Category::with('children')->whereNull('parent_id')
            ->where('is_active', true)->get();

        $sizeFilter  = FilterType::where('name', 'Size')->with('values')->first();
        $colorFilter = FilterType::where('name', 'Color')->with('values')->first();

        return view('admin.products.create', compact('categories', 'sizeFilter', 'colorFilter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'brand'       => 'nullable|string|max:100',
            'sku'         => 'nullable|string|max:100|unique:products,sku',
            'status'      => 'required|in:active,inactive,draft',
            'is_featured' => 'nullable|boolean',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'variants'    => 'nullable|array',
            'variants.*.size_id'  => 'nullable|exists:filter_values,id',
            'variants.*.color_id' => 'nullable|exists:filter_values,id',
            'variants.*.stock'    => 'required_with:variants|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Slug
            $slug  = Str::slug($validated['name']);
            $count = Product::where('slug', 'like', $slug . '%')->count();
            if ($count > 0) $slug = $slug . '-' . ($count + 1);

            $product = Product::create([
                'name'        => $validated['name'],
                'slug'        => $slug,
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'price'       => $validated['price'],
                'sale_price'  => $validated['sale_price'] ?? null,
                'brand'       => $validated['brand'] ?? null,
                'sku'         => $validated['sku'] ?? null,
                'status'      => $validated['status'],
                'is_featured' => $request->boolean('is_featured'),
                'is_bestseller' => $request->boolean('is_bestseller'), 
            ]);

            // Images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $index === 0, // pehli image primary
                        'sort_order' => $index,
                    ]);
                }
            }

            // Variants save
            if (!empty($validated['variants'])) {
                foreach ($validated['variants'] as $variant) {
                    if (isset($variant['stock'])) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size_id'    => $variant['size_id'] ?? null,
                            'color_id'   => $variant['color_id'] ?? null,
                            'stock'      => $variant['stock'],
                            'extra_price'=> $variant['extra_price'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', '"' . $product->name . '" product create ho gaya!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kuch galat hua: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        $product->load('category', 'images', 'variants.size', 'variants.color');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('images', 'variants.size', 'variants.color');

        $categories  = Category::with('children')->whereNull('parent_id')
            ->where('is_active', true)->get();

        $sizeFilter  = FilterType::where('name', 'Size')->with('values')->first();
        $colorFilter = FilterType::where('name', 'Color')->with('values')->first();

        return view('admin.products.edit', compact(
            'product', 'categories', 'sizeFilter', 'colorFilter'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',

            'sale_price'  => 'nullable|numeric|min:0',

            'brand'       => 'nullable|string|max:100',
            'sku'         => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'status'      => 'required|in:active,inactive,draft',
            'is_featured' => 'nullable|boolean',
            'new_images'  => 'nullable|array',
            'new_images.*'=> 'image|mimes:jpg,jpeg,png,webp|max:3072',
            'variants'    => 'nullable|array',

            // ✅ FIX: variants ke andar stock required nahi, sirf integer
            'variants.*.size_id'    => 'nullable|exists:filter_values,id',
            'variants.*.color_id'   => 'nullable|exists:filter_values,id',
            'variants.*.stock'      => 'nullable|integer|min:0',
            'variants.*.extra_price'=> 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'name'        => $validated['name'],
                'slug'        => Str::slug($validated['name']),
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'price'       => $validated['price'],
                'sale_price'  => $validated['sale_price'] ?? null,
                'brand'       => $validated['brand'] ?? null,
                'sku'         => $validated['sku'] ?? null,
                'status'      => $validated['status'],
                'is_featured' => $request->boolean('is_featured'),
                'is_bestseller' => $request->boolean('is_bestseller'), 
            ]);

            // New images
            if ($request->hasFile('new_images')) {
                $lastOrder = $product->images()->max('sort_order') ?? -1;
                foreach ($request->file('new_images') as $index => $image) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $product->images()->count() === 0 && $index === 0,
                        'sort_order' => $lastOrder + $index + 1,
                    ]);
                }
            }

            // ✅ FIX: variants sirf tab update karo jab request mein ho
            if ($request->has('variants') && is_array($request->variants)) {
                $product->variants()->delete();

                foreach ($request->variants as $variant) {
                    // Empty rows skip karo
                    if (!isset($variant['stock']) || $variant['stock'] === '') continue;

                    ProductVariant::create([
                        'product_id'  => $product->id,
                        'size_id'     => $variant['size_id']    ?? null,
                        'color_id'    => $variant['color_id']   ?? null,
                        'stock'       => (int)$variant['stock'],
                        'extra_price' => $variant['extra_price'] ?? 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', '"' . $product->name . '" update ho gaya!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        // Images delete karo storage se
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $product->delete(); // SoftDelete

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product delete ho gaya!');
    }

    // Image delete via AJAX
    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);

        // Agar primary thi toh dusri ko primary banao
        if ($image->is_primary) {
            $next = ProductImage::where('product_id', $image->product_id)
                ->where('id', '!=', $image->id)->first();
            if ($next) $next->update(['is_primary' => true]);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }

    // Primary image set karo
    public function setPrimaryImage(ProductImage $image)
    {
        ProductImage::where('product_id', $image->product_id)
            ->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }

    public function toggle(Product $product)
    {
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);
        return response()->json(['success' => true, 'status' => $newStatus]);
    }
}