<?php

namespace App\Http\Controllers\Admin;

use App\Models\EventCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventCategoryController extends Controller
{
    /**
     * Display a listing of all event categories
     */
    public function index()
    {
        $categories = EventCategory::orderBy('name')->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:kategori_acara',
            'description' => 'nullable|string',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        EventCategory::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat');
    }

    /**
     * Show the form for editing a category
     */
    public function edit($category_id)
    {
        $category = EventCategory::findOrFail($category_id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update a category
     */
    public function update(Request $request, $category_id)
    {
        $category = EventCategory::findOrFail($category_id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:kategori_acara,name,' . $category_id . ',category_id',
            'description' => 'nullable|string',
            'color' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Delete a category
     */
    public function destroy($category_id)
    {
        $category = EventCategory::findOrFail($category_id);
        // Check if category has events
        if ($category->events()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus kategori yang memiliki event');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus');
    }
}
