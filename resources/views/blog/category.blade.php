@extends('layouts.app')

@section('title', $category->name . ' — ' . ($settings['blog_title'] ?? 'Blog') . ' — ' . ($settings['site_name'] ?? config('app.name')))

@section('content')

    <div class="bg-surface-alt border-b border-border">
        <div class="container-base py-6">
            <nav class="flex items-center gap-1.5 text-xs text-text-muted mb-2"
                 aria-label="Breadcrumb">
                <a href="/" class="hover:text-primary transition-colors">Home</a>
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="/blog" class="hover:text-primary transition-colors">
                    {{ $settings['blog_title'] ?? 'Blog' }}
                </a>
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-text-base font-medium">{{ $category->name }}</span>
            </nav>
            <h1 class="text-2xl font-bold text-primary-dark">{{ $category->name }}</h1>
            @if($category->description)
                <p class="mt-1 text-sm text-text-muted">{{ $category->description }}</p>
            @endif
        </div>
    </div>

    <div class="container-base py-8">
        @if($posts->isEmpty())
            <div class="text-center py-12">
                <p class="text-text-muted">Belum ada artikel dalam kategori ini.</p>
                <a href="/blog" class="mt-3 inline-block text-sm text-secondary hover:underline">
                    ← Kembali ke Blog
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    @include('components.partials.post-card', ['post' => $post])
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection