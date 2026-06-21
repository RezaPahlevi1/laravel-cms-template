<?php

namespace App\Services;

use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class NavigationService
{
    public function getNavTree(): Collection
    {
        return Cache::remember('nav_tree', config('cms.cache.nav_ttl'), function () {
            return Page::with('children.children')
                ->whereNull('parent_id')
                ->where('is_published', true)
                ->where('show_in_nav', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function isBlogEnabled(): bool
    {
        return Cache::remember('site_setting_blog_enabled', config('cms.cache.settings_ttl'), fn () =>
            SiteSetting::get('blog_enabled', 'true') === 'true'
        );
    }

    public function isContactEnabled(): bool
    {
        return Cache::remember('site_setting_contact_enabled', config('cms.cache.settings_ttl'), fn () =>
            SiteSetting::get('contact_enabled', 'true') === 'true'
        );
    }
}