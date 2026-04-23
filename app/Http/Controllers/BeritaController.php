<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    private const CATEGORY = 'berita';
    private const CACHE_KEY = 'telkom_blogs';

    // =========================================================
    // ADMIN CRUD
    // =========================================================

    public function index(Request $request)
    {
        $query = Page::where('category', self::CATEGORY)->with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $beritas = $query->orderBy('published_at', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(15)
                         ->withQueryString();

        $statuses = ['draft', 'published', 'archived'];

        return view('berita.index', compact('beritas', 'statuses'));
    }

    public function create()
    {
        return view('berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'excerpt'        => 'nullable|string|max:500',
            'content'        => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status'         => 'required|in:draft,published,archived',
            'is_featured'    => 'boolean',
        ]);

        $data = [
            'title'       => $validated['title'],
            'slug'        => $this->uniqueSlug($validated['title']),
            'excerpt'     => $validated['excerpt'] ?? null,
            'content'     => $validated['content'],
            'category'    => self::CATEGORY,
            'status'      => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
            'user_id'     => Auth::id(),
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ];

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('berita', 'public');
        }

        Page::create($data);
        Cache::forget(self::CACHE_KEY);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(Page $berita)
    {
        abort_if($berita->category !== self::CATEGORY, 404);
        return view('berita.show', compact('berita'));
    }

    public function edit(Page $berita)
    {
        abort_if($berita->category !== self::CATEGORY, 404);
        return view('berita.edit', compact('berita'));
    }

    public function update(Request $request, Page $berita)
    {
        abort_if($berita->category !== self::CATEGORY, 404);

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'excerpt'        => 'nullable|string|max:500',
            'content'        => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status'         => 'required|in:draft,published,archived',
            'is_featured'    => 'boolean',
        ]);

        $data = [
            'title'       => $validated['title'],
            'excerpt'     => $validated['excerpt'] ?? null,
            'content'     => $validated['content'],
            'status'      => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
        ];

        // Set published_at when first publishing
        if ($validated['status'] === 'published' && !$berita->published_at) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('featured_image')) {
            if ($berita->featured_image) {
                Storage::disk('public')->delete($berita->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('berita', 'public');
        }

        $berita->update($data);
        Cache::forget(self::CACHE_KEY);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Page $berita)
    {
        abort_if($berita->category !== self::CATEGORY, 404);

        if ($berita->featured_image) {
            Storage::disk('public')->delete($berita->featured_image);
        }

        $berita->delete();
        Cache::forget(self::CACHE_KEY);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    // =========================================================
    // PUBLIC
    // =========================================================

    public function publicIndex(Request $request)
    {
        $query = Page::where('category', self::CATEGORY)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $beritas = $query->orderBy('published_at', 'desc')->paginate(9);

        $featured = Page::where('category', self::CATEGORY)
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->first();

        return view('berita.public.index', compact('beritas', 'featured'));
    }

    public function publicShow(Page $berita)
    {
        abort_if($berita->category !== self::CATEGORY || $berita->status !== 'published', 404);

        $related = Page::where('category', self::CATEGORY)
            ->where('status', 'published')
            ->where('id', '!=', $berita->id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('berita.public.show', compact('berita', 'related'));
    }

    // =========================================================
    // IMAGE UPLOAD (TinyMCE)
    // =========================================================

    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $path = $request->file('file')->store('berita/content', 'public');

        return response()->json([
            'location' => Storage::url($path),
        ]);
    }

    // =========================================================
    // HELPERS
    // =========================================================

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;

        while (Page::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
