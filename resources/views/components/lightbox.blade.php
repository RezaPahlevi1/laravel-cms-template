{{-- Lightbox overlay — dikontrol via Alpine dari gallery-grid --}}
<div
    x-show="lightboxOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="lightboxOpen = false"
    @keydown.arrow-left.window="lightboxPrev()"
    @keydown.arrow-right.window="lightboxNext()"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90"
    style="display: none;"
    @click.self="lightboxOpen = false"
>
    {{-- Close button --}}
    <button
        @click="lightboxOpen = false"
        class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center
               text-white bg-white/10 hover:bg-white/20 rounded-full transition-colors"
        aria-label="Tutup"
    >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Prev button --}}
    <button
        @click="lightboxPrev()"
        x-show="lightboxImages.length > 1"
        class="absolute left-4 top-1/2 -translate-y-1/2 z-10
               w-10 h-10 flex items-center justify-center
               text-white bg-white/10 hover:bg-white/20 rounded-full transition-colors"
        aria-label="Sebelumnya"
    >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

    {{-- Image --}}
    <div class="relative max-w-5xl max-h-[85vh] w-full mx-16 flex flex-col items-center">
        <template x-if="lightboxImages.length > 0">
            <img
                :src="lightboxImages[lightboxIndex]?.src"
                :alt="lightboxImages[lightboxIndex]?.caption ?? 'Galeri'"
                class="max-h-[80vh] max-w-full object-contain rounded-lg shadow-2xl"
            >
        </template>

        {{-- Caption --}}
        <template x-if="lightboxImages[lightboxIndex]?.caption">
            <p class="mt-3 text-sm text-slate-300 text-center"
               x-text="lightboxImages[lightboxIndex]?.caption">
            </p>
        </template>

        {{-- Counter --}}
        <p class="mt-2 text-xs text-slate-500"
           x-text="`${lightboxIndex + 1} / ${lightboxImages.length}`">
        </p>
    </div>

    {{-- Next button --}}
    <button
        @click="lightboxNext()"
        x-show="lightboxImages.length > 1"
        class="absolute right-4 top-1/2 -translate-y-1/2 z-10
               w-10 h-10 flex items-center justify-center
               text-white bg-white/10 hover:bg-white/20 rounded-full transition-colors"
        aria-label="Berikutnya"
    >
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

</div>