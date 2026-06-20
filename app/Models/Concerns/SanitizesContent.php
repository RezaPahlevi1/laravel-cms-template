<?php

namespace App\Models\Concerns;

use Mews\Purifier\Facades\Purifier;

trait SanitizesContent
{
    public static function bootSanitizesContent(): void
    {
        static::saving(function ($model) {
            if (! empty($model->content)) {
                $model->content = Purifier::clean($model->content, 'cms_content');
            }
        });
    }
}