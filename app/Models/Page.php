<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Concerns\SanitizesContent;
use App\Models\SiteSetting;

class Page extends Model
{
    use HasSlug, Searchable, SanitizesContent;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'full_path',
        'content',
        'hero_image_path',
        'is_published',
        'show_in_nav',
        'sort_order',
        'depth',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'show_in_nav'  => 'boolean',
        'sort_order'   => 'integer',
        'depth'        => 'integer',
    ];

    // ── Sluggable ──────────────────────────────────────────
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── Relasi ─────────────────────────────────────────────
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Page::class, 'parent_id')
            ->orderBy('sort_order');
    }

    // ── Scopes ─────────────────────────────────────────────
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeVisibleInNav(Builder $query): Builder
    {
        return $query->where('show_in_nav', true);
    }

    public function scopeRootPages(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    // ── Scout ──────────────────────────────────────────────
    public function toSearchableArray(): array
    {
        return [
            'title'   => $this->title,
            'content' => strip_tags($this->content ?? ''),
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->is_published;
    }

    public function getHeroImageUrlAttribute(): ?string
    {
        return $this->hero_image_path ? Storage::url($this->hero_image_path) : null;
    }

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: SiteSetting::get('meta_description', '');
    }

    public function getSeoImageAttribute(): ?string
    {
        return $this->hero_image_url;
    }
}