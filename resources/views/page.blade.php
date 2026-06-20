@extends('layouts.app')

@section('title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('og_image', $page->seo_image)

@section('title', $page->title . ' — ' . ($settings['site_name'] ?? config('app.name')))

@section('meta_description', Str::limit(strip_tags($page->content ?? ''), 160))

@section('content')

    {{-- Hero Banner --}}
    @if($page->hero_image_url)
        <div class="relative bg-primary overflow-hidden" style="height: 220px;">
            <img
                src="{{ $page->hero_image_url }}"
                alt="{{ $page->title }}"
                class="w-full h-full object-cover"
                loading="eager"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/30 to-transparent"></div>
            <div class="absolute inset-0 flex items-end">
                <div class="container-base pb-6">
                    <h1 class="text-2xl lg:text-3xl font-bold text-white">
                        {{ $page->title }}
                    </h1>
                    @include('components.partials.breadcrumb')
                </div>
            </div>
        </div>
    @else
        <div class="bg-surface-alt border-b border-border">
            <div class="container-base py-6">
                <h1 class="text-2xl lg:text-3xl font-bold text-primary-dark">
                    {{ $page->title }}
                </h1>
                @include('components.partials.breadcrumb')
            </div>
        </div>
    @endif

    {{-- Content + Sidebar --}}
    <div class="container-base py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Kolom kiri: Content --}}
            <div class="lg:col-span-2">
                @if($page->content)
                    <div class="prose-content">
                        {!! $page->content !!}
                    </div>
                @else
                    <p class="text-text-muted italic">Konten belum tersedia.</p>
                @endif
            </div>

            {{-- Kolom kanan: Sidebar --}}
            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 space-y-6">

                    {{-- Search --}}
                    @include('components.search-bar')

                    {{-- Company identity --}}
                    <div class="bg-surface-alt rounded-lg border border-border p-5">
                        <p class="text-base font-bold text-primary-dark">
                            {{ $settings['site_name'] ?? config('app.name') }}
                        </p>
                        @if(!empty($settings['site_tagline']))
                            <p class="mt-1.5 text-xs tracking-wide text-text-muted uppercase">
                                {{ $settings['site_tagline'] }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection