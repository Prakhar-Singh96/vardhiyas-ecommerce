<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\MoodSection;

class HomeController extends Controller
{
    public function index()
    {
        $heroBanners = Banner::where('position', 'hero')
            ->where('is_active', true)
            ->orderBy('sort_order')->get();

        $promoBanners = Banner::where('position', 'promo')
            ->where('is_active', true)
            ->orderBy('sort_order')->take(2)->get();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with([
                'children' => fn($q) =>
                $q->where('is_active', true)->orderBy('sort_order')
            ])
            ->orderBy('sort_order')->get();

        $subCategories = Category::whereNotNull('parent_id')
            ->where('is_active', true)
            ->where('show_in_collection', true)
            ->orderBy('sort_order')
            ->get();

        // ✅ NEW — "Vardiyash Favourite" (New Arrivals ki jagah)
        $favouriteProducts = Product::with('primaryImage', 'variants.size', 'variants.color')
            ->where('status', 'active')
            ->where('is_bestseller', true)
            ->latest()
            ->take(8)
            ->get();

        $bestSellers = Product::with('primaryImage', 'variants.size', 'variants.color')
            ->where('status', 'active')
            ->where('is_featured', true)
            ->latest()->take(8)->get();

        $moodSections = MoodSection::with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(4)->get();

        $lookCategory = Category::where('is_look_category', true)
            ->where('is_active', true)
            ->with(['products' => function ($q) {
                $q->with('primaryImage', 'variants.size', 'variants.color')
                    ->where('status', 'active')
                    ->orderBy('id', 'desc')
                    ->take(8);
            }])
            ->first();

        $lookProducts = $lookCategory ? $lookCategory->products : collect();

        return view('frontend.pages.home', compact(
            'heroBanners',
            'promoBanners',
            'categories',
            'subCategories',
            'favouriteProducts',  // ← naya
            'bestSellers',
            'lookProducts', 'lookCategory',
            'moodSections'
        ));
    }
}