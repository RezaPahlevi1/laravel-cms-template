<?php

namespace App\Http\Middleware;

use App\Services\SiteSettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogEnabled
{
    public function __construct(private SiteSettingService $settings) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->settings->get('blog_enabled', 'true') !== 'true') {
            abort(404);
        }

        return $next($request);
    }
}