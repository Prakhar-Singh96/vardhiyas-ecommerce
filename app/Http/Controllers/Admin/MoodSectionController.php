<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MoodSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MoodSectionController extends Controller
{
    public function index()
    {
        $moodSections = MoodSection::with('category')
            ->orderBy('sort_order')->get();

        return view('admin.mood-sections.index', compact('moodSections'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')->get();

        return view('admin.mood-sections.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'admin_label' => 'nullable|string|max:200',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'label_top'   => 'nullable|string|max:100',
            'label_main'  => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'custom_url'  => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
        ]);

        MoodSection::create([
            'admin_label' => $request->admin_label,
            'image'       => $request->file('image')->store('mood', 'public'),
            'label_top'   => $request->label_top,
            'label_main'  => $request->label_main,
            'category_id' => $request->category_id,
            'custom_url'  => $request->custom_url,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.mood-sections.index')
            ->with('success', 'Mood card create ho gaya!');
    }

    public function edit(MoodSection $moodSection)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')->get();

        return view('admin.mood-sections.edit', compact('moodSection', 'categories'));
    }

    public function update(Request $request, MoodSection $moodSection)
    {
        $request->validate([
            'admin_label' => 'nullable|string|max:200',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'label_top'   => 'nullable|string|max:100',
            'label_main'  => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'custom_url'  => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer',
        ]);

        $data = [
            'admin_label' => $request->admin_label,
            'label_top'   => $request->label_top,
            'label_main'  => $request->label_main,
            'category_id' => $request->category_id,
            'custom_url'  => $request->custom_url,
            'sort_order'  => $request->sort_order ?? 0,
            'is_active'   => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($moodSection->image);
            $data['image'] = $request->file('image')->store('mood', 'public');
        }

        $moodSection->update($data);

        return redirect()
            ->route('admin.mood-sections.index')
            ->with('success', 'Mood card update ho gaya!');
    }

    public function destroy(MoodSection $moodSection)
    {
        Storage::disk('public')->delete($moodSection->image);
        $moodSection->delete();

        return back()->with('success', 'Mood card delete ho gaya!');
    }

    public function toggle(MoodSection $moodSection)
    {
        $moodSection->update(['is_active' => !$moodSection->is_active]);
        return response()->json(['success' => true]);
    }
}