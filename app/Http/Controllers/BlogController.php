<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'tags'])
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(SiteSetting::get('blog_per_page', 9));

        return view('blog.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_if(!$post->is_published, 404);

        $post->load(['category', 'tags']);

        $relatedPosts = Post::with(['category'])
            ->where('is_published', true)
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->orderByDesc('published_at')
            ->limit(config('cms.pagination.related_posts_limit'))
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    public function category(Category $category)
    {
        $posts = Post::with(['category', 'tags'])
            ->where('category_id', $category->id)
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(SiteSetting::get('blog_per_page', 9));

        return view('blog.category', compact('category', 'posts'));
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->posts()
            ->with(['category', 'tags'])
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->paginate(SiteSetting::get('blog_per_page', 9));

        return view('blog.tag', compact('tag', 'posts'));
    }
}