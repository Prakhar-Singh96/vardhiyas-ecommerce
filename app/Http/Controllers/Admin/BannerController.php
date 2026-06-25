<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $heroBanners  = Banner::where('position', 'hero')
            ->orderBy('sort_order')->get();
        $promoBanners = Banner::where('position', 'promo')
            ->orderBy('sort_order')->get();
        $collectionBanners = Banner::where('position', 'collection') // ← ADD
        ->orderBy('sort_order')->get();


        return view('admin.banners.index', compact('heroBanners', 'promoBanners', 'collectionBanners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'nullable|string|max:200',
            'position'     => 'required|in:hero,promo,collection',
            'button_url'   => 'nullable|string|max:500',
            'sort_order'   => 'nullable|integer',
            'image'        => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = [
            'title'       => $request->title,
            'position'    => $request->position,
            'button_url'  => $request->button_url,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
            'text_color'  => 'white',  // default
            'text_align'  => 'left',   // default
            'image'       => $request->file('image')->store('banners', 'public'),
        ];

        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = $request->file('mobile_image')
                ->store('banners/mobile', 'public');
        }

        Banner::create($data);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner create ho gaya!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title'        => 'nullable|string|max:200',
            'position'     => 'required|in:hero,promo,collection',
            'button_url'   => 'nullable|string|max:500',
            'sort_order'   => 'nullable|integer',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = [
            'title'      => $request->title,
            'position'   => $request->position,
            'button_url' => $request->button_url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            // Purani image delete karo
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')
                ->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image) {
                Storage::disk('public')->delete($banner->mobile_image);
            }
            $data['mobile_image'] = $request->file('mobile_image')
                ->store('banners/mobile', 'public');
        }

        $banner->update($data);

        return redirect()
            ->route('admin.banners.index')
            ->with('success', 'Banner update ho gaya!');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        if ($banner->mobile_image) {
            Storage::disk('public')->delete($banner->mobile_image);
        }
        $banner->delete();

        return back()->with('success', 'Banner delete ho gaya!');
    }

    public function toggle(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return response()->json([
            'success'   => true,
            'is_active' => $banner->is_active,
        ]);
    }
}