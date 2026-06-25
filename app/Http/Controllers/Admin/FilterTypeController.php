<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FilterType;
use Illuminate\Http\Request;

class FilterTypeController extends Controller
{
    public function index()
    {
        $filterTypes = FilterType::withCount('values')
            ->orderBy('sort_order')
            ->get();

        return view('admin.filters.index', compact('filterTypes'));
    }

    public function create()
    {
        return view('admin.filters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:filter_types,name',
            'display_name' => 'required|string|max:100',
            'type'         => 'required|in:select,color,boolean,range',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        FilterType::create([
            'name'         => $validated['name'],
            'display_name' => $validated['display_name'],
            'type'         => $validated['type'],
            'sort_order'   => $validated['sort_order'] ?? 0,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.filter-types.index')
            ->with('success', 'Filter type create ho gaya!');
    }

    public function edit(FilterType $filterType)
    {
        $filterType->load(['values' => fn($q) => $q->orderBy('sort_order')]);
        return view('admin.filters.edit', compact('filterType'));
    }

    public function update(Request $request, FilterType $filterType)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:filter_types,name,' . $filterType->id,
            'display_name' => 'required|string|max:100',
            'type'         => 'required|in:select,color,boolean,range',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);

        $filterType->update([
            'name'         => $validated['name'],
            'display_name' => $validated['display_name'],
            'type'         => $validated['type'],
            'sort_order'   => $validated['sort_order'] ?? 0,
            'is_active'    => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.filter-types.index')
            ->with('success', 'Filter type update ho gaya!');
    }

    public function destroy(FilterType $filterType)
    {
        if ($filterType->values()->count() > 0) {
            return back()->with('error', 'Pehle is filter ki saari values delete karo!');
        }

        $filterType->delete();

        return redirect()
            ->route('admin.filter-types.index')
            ->with('success', 'Filter type delete ho gaya!');
    }
}