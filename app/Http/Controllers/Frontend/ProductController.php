<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with([
                'images' => fn($q) => $q->orderBy('is_primary', 'desc')
                                         ->orderBy('sort_order'),
                'variants.size',
                'variants.color',
                'category.parent',
            ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Unique sizes / colors from variants
        $sizes = $product->variants
            ->pluck('size')
            ->filter()
            ->unique('id')
            ->sortBy('sort_order')
            ->values();

        $colors = $product->variants
            ->pluck('color')
            ->filter()
            ->unique('id')
            ->sortBy('sort_order')
            ->values();

        // Variant stock map for JS (size + color combo)
        $variantsData = $product->variants->map(fn($v) => [
            'size_id'     => $v->size_id,
            'color_id'    => $v->color_id,
            'stock'       => $v->stock,
            'extra_price' => (float) $v->extra_price,
        ]);

        // Related products — same category
        $relatedProducts = Product::with('primaryImage', 'variants.size', 'variants.color')
            ->where('category_id', $product->category_id)
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('frontend.pages.product-detail', compact(
            'product', 'sizes', 'colors',
            'variantsData', 'relatedProducts'
        ));
    }
}