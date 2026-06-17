@if($galleryImages->isNotEmpty())
    <div class="grid grid-cols-3 gap-2">
        @foreach($galleryImages as $index => $image)
            <button
                @click="openLightbox({{ $index }}, {{ json_encode($galleryImages->map(fn($i) => ['src' => $i->image_url, 'caption' => $i->caption])->values()) }})"
                class="relative aspect-square overflow-hidden rounded bg-surface-alt
                       hover:opacity-90 transition-opacity group"
            >
                <img
                    src="{{ $image->image_url }}"
                    alt="{{ $image->caption ?? 'Galeri' }}"
                    class="w-full h-full object-cover group-hover:scale-105
                           transition-transform duration-500"
                    loading="lazy"
                >
                @if($image->caption)
                    <div class="absolute inset-x-0 bottom-0 bg-linear-to-t from-black/60
                                to-transparent p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <p class="text-xs text-white line-clamp-1">{{ $image->caption }}</p>
                    </div>
                @endif
            </button>
        @endforeach
    </div>

    @if($galleryImages->hasPages())
        <div class="mt-3 flex justify-end">
            {{ $galleryImages->links() }}
        </div>
    @endif
@endif