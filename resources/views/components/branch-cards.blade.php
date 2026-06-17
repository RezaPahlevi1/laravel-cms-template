@if($branchCards->isNotEmpty())
<section class="bg-white border-b border-border">
    <div class="container-base py-8">

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($branchCards as $card)
                <div class="group {{ $card->link_url ? 'cursor-pointer' : '' }}"
                    @if($card->link_url) onclick="window.location='{{ $card->link_url }}'" @endif>

                    <div class="relative aspect-4/3 overflow-hidden rounded-lg bg-surface-alt mb-2">
                        @if($card->image_url)
                            <img
                                src="{{ $card->image_url }}"
                                alt="{{ $card->title }}"
                                class="w-full h-full object-cover group-hover:scale-105
                                       transition-transform duration-500"
                                loading="lazy"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-border" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <h3 class="text-sm font-semibold text-primary-dark leading-snug
                               group-hover:text-secondary transition-colors">
                        {{ $card->title }}
                    </h3>
                    @if($card->description)
                        <p class="mt-0.5 text-xs text-text-muted leading-relaxed line-clamp-2">
                            {{ $card->description }}
                        </p>
                    @endif

                </div>
            @endforeach
        </div>

    </div>
</section>
@endif