<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BranchCard extends Model
{
    protected $fillable = [
        'image_path',
        'title',
        'description',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booting(): void
    {
        static::creating(function ($model) {
            if (is_null($model->sort_order) || $model->sort_order === 0) {
                $model->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}