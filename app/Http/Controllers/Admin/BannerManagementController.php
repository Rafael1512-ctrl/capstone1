<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerManagementController extends Controller
{
    /**
     * Display a listing of banners.
     */
    public function index()
    {
        $banners = Banner::orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created banner.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:100',
            'subtitle' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'image_url' => 'nullable|string|max:512',
            'background_url' => 'required|string|max:512',
        ]);

        if (!$validated['title']) {
            $validated['title'] = 'Banner #' . (Banner::max('id') + 1);
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner.
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:100',
            'subtitle' => 'nullable|string',
            'badge_text' => 'nullable|string|max:50',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'image_url' => 'nullable|string|max:512',
            'background_url' => 'required|string|max:512',
        ]);

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil diupdate');
    }

    /**
     * Remove the specified banner.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil dihapus');
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Status banner berhasil diubah');
    }
}
