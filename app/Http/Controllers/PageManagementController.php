<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageManagementController extends Controller
{
    /**
     * Display page management dashboard
     */
    public function index(Request $request)
    {
        $query = Page::with('category');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        // Search by title
        if ($request->has('search') && $request->search !== '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $pages = $query->paginate(15);
        $categories = PageCategory::all();
        $statuses = ['draft', 'published', 'archived'];

        return view('superadmin.page-management', compact('pages', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new page
     */
    public function create()
    {
        $categories = PageCategory::all();
        $templates = $this->getAvailableTemplates();

        return view('superadmin.page-create', compact('categories', 'templates'));
    }

    /**
     * Store a newly created page
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:page_categories,id',
            'template' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->title);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('pages/images', 'public');
        }

        // Handle SEO meta
        $data['seo_meta'] = [
            'title' => $request->seo_title,
            'description' => $request->seo_description,
            'keywords' => $request->seo_keywords,
        ];

        // Set published_at if status is published
        if ($request->status === 'published') {
            $data['published_at'] = now();
        }

        $page = Page::create($data);

        return redirect()->route('superadmin.page-management')
            ->with('success', 'Page created successfully!');
    }

    /**
     * Display the specified page
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(Page $page)
    {
        $categories = PageCategory::all();
        $templates = $this->getAvailableTemplates();

        return view('superadmin.page-edit', compact('page', 'categories', 'templates'));
    }

    /**
     * Update the specified page
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:page_categories,id',
            'template' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string|max:255',
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($request->title);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('pages/images', 'public');
        }

        // Handle SEO meta
        $data['seo_meta'] = [
            'title' => $request->seo_title,
            'description' => $request->seo_description,
            'keywords' => $request->seo_keywords,
        ];

        // Set published_at if status is published
        if ($request->status === 'published' && $page->status !== 'published') {
            $data['published_at'] = now();
        }

        $page->update($data);

        return redirect()->route('superadmin.page-management')
            ->with('success', 'Page updated successfully!');
    }

    /**
     * Remove the specified page
     */
    public function destroy(Page $page)
    {
        // Delete featured image
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return redirect()->route('superadmin.page-management')
            ->with('success', 'Page deleted successfully!');
    }

    /**
     * Publish a page
     */
    public function publish(Page $page)
    {
        $page->update([
            'status' => 'published',
            'published_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Page published successfully!');
    }

    /**
     * Unpublish a page
     */
    public function unpublish(Page $page)
    {
        $page->update([
            'status' => 'draft',
            'published_at' => null
        ]);

        return redirect()->back()
            ->with('success', 'Page unpublished successfully!');
    }

    /**
     * Duplicate a page
     */
    public function duplicate(Page $page)
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = Str::slug($newPage->title);
        $newPage->status = 'draft';
        $newPage->published_at = null;
        $newPage->save();

        return redirect()->route('superadmin.page-management')
            ->with('success', 'Page duplicated successfully!');
    }

    /**
     * Get available templates
     */
    private function getAvailableTemplates()
    {
        return [
            'default' => 'Default Template',
            'landing' => 'Landing Page',
            'blog' => 'Blog Post',
            'about' => 'About Page',
            'contact' => 'Contact Page',
            'gallery' => 'Gallery Page',
            'news' => 'News Page',
            'event' => 'Event Page',
        ];
    }
}
