<?php

namespace App\Services;

use App\Models\SiteSetting;

class SiteSettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return SiteSetting::get($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        SiteSetting::set($key, $value);
    }

    public function all(): array
    {
        return SiteSetting::pluck('value', 'key')->toArray();
    }
}