<?php

namespace App\Providers;

use App\Models\Page;
use App\Observers\PageObserver;
use App\Services\NavigationService;
use App\Services\SiteSettingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Page::observe(PageObserver::class);

        // Inject data global ke semua Blade view
        View::composer('*', function ($view) {
            $navService     = app(NavigationService::class);
            $settingService = app(SiteSettingService::class);

            $view->with([
                'navTree'       => $navService->getNavTree(),
                'isBlogEnabled' => $navService->isBlogEnabled(),
                'settings'      => $settingService->all(),
            ]);
        });
    }
}