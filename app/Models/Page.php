<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Traits\Auditable;

class Page extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'category',
        'template',
        'seo_meta',
        'custom_fields',
        'status',
        'is_featured',
        'is_menu',
        'menu_title',
        'menu_position',
        'parent_id',
        'menu_icon',
        'menu_url',
        'menu_target_blank',
        'menu_sort_order',
        'sort_order',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'seo_meta' => 'array',
        'custom_fields' => 'array',
        'is_featured' => 'boolean',
        'is_menu' => 'boolean',
        'menu_target_blank' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the user that owns the page.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the page versions.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(PageVersion::class);
    }

    /**
     * Get the parent page (for submenu).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    /**
     * Get the child pages (submenu).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')->orderBy('menu_sort_order');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            // Always ensure slug is generated correctly from title
            if (!empty($page->title)) {
                $page->slug = Str::slug($page->title);
            } elseif (empty($page->slug)) {
                // If no title and no slug, set a default
                $page->slug = Str::random(10);
            } else {
                // Sanitize existing slug if it contains invalid characters
                $page->slug = Str::slug($page->slug);
            }
        });

        static::updating(function ($page) {
            // If title changed, regenerate slug
            if ($page->isDirty('title') && !empty($page->title)) {
                $page->slug = Str::slug($page->title);
            }
            // Always sanitize slug to ensure no invalid characters (spaces, special chars)
            if ($page->isDirty('slug') && !empty($page->slug)) {
                $page->slug = Str::slug($page->slug);
            }
            // If slug is empty but title exists, generate slug from title
            if (empty($page->slug) && !empty($page->title)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter published pages.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to filter featured pages.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to filter menu pages.
     */
    public function scopeMenu($query)
    {
        return $query->where('is_menu', true)->where('status', 'published');
    }

    /**
     * Scope to filter by menu position.
     */
    public function scopeMenuPosition($query, string $position)
    {
        return $query->where('menu_position', $position);
    }

    /**
     * Scope to get main menu items (no parent).
     */
    public function scopeMainMenu($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get submenu items.
     */
    public function scopeSubmenu($query, int $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Get the page URL.
     */
    public function getUrlAttribute(): string
    {
        return route('pages.public.show', $this->slug);
    }

    /**
     * Get the menu URL (custom URL or page URL).
     */
    public function getMenuUrlAttribute(): string
    {
        $customUrl = $this->getRawOriginal('menu_url');

        // If custom URL is provided and it's a full URL or starts with /
        if (!empty($customUrl)) {
            // If it's a full URL (http/https), return as is
            if (filter_var($customUrl, FILTER_VALIDATE_URL)) {
                return $customUrl;
            }
            // If it starts with /, it's a relative path, return as is
            if (str_starts_with($customUrl, '/')) {
                return $customUrl;
            }
            // If custom URL looks like a slug (contains spaces or not a valid route), treat it as slug
            // This fixes the issue where slug was saved in menu_url field
            if (str_contains($customUrl, ' ') || !str_starts_with($customUrl, '/')) {
                // Treat it as slug and generate proper route
                $sanitizedSlug = Str::slug($customUrl);
                return route('pages.public.show', $sanitizedSlug);
            }
            return $customUrl;
        }

        // If no custom URL, generate from slug
        if (empty($this->slug)) {
            return '#';
        }

        return route('pages.public.show', $this->slug);
    }

    /**
     * Get the menu title (custom title or page title).
     */
    public function getMenuTitleAttribute($value): string
    {
        return $value ?: $this->title;
    }

    /**
     * Check if page is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' &&
            $this->published_at &&
            $this->published_at <= now();
    }

    /**
     * Publish the page.
     */
    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Unpublish the page.
     */
    public function unpublish(): void
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
