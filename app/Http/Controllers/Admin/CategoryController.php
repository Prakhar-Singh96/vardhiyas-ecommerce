<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent', 'children')
            ->whereNull('parent_id')
            ->withCount('children', 'products')
            ->orderBy('sort_order')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $slug  = Str::slug($validated['name']);
        $count = Category::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) $slug = $slug . '-' . ($count + 1);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        Category::create([
            'name'               => $validated['name'],
            'slug'               => $slug,
            'parent_id'          => $validated['parent_id'] ?? null,
            'description'        => $validated['description'] ?? null,
            'image'              => $imagePath,
            'sort_order'         => $validated['sort_order'] ?? 0,
            'is_active'          => $request->boolean('is_active'),          // ✅ no default
            'show_in_collection' => $request->boolean('show_in_collection'), // ✅ no default
            'is_look_category' => $request->boolean('is_look_category'),
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category successfully create ho gayi!');
    }

    public function show(Category $category)
    {
        $category->load('parent', 'children', 'products');
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $excludeIds = $category->children->pluck('id')->push($category->id);

        $parents = Category::whereNull('parent_id')
            ->whereNotIn('id', $excludeIds)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:500',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $data = [
            'name'               => $validated['name'],
            'slug'               => Str::slug($validated['name']),
            'parent_id'          => $validated['parent_id'] ?? null,
            'description'        => $validated['description'] ?? null,
            'sort_order'         => $validated['sort_order'] ?? 0,
            'is_active'          => $request->boolean('is_active'),          // ✅ no default
            'show_in_collection' => $request->boolean('show_in_collection'), // ✅ no default
            'is_look_category' => $request->boolean('is_look_category'),
        ];

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category successfully update ho gayi!');
    }

    public function destroy(Category $category)
    {
        if ($category->children()->count() > 0) {
            return back()->with('error', 'Pehle sub-categories delete karo!');
        }

        if ($category->products()->count() > 0) {
            return back()->with('error', 'Is category mein products hain, pehle unhe hatao!');
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category delete ho gayi!');
    }

    public function toggle(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success'   => true,
            'is_active' => $category->is_active,
            'message'   => $category->is_active
                ? 'Category active ho gayi'
                : 'Category inactive ho gayi',
        ]);
    }
}