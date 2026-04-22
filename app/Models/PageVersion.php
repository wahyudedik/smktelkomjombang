<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'version_number',
        'title',
        'content',
        'excerpt',
        'featured_image',
        'category',
        'template',
        'seo_meta',
        'custom_fields',
        'status',
        'is_featured',
        'sort_order',
        'published_at',
        'user_id',
        'change_summary',
    ];

    protected $casts = [
        'seo_meta' => 'array',
        'custom_fields' => 'array',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the page that owns the version.
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the user that created the version.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by version number.
     */
    public function scopeVersion($query, int $version)
    {
        return $query->where('version_number', $version);
    }

    /**
     * Scope to order by version number.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('version_number', 'desc');
    }

    /**
     * Create a new version from a page.
     */
    public static function createFromPage(Page $page, string $changeSummary = null): self
    {
        $latestVersion = $page->versions()->latest()->first();
        $versionNumber = $latestVersion ? $latestVersion->version_number + 1 : 1;

        return self::create([
            'page_id' => $page->id,
            'version_number' => $versionNumber,
            'title' => $page->title,
            'content' => $page->content,
            'excerpt' => $page->excerpt,
            'featured_image' => $page->featured_image,
            'category' => $page->category,
            'template' => $page->template,
            'seo_meta' => $page->seo_meta,
            'custom_fields' => $page->custom_fields,
            'status' => $page->status,
            'is_featured' => $page->is_featured ?? false, // ✅ Fix: Provide default value
            'sort_order' => $page->sort_order ?? 0, // ✅ Fix: Provide default value
            'published_at' => $page->published_at,
            'user_id' => $page->user_id,
            'change_summary' => $changeSummary,
        ]);
    }

    /**
     * Restore this version to the page.
     */
    public function restoreToPage(): Page
    {
        $page = $this->page;

        $page->update([
            'title' => $this->title,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'featured_image' => $this->featured_image,
            'category' => $this->category,
            'template' => $this->template,
            'seo_meta' => $this->seo_meta,
            'custom_fields' => $this->custom_fields,
            'status' => $this->status,
            'is_featured' => $this->is_featured ?? false, // ✅ Fix: Provide default value
            'sort_order' => $this->sort_order,
            'published_at' => $this->published_at,
        ]);

        return $page;
    }
}
