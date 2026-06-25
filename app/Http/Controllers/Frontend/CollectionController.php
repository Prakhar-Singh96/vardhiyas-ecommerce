<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FilterType;
use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    // ✅ AB DB SE — admin jo bhi values add kare wahi yahan aayengi
    public static function priceRanges(): array
    {
        $rangeFilter = FilterType::where('name', 'Range')
            ->where('is_active', true)
            ->with([
                'values' => fn($q) =>
                $q->where('is_active', true)->orderBy('sort_order')
            ])
            ->first();

        if ($rangeFilter && $rangeFilter->values->count()) {
            return $rangeFilter->values->map(fn($v) => [
                'key'   => $v->value,   // e.g. "500-1000"
                'label' => $v->label,   // e.g. "₹500 - ₹1000"
            ])->toArray();
        }

        // Fallback — agar admin ne abhi values nahi daali
        return [
            ['key' => '0-500',       'label' => 'Less than ₹500'],
            ['key' => '500-1000',    'label' => '₹500 - ₹1000'],
            ['key' => '1000-1500',   'label' => '₹1000 - ₹1500'],
            ['key' => '1500-2000',   'label' => '₹1500 - ₹2000'],
            ['key' => '2000-999999', 'label' => 'More than ₹2000'],
        ];
    }

    public function show(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['children', 'parent'])
            ->firstOrFail();

        $categoryIds = collect([$category->id]);
        if ($category->children->count()) {
            $categoryIds = $categoryIds->merge($category->children->pluck('id'));
        }

        $query = $this->buildQuery(
            Product::whereIn('category_id', $categoryIds)->where('status', 'active'),
            $request
        );

        $products = $query->paginate(12)->withQueryString();

        $sizeFilter  = FilterType::where('name', 'Size')->with('values')->first();
        $colorFilter = FilterType::where('name', 'Color')->with('values')->first();

        return view('frontend.pages.collection', [
            'category'    => $category,
            'products'    => $products,
            'sizeFilter'  => $sizeFilter,
            'colorFilter' => $colorFilter,
            'priceRanges' => self::priceRanges(),  // ✅ ab DB se aa raha
        ]);
    }

    public function favourites(Request $request)
    {
        $category = (object)[
            'name' => 'Vardiyash Favourite',
            'slug' => 'favourites',
            'image' => null,
            'parent' => null,
        ];

        $query = $this->buildQuery(
            Product::where('status', 'active')->where('is_bestseller', true),
            $request
        );

        $products = $query->paginate(12)->withQueryString();

        $sizeFilter  = FilterType::where('name', 'Size')->with('values')->first();
        $colorFilter = FilterType::where('name', 'Color')->with('values')->first();

        return view('frontend.pages.collection', [
            'category'    => $category,
            'products'    => $products,
            'sizeFilter'  => $sizeFilter,
            'colorFilter' => $colorFilter,
            'priceRanges' => self::priceRanges(),
        ]);
    }

    private function buildQuery($query, Request $request)
    {
        $query->with('primaryImage', 'variants.size', 'variants.color');

        if ($request->filled('sizes')) {
            $query->whereHas(
                'variants',
                fn($q) =>
                $q->whereIn('size_id', $request->sizes)->where('stock', '>', 0)
            );
        }

        if ($request->filled('colors')) {
            $query->whereHas(
                'variants',
                fn($q) =>
                $q->whereIn('color_id', $request->colors)->where('stock', '>', 0)
            );
        }

        // ✅ FIX: effective price (sale_price ?? price)
        if ($request->filled('price_ranges')) {
            $query->where(function ($q) use ($request) {
                foreach ($request->price_ranges as $range) {
                    $parts = explode('-', $range);
                    if (count($parts) === 2) {
                        $min = (float) $parts[0];
                        $max = (float) $parts[1];
                        $q->orWhereRaw('COALESCE(sale_price, price) BETWEEN ? AND ?', [$min, $max]);
                    }
                }
            });
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'in_stock') {
                $query->whereHas('variants', fn($q) => $q->where('stock', '>', 0));
            } elseif ($request->availability === 'out_of_stock') {
                $query->where(function ($q) {
                    $q->whereDoesntHave('variants')
                        ->orWhereDoesntHave('variants', fn($v) => $v->where('stock', '>', 0));
                });
            }
        }

        // ✅ FIX: sort bhi effective price use kare
        match ($request->get('sort', 'featured')) {
            'price_asc'   => $query->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_desc'  => $query->orderByRaw('COALESCE(sale_price, price) desc'),
            'name_asc'    => $query->orderBy('name', 'asc'),
            'newest'      => $query->orderBy('id', 'desc'),
            'bestselling' => $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc'),
            default       => $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc'),
        };

        return $query;
    }
}
