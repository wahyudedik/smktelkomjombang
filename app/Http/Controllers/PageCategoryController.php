<?php

namespace App\Http\Controllers;

use App\Models\PageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageCategoryController extends Controller
{
    /**
     * Display page categories management
     */
    public function index()
    {
        $categories = PageCategory::withCount('pages')->paginate(15);
        return view('superadmin.page-categories', compact('categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:page_categories,slug',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($request->name);
        }

        PageCategory::create($data);

        return redirect()->route('superadmin.page-categories')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, PageCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:page_categories,slug,' . $category->id,
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($request->name);
        }

        $category->update($data);

        return redirect()->route('superadmin.page-categories')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category
     */
    public function destroy(PageCategory $category)
    {
        // Check if category has pages
        if ($category->pages()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with existing pages. Please move or delete the pages first.');
        }

        $category->delete();

        return redirect()->route('superadmin.page-categories')
            ->with('success', 'Category deleted successfully!');
    }
}
