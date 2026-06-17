@extends('layouts.app')

@section('title', ($settings['blog_title'] ?? 'Blog') . ' — ' . ($settings['site_name'] ?? config('app.name')))

@section('content')

    <div class="bg-surface-alt border-b border-border">
        <div class="container-base py-6">
            <h1 class="text-2xl font-bold text-primary-dark">
                {{ $settings['blog_title'] ?? 'Blog' }}
            </h1>
        </div>
    </div>

    <div class="container-base py-8">
        @if($posts->isEmpty())
            <div class="text-center py-12">
                <p class="text-text-muted">Belum ada artikel yang dipublikasikan.</p>
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