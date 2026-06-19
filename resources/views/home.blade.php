@extends('layouts.app')

@section('title', $settings['site_name'] ?? config('app.name'))

@section('content')

    {{-- Hero Slider --}}
    @include('components.hero-slider')

    {{-- Tagline Band --}}
    @if(!empty($settings['site_tagline']))
        <div class="bg-surface border-b border-border">
            <div class="container-base py-4 flex items-center justify-center gap-4">
                <span class="hidden sm:block flex-1 max-w-[60px] h-px bg-border"></span>
                <p class="text-sm tracking-wider text-text-muted uppercase text-center">
                    {{ $settings['site_tagline'] }}
                </p>
                <span class="hidden sm:block flex-1 max-w-[60px] h-px bg-border"></span>
            </div>
        </div>
    @endif

    {{-- Branch Cards --}}
    @include('components.branch-cards')

    {{-- What We Do + Gallery --}}
    @if(!empty($settings['what_we_do_body']) || $galleryImages->isNotEmpty())
        <section class="bg-surface-alt border-b border-border">
            <div class="container-base py-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

                    {{-- Kiri: What We Do --}}
                    @if(!empty($settings['what_we_do_body']))
                        <div>
                            <h2 class="text-lg font-bold text-primary-dark mb-4">
                                {{ $settings['what_we_do_heading'] ?? 'What We Do' }}
                            </h2>
                            <div class="text-sm text-text-muted leading-relaxed prose-content">
                                {!! $settings['what_we_do_body'] !!}
                            </div>
                        </div>
                    @endif

                    {{-- Kanan: Gallery --}}
                    @if($galleryImages->isNotEmpty())
                        <div
                            x-data="{
                                lightboxOpen: false,
                                lightboxIndex: 0,
                                lightboxImages: [],
                                openLightbox(index, images) {
                                    this.lightboxIndex = index;
                                    this.lightboxImages = images;
                                    this.lightboxOpen = true;
                                },
                                lightboxNext() {
                                    this.lightboxIndex = (this.lightboxIndex + 1) % this.lightboxImages.length;
                                },
                                lightboxPrev() {
                                    this.lightboxIndex = (this.lightboxIndex - 1 + this.lightboxImages.length) % this.lightboxImages.length;
                                }
                            }"
                        >
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-bold text-primary-dark">Galeri</h2>
                                @if($galleryImages->hasPages())
                                    <a href="#galeri"
                                       class="text-xs text-secondary hover:text-secondary-light
                                              transition-colors font-medium">
                                        Lihat semua →
                                    </a>
                                @endif
                            </div>

                            @include('components.gallery-grid')
                            @include('components.lightbox')
                        </div>
                    @endif

                </div>
            </div>
        </section>
    @endif

@endsection