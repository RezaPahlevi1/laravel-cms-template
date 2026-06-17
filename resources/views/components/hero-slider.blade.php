@if($heroSlides->isEmpty())
    <section class="relative bg-primary" style="height: 360px;">
        <div class="container-base h-full flex items-center">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-white leading-tight">
                    {{ $settings['site_name'] ?? config('app.name') }}
                </h1>
                @if(!empty($settings['site_tagline']))
                    <p class="mt-3 text-slate-300">{{ $settings['site_tagline'] }}</p>
                @endif
            </div>
        </div>
    </section>
@else
    <section
        x-data="heroSlider({{ $heroSlides->count() }})"
        x-init="init()"
        class="relative overflow-hidden bg-primary"
        style="height: 360px;"
    >
        @foreach($heroSlides as $index => $slide)
            <div
                x-show="current === {{ $index }}"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0"
                style="display: none;"
            >
                <div class="absolute inset-0">
                    <img
                        src="{{ $slide->image_url }}"
                        alt="{{ $slide->title ?? '' }}"
                        class="w-full h-full object-cover"
                        @if($index === 0) loading="eager" @else loading="lazy" @endif
                    >
                    <div class="absolute inset-0 bg-linear-to-r from-black/65 via-black/30 to-transparent"></div>
                </div>

                <div class="relative container-base h-full flex items-center">
                    <div class="max-w-lg">
                        @if($slide->title)
                            <h1 class="text-2xl lg:text-4xl font-bold text-white leading-tight">
                                {{ $slide->title }}
                            </h1>
                        @endif
                        @if($slide->subtitle)
                            <p class="mt-3 text-sm lg:text-base text-slate-200 leading-relaxed">
                                {{ $slide->subtitle }}
                            </p>
                        @endif
                        @if($slide->cta_primary_label || $slide->cta_secondary_label)
                            <div class="mt-6 flex flex-wrap gap-3">
                                @if($slide->cta_primary_label && $slide->cta_primary_url)
                                    <a href="{{ $slide->cta_primary_url }}"
                                       class="px-5 py-2 bg-secondary text-white text-sm font-semibold
                                              rounded-lg hover:bg-secondary-light transition-colors">
                                        {{ $slide->cta_primary_label }}
                                    </a>
                                @endif
                                @if($slide->cta_secondary_label && $slide->cta_secondary_url)
                                    <a href="{{ $slide->cta_secondary_url }}"
                                       class="px-5 py-2 bg-white/10 text-white text-sm font-semibold
                                              rounded-lg border border-white/30
                                              hover:bg-white/20 transition-colors">
                                        {{ $slide->cta_secondary_label }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        @if($heroSlides->count() > 1)
            <button @click="prev()"
                class="absolute left-3 top-1/2 -translate-y-1/2 z-10 w-8 h-8 flex items-center
                       justify-center bg-black/30 hover:bg-black/50 text-white rounded-full transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="next()"
                class="absolute right-3 top-1/2 -translate-y-1/2 z-10 w-8 h-8 flex items-center
                       justify-center bg-black/30 hover:bg-black/50 text-white rounded-full transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 flex gap-1.5">
                @foreach($heroSlides as $index => $slide)
                    <button
                        @click="goTo({{ $index }})"
                        :class="current === {{ $index }} ? 'bg-white w-5' : 'bg-white/40 hover:bg-white/70 w-2'"
                        class="h-1.5 rounded-full transition-all duration-300"
                    ></button>
                @endforeach
            </div>
        @endif

    </section>

    <script>
    function heroSlider(total) {
        return {
            current: 0, total: total, timer: null,
            init() { if (this.total > 1) this.startAutoplay(); },
            startAutoplay() { this.timer = setInterval(() => this.next(), 5000); },
            stopAutoplay() { clearInterval(this.timer); },
            next() { this.current = (this.current + 1) % this.total; },
            prev() { this.current = (this.current - 1 + this.total) % this.total; },
            goTo(i) { this.stopAutoplay(); this.current = i; this.startAutoplay(); }
        }
    }
    </script>
@endif