<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterProject extends Model
{
    protected $fillable = [
        'youtube_url',
        'youtube_id',
        'title',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function getThumbnailUrlAttribute(): string
    {
        return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
    }

    protected static function booting(): void
    {
        static::creating(function ($model) {
            if (is_null($model->sort_order) || $model->sort_order === 0) {
                $model->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });
    }
}