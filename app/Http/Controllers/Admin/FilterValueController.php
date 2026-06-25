<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FilterType;
use App\Models\FilterValue;
use Illuminate\Http\Request;

class FilterValueController extends Controller
{
    public function store(Request $request, FilterType $filterType)
    {
        $validated = $request->validate([
            'value'      => 'required|string|max:100',
            'label'      => 'required|string|max:100',
            'meta'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $filterType->values()->create([
            'value'      => $validated['value'],
            'label'      => $validated['label'],
            'meta'       => $validated['meta'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active'  => true,
        ]);

        return back()->with('success', '"' . $validated['label'] . '" value add ho gayi!');
    }

    public function update(Request $request, FilterValue $filterValue)
    {
        $validated = $request->validate([
            'value'      => 'required|string|max:100',
            'label'      => 'required|string|max:100',
            'meta'       => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);

        $filterValue->update([
            'value'      => $validated['value'],
            'label'      => $validated['label'],
            'meta'       => $validated['meta'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Value update ho gayi!');
    }

    public function destroy(FilterValue $filterValue)
    {
        $filterValue->delete();
        return back()->with('success', 'Value delete ho gayi!');
    }

    // AJAX toggle
    public function toggle(FilterValue $filterValue)
    {
        $filterValue->update(['is_active' => !$filterValue->is_active]);
        return response()->json(['success' => true, 'is_active' => $filterValue->is_active]);
    }
}