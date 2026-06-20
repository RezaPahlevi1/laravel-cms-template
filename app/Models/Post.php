<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Concerns\SanitizesContent;

class Post extends Model
{
    use HasSlug, Searchable, SanitizesContent;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail_path',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
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
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    // ── Scopes ─────────────────────────────────────────────
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    // ── Scout ──────────────────────────────────────────────
    public function toSearchableArray(): array
    {
        return [
            'title'   => $this->title,
            'content' => strip_tags($this->content ?? ''),
            'excerpt' => $this->excerpt ?? '',
        ];
    }

    public function shouldBeSearchable(): bool
    {
        return $this->is_published;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail_path ? Storage::url($this->thumbnail_path) : null;
    }
}