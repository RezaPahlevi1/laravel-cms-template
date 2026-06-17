@extends('layouts.app')

@section('title', $post->title . ' — ' . ($settings['site_name'] ?? config('app.name')))
@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->content ?? ''), 160))

@section('content')

    {{-- Hero / Header --}}
    @if($post->thumbnail_url)
        <div class="relative bg-primary overflow-hidden" style="height: 280px;">
            <img
                src="{{ $post->thumbnail_url }}"
                alt="{{ $post->title }}"
                class="w-full h-full object-cover"
                loading="eager"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
            <div class="absolute inset-0 flex items-end">
                <div class="container-base pb-6">
                    {{-- Breadcrumb manual untuk blog --}}
                    <nav class="flex items-center gap-1.5 text-xs text-white/70 mb-2"
                         aria-label="Breadcrumb">
                        <a href="/" class="hover:text-white transition-colors">Home</a>
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="/blog" class="hover:text-white transition-colors">
                            {{ $settings['blog_title'] ?? 'Blog' }}
                        </a>
                        @if($post->category)
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                            <a href="{{ route('blog.category', $post->category->slug) }}"
                               class="hover:text-white transition-colors">
                                {{ $post->category->name }}
                            </a>
                        @endif
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="text-white font-medium line-clamp-1">{{ $post->title }}</span>
                    </nav>
                    <h1 class="text-2xl lg:text-3xl font-bold text-white leading-tight">
                        {{ $post->title }}
                    </h1>
                </div>
            </div>
        </div>
    @else
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
                    @if($post->category)
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                        <a href="{{ route('blog.category', $post->category->slug) }}"
                           class="hover:text-primary transition-colors">
                            {{ $post->category->name }}
                        </a>
                    @endif
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-text-base font-medium line-clamp-1">{{ $post->title }}</span>
                </nav>
                <h1 class="text-2xl lg:text-3xl font-bold text-primary-dark leading-tight">
                    {{ $post->title }}
                </h1>
            </div>
        </div>
    @endif

    {{-- Content area --}}
    <div class="container-base py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">

            {{-- Main content --}}
            <div class="lg:col-span-2">

                {{-- Meta --}}
                <div class="flex flex-wrap items-center gap-3 mb-6 text-xs text-text-muted">
                    @if($post->published_at)
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $post->published_at->translatedFormat('d F Y') }}
                        </span>
                    @endif
                    @if($post->category)
                        <a href="{{ route('blog.category', $post->category->slug) }}"
                           class="px-2 py-0.5 bg-primary/10 text-primary rounded font-medium
                                  hover:bg-primary/20 transition-colors">
                            {{ $post->category->name }}
                        </a>
                    @endif
                </div>

                {{-- Excerpt --}}
                @if($post->excerpt)
                    <p class="text-sm text-text-muted leading-relaxed mb-6 pb-6
                               border-b border-border italic">
                        {{ $post->excerpt }}
                    </p>
                @endif

                {{-- Body --}}
                <div class="prose-content">
                    {!! $post->content !!}
                </div>

                {{-- Tags --}}
                @if($post->tags->isNotEmpty())
                    <div class="mt-8 pt-6 border-t border-border flex flex-wrap items-center gap-2">
                        <span class="text-xs text-text-muted">Tags:</span>
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}"
                               class="px-2.5 py-1 text-xs bg-surface-alt border border-border
                                      rounded-full text-text-muted hover:border-primary
                                      hover:text-primary transition-colors">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-1">
                @if($relatedPosts->isNotEmpty())
                    <div class="bg-surface-alt rounded-lg p-5">
                        <h3 class="text-sm font-semibold text-primary-dark mb-4">
                            Artikel Terkait
                        </h3>
                        <div class="space-y-4">
                            @foreach($relatedPosts as $related)
                                <a href="{{ route('blog.show', $related->slug) }}"
                                   class="flex gap-3 group">
                                    @if($related->thumbnail_url)
                                        <div class="w-16 h-14 shrink-0 rounded overflow-hidden bg-border">
                                            <img src="{{ $related->thumbnail_url }}"
                                                 alt="{{ $related->title }}"
                                                 class="w-full h-full object-cover
                                                        group-hover:scale-105 transition-transform duration-300"
                                                 loading="lazy">
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-text-base
                                                   group-hover:text-primary transition-colors
                                                   line-clamp-2 leading-snug">
                                            {{ $related->title }}
                                        </p>
                                        @if($related->published_at)
                                            <p class="mt-1 text-xs text-text-light">
                                                {{ $related->published_at->translatedFormat('d M Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>

        </div>
    </div>

@endsection