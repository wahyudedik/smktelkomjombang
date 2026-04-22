<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\PageVersion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource (public view).
     */
    public function index(Request $request)
    {
        $query = Page::published()->with('user');

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
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
        $categories = Page::distinct()->pluck('category')->filter();
        $statuses = ['published', 'draft', 'archived'];

        return view('pages.index', compact('pages', 'categories', 'statuses'));
    }

    /**
     * Display admin listing of pages (superadmin only).
     */
    public function admin(Request $request)
    {
        // Middleware will handle authorization

        $query = Page::with('user');

        // Filter by status (lebih robust: check filled)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category (lebih robust: check filled)
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search by title (lebih robust: check filled dan trim)
        if ($request->filled('search')) {
            $search = trim($request->search);
            if ($search !== '') {
                $query->where('title', 'like', '%' . $search . '%');
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $pages = $query->paginate(15)->withQueryString(); // Preserve query string saat pagination
        $categories = Page::distinct()->pluck('category')->filter();
        $statuses = ['draft', 'published', 'archived'];

        return view('pages.admin', compact('pages', 'categories', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Middleware will handle authorization

        $templates = $this->getAvailableTemplates();
        $categories = Page::distinct()->pluck('category')->filter();
        $parentPages = Page::whereNull('parent_id')->where('is_menu', true)->get();

        return view('pages.create', compact('templates', 'categories', 'parentPages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'template' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string|max:255',
            'is_menu' => 'boolean',
            'menu_title' => 'nullable|string|max:255',
            'menu_position' => 'nullable|in:header,footer',
            'parent_id' => 'nullable|exists:pages,id',
            'menu_icon' => 'nullable|string|max:100',
            'menu_url' => 'nullable|string|max:500',
            'menu_sort_order' => 'nullable|integer|min:0',
            'menu_target_blank' => 'boolean',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        // Ensure slug is always generated correctly (remove any spaces, special chars)
        $data['slug'] = Str::slug($request->title);

        // ✅ Fix: Ensure is_featured has a default value
        $data['is_featured'] = $request->boolean('is_featured', false);

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

        // Handle menu settings
        $data['is_menu'] = $request->boolean('is_menu', false);
        $data['menu_target_blank'] = $request->boolean('menu_target_blank', false);
        if (!$data['is_menu']) {
            // Reset menu fields if not a menu item
            $data['menu_title'] = null;
            $data['menu_position'] = 'header';
            $data['parent_id'] = null;
            $data['menu_icon'] = null;
            $data['menu_url'] = null;
            $data['menu_sort_order'] = 0;
            $data['menu_target_blank'] = false;
        } else {
            $data['menu_title'] = $request->menu_title ?: null;
            $data['menu_position'] = $request->menu_position ?: 'header';
            $data['parent_id'] = $request->parent_id ?: null;
            $data['menu_icon'] = $request->menu_icon ?: null;

            // Handle menu_url: if it's a slug (no / or http), treat as invalid and set to null
            // This ensures menu_url is only used for custom URLs, not slugs
            $menuUrl = $request->menu_url ?: null;
            if (!empty($menuUrl)) {
                // If it's not a full URL and doesn't start with /, it's probably a slug - ignore it
                if (!filter_var($menuUrl, FILTER_VALIDATE_URL) && !str_starts_with($menuUrl, '/')) {
                    $menuUrl = null; // Will use auto-generated route instead
                }
            }
            $data['menu_url'] = $menuUrl;

            $data['menu_sort_order'] = $request->menu_sort_order ?? 0;
        }

        // Set published_at if status is published
        if ($request->status === 'published') {
            $data['published_at'] = now();
        }

        // Use transaction for page creation with version
        $page = DB::transaction(function () use ($data) {
            $page = Page::create($data);

            // Create initial version
            PageVersion::createFromPage($page, 'Initial version');

            // Clear menu cache after creating page with menu
            if ($data['is_menu'] ?? false) {
                cache()->forget('header_menus');
                cache()->forget('footer_menus');
            }

            return $page;
        });

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        // Check if page is published or user has permission
        $user = Auth::user();
        if (!$page->isPublished() && (!$user || !$user->hasRole('superadmin'))) {
            abort(403, 'Page not found or not published.');
        }

        return view('pages.show', compact('page'));
    }

    /**
     * Get menu data for header/footer.
     */
    public function getMenus()
    {
        $headerMenus = Page::menu()
            ->menuPosition('header')
            ->mainMenu()
            ->orderBy('menu_sort_order')
            ->with('children')
            ->get();

        $footerMenus = Page::menu()
            ->menuPosition('footer')
            ->mainMenu()
            ->orderBy('menu_sort_order')
            ->with('children')
            ->get();

        return [
            'header' => $headerMenus,
            'footer' => $footerMenus
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        $templates = $this->getAvailableTemplates();
        $categories = Page::distinct()->pluck('category')->filter();
        $parentPages = Page::whereNull('parent_id')->where('is_menu', true)->where('id', '!=', $page->id)->get();

        return view('pages.edit', compact('page', 'templates', 'categories', 'parentPages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'template' => 'required|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'seo_title' => 'nullable|string|max:60',
            'seo_description' => 'nullable|string|max:160',
            'seo_keywords' => 'nullable|string|max:255',
            'is_menu' => 'boolean',
            'menu_title' => 'nullable|string|max:255',
            'menu_position' => 'nullable|in:header,footer',
            'parent_id' => 'nullable|exists:pages,id',
            'menu_icon' => 'nullable|string|max:100',
            'menu_url' => 'nullable|string|max:500',
            'menu_sort_order' => 'nullable|integer|min:0',
            'menu_target_blank' => 'boolean',
        ]);

        $data = $request->all();
        // Ensure slug is always generated correctly (remove any spaces, special chars)
        $data['slug'] = Str::slug($request->title);

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

        // Handle menu settings
        $data['is_menu'] = $request->boolean('is_menu', false);
        $data['menu_target_blank'] = $request->boolean('menu_target_blank', false);
        if (!$data['is_menu']) {
            // Reset menu fields if not a menu item
            $data['menu_title'] = null;
            $data['menu_position'] = 'header';
            $data['parent_id'] = null;
            $data['menu_icon'] = null;
            $data['menu_url'] = null;
            $data['menu_sort_order'] = 0;
            $data['menu_target_blank'] = false;
        } else {
            $data['menu_title'] = $request->menu_title ?: null;
            $data['menu_position'] = $request->menu_position ?: 'header';
            $data['parent_id'] = $request->parent_id ?: null;
            $data['menu_icon'] = $request->menu_icon ?: null;

            // Handle menu_url: if it's a slug (no / or http), treat as invalid and set to null
            // This ensures menu_url is only used for custom URLs, not slugs
            $menuUrl = $request->menu_url ?: null;
            if (!empty($menuUrl)) {
                // If it's not a full URL and doesn't start with /, it's probably a slug - ignore it
                if (!filter_var($menuUrl, FILTER_VALIDATE_URL) && !str_starts_with($menuUrl, '/')) {
                    $menuUrl = null; // Will use auto-generated route instead
                }
            }
            $data['menu_url'] = $menuUrl;

            $data['menu_sort_order'] = $request->menu_sort_order ?? 0;
        }

        // Set published_at if status is published
        if ($request->status === 'published' && $page->status !== 'published') {
            $data['published_at'] = now();
        }

        $page->update($data);

        // Create new version if there are changes
        if ($page->wasChanged()) {
            PageVersion::createFromPage($page, $request->change_summary ?? 'Page updated');
        }

        // Clear menu cache after updating page with menu
        if ($data['is_menu'] ?? $page->is_menu) {
            cache()->forget('header_menus');
            cache()->forget('footer_menus');
        }

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        // Delete featured image
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    /**
     * Publish a page.
     */
    public function publish(Page $page)
    {
        $page->publish();

        return redirect()->back()
            ->with('success', 'Page published successfully.');
    }

    /**
     * Unpublish a page.
     */
    public function unpublish(Page $page)
    {
        $page->unpublish();

        return redirect()->back()
            ->with('success', 'Page unpublished successfully.');
    }

    /**
     * Duplicate a page.
     */
    public function duplicate(Page $page)
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = Str::slug($newPage->title);
        $newPage->status = 'draft';
        $newPage->published_at = null;
        $newPage->save();

        return redirect()->route('admin.pages.edit', $newPage)
            ->with('success', 'Page duplicated successfully.');
    }

    /**
     * Show page versions.
     */
    public function versions(Page $page)
    {
        $versions = $page->versions()->with('user')->latest()->paginate(10);
        return view('pages.versions', compact('page', 'versions'));
    }

    /**
     * Restore a specific version.
     */
    public function restoreVersion(Page $page, PageVersion $version)
    {
        $version->restoreToPage();

        return redirect()->route('admin.pages.show', $page)
            ->with('success', "Page restored to version {$version->version_number}.");
    }

    /**
     * Compare two versions.
     */
    public function compareVersions(Page $page, PageVersion $version1, PageVersion $version2)
    {
        return view('pages.compare', compact('page', 'version1', 'version2'));
    }

    /**
     * Get available templates.
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
        ];
    }

    /**
     * Display a listing of published pages (public view).
     */
    public function publicIndex(Request $request)
    {
        $query = Page::where('status', 'published')
            ->orderBy('published_at', 'desc');

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Search by title
        if ($request->has('search') && $request->search !== '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $pages = $query->paginate(12);
        $categories = Page::where('status', 'published')
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('pages.public.index', compact('pages', 'categories'));
    }

    /**
     * Display the specified page (public view).
     */
    public function publicShow($slug)
    {
        // Decode URL-encoded slug if needed (handles %20 for spaces)
        $decodedSlug = urldecode($slug);

        // Try to find page by slug
        $page = Page::where(function ($query) use ($slug, $decodedSlug) {
            $query->where('slug', $decodedSlug)
                ->orWhere('slug', $slug); // Also try original slug
        })
            ->where('status', 'published')
            ->first();

        // If not found, try to sanitize and search again
        if (!$page) {
            $sanitizedSlug = Str::slug($decodedSlug);
            $page = Page::where('slug', $sanitizedSlug)
                ->where('status', 'published')
                ->first();
        }

        // If still not found, return 404
        if (!$page) {
            abort(404, 'Page not found.');
        }

        // Increment views count (optional)
        // $page->increment('views_count');

        return view('pages.public.show', compact('page'));
    }
}
