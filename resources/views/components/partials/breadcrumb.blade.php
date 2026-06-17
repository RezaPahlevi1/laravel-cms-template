{{--
    Membangun breadcrumb dari $page->parent secara rekursif.
    Variabel $page harus tersedia di scope yang memanggil partial ini.
--}}
@php
    $crumbs = [];
    $current = $page;

    // Traverse ke atas sampai root
    while ($current->parent) {
        $current = $current->parent;
        array_unshift($crumbs, $current);
    }
@endphp

@if(!empty($crumbs) || isset($extraCrumbs))
    <nav class="mt-1.5 flex items-center gap-1.5 text-xs {{ $page->hero_image_url ? 'text-white/70' : 'text-text-muted' }}"
         aria-label="Breadcrumb">

        <a href="/"
           class="{{ $page->hero_image_url ? 'hover:text-white' : 'hover:text-primary' }} transition-colors">
            Home
        </a>

        @foreach($crumbs as $crumb)
            <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ $crumb->full_path }}"
               class="{{ $page->hero_image_url ? 'hover:text-white' : 'hover:text-primary' }} transition-colors">
                {{ $crumb->title }}
            </a>
        @endforeach

        {{-- Extra crumbs untuk blog (kategori, tag, dll) --}}
        @isset($extraCrumbs)
            @foreach($extraCrumbs as $label => $url)
                <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
                @if($url)
                    <a href="{{ $url }}"
                       class="{{ isset($page) && $page->hero_image_url ? 'hover:text-white' : 'hover:text-primary' }} transition-colors">
                        {{ $label }}
                    </a>
                @else
                    <span class="{{ isset($page) && $page->hero_image_url ? 'text-white' : 'text-text-base' }} font-medium">
                        {{ $label }}
                    </span>
                @endif
            @endforeach
        @endisset

        <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="{{ $page->hero_image_url ? 'text-white' : 'text-text-base' }} font-medium">
            {{ $page->title }}
        </span>

    </nav>
@endif