<a href="{{ route('blog.show', $post->slug) }}"
   class="group flex flex-col bg-white rounded-xl border border-border
          hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">

    {{-- Thumbnail --}}
    @if($post->thumbnail_url)
        <div class="relative h-44 overflow-hidden bg-surface-alt shrink-0">
            <img
                src="{{ $post->thumbnail_url }}"
                alt="{{ $post->title }}"
                class="w-full h-full object-cover group-hover:scale-105
                       transition-transform duration-500"
                loading="lazy"
            >
        </div>
    @else
        <div class="h-44 bg-surface-alt flex items-center justify-center shrink-0">
            <svg class="w-10 h-10 text-border" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    @endif

    {{-- Content --}}
    <div class="flex flex-col flex-1 p-4">

        {{-- Category + Date --}}
        <div class="flex items-center justify-between gap-2 mb-2">
            @if($post->category)
                <span class="text-xs px-2 py-0.5 bg-primary/10 text-primary
                             rounded font-medium truncate">
                    {{ $post->category->name }}
                </span>
            @else
                <span></span>
            @endif
            @if($post->published_at)
                <span class="text-xs text-text-light shrink-0">
                    {{ $post->published_at->translatedFormat('d M Y') }}
                </span>
            @endif
        </div>

        {{-- Title --}}
        <h3 class="text-sm font-semibold text-primary-dark leading-snug
                   group-hover:text-primary transition-colors line-clamp-2">
            {{ $post->title }}
        </h3>

        {{-- Excerpt --}}
        @if($post->excerpt)
            <p class="mt-1.5 text-xs text-text-muted leading-relaxed line-clamp-3 flex-1">
                {{ $post->excerpt }}
            </p>
        @endif

        {{-- Tags --}}
        @if($post->tags->isNotEmpty())
            <div class="mt-3 flex flex-wrap gap-1">
                @foreach($post->tags->take(3) as $tag)
                    <span class="text-xs px-2 py-0.5 bg-surface-alt border border-border
                                 rounded-full text-text-light">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

    </div>

</a>