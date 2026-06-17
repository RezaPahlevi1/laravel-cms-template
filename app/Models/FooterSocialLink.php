<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSocialLink extends Model
{
    protected $fillable = [
        'platform',
        'url',
        'icon',
        'sort_order',
    ];

    protected $casts = [
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
}