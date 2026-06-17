<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query   = $request->get('q', '');
        $results = collect();

        if (strlen($query) >= 2) {
            $pages = Page::search($query)->get()
                ->where('is_published', true)
                ->map(fn($p) => [
                    'type'    => 'page',
                    'title'   => $p->title,
                    'url'     => $p->full_path,
                    'excerpt' => mb_strimwidth(strip_tags($p->content ?? ''), 0, 150, '...'),
                ]);

            $posts = Post::search($query)->get()
                ->where('is_published', true)
                ->map(fn($p) => [
                    'type'    => 'post',
                    'title'   => $p->title,
                    'url'     => '/blog/' . $p->slug,
                    'excerpt' => $p->excerpt ?? mb_strimwidth(strip_tags($p->content ?? ''), 0, 150, '...'),
                ]);

            $results = $pages->concat($posts);
        }

        return view('search-results', compact('query', 'results'));
    }

    public function suggest(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $pages = Page::search($query)->get()
            ->where('is_published', true)
            ->take(4)
            ->map(fn($p) => [
                'id'      => $p->id,
                'type'    => 'page',
                'title'   => $p->title,
                'url'     => $p->full_path,
                'excerpt' => mb_strimwidth(strip_tags($p->content ?? ''), 0, 80, '...'),
            ]);

        $posts = Post::search($query)->get()
            ->where('is_published', true)
            ->take(4)
            ->map(fn($p) => [
                'id'      => $p->id,
                'type'    => 'post',
                'title'   => $p->title,
                'url'     => '/blog/' . $p->slug,
                'excerpt' => $p->excerpt ?? mb_strimwidth(strip_tags($p->content ?? ''), 0, 80, '...'),
            ]);

        return response()->json([
            'results' => $pages->concat($posts)->values()->all()
        ]);
    }
}