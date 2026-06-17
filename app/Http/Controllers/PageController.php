<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Support\Facades\Request;

class PageController extends Controller
{
    public function show(string $path)
    {
        $page = Page::where('full_path', '/' . $path)
            ->where('is_published', true)
            ->firstOrFail();

        return view('page', compact('page'));
    }
}