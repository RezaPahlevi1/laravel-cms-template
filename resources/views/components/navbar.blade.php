{{--
    Variabel tersedia via View Composer (AppServiceProvider):
    $navTree        — Collection<Page> dengan relasi children.children
    $isBlogEnabled  — bool
    $isContactEnabled — bool
    $settings       — array ['site_name' => ..., 'logo_path' => ..., 'logo_mode' => ...]
--}}

@use('Illuminate\Support\Facades\Storage')

<header
    x-data="navbar()"
    x-init="init()"
    :class="[
        'fixed top-0 left-0 right-0 z-50 transition-all duration-300',
        scrolled ? 'bg-white shadow-md' : 'bg-white'
    ]"
>
    <nav class="container-base">
        <div class="flex items-center justify-between h-16 lg:h-18">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 shrink-0">
                @if(($settings['logo_mode'] ?? 'text') === 'image' && !empty($settings['logo_path']))
                    <img
                        src="{{ $settings['logo_path'] ? Storage::url($settings['logo_path']) : '' }}"
                        alt="{{ $settings['site_name'] ?? config('app.name') }}"
                        class="h-9 w-auto object-contain"
                    >
                @else
                    <span class="text-xl font-bold text-primary tracking-tight">
                        {{ $settings['site_name'] ?? config('app.name') }}
                    </span>
                @endif
            </a>

            {{-- Desktop Nav --}}
            <ul class="hidden lg:flex items-center gap-1">

                {{-- Home link --}}
                <li>
                    <a
                        href="/"
                        class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                               text-text-base hover:text-primary hover:bg-surface"
                    >
                        Home
                    </a>
                </li>

                {{-- Dynamic pages --}}
                @foreach($navTree as $page)
                    @if($page->children->isEmpty())
                        {{-- Level 1: no children --}}
                        <li>
                            <a
                                href="{{ $page->full_path }}"
                                class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                                       text-text-base hover:text-primary hover:bg-surface"
                            >
                                {{ $page->title }}
                            </a>
                        </li>
                    @else
                        {{-- Level 1: has children — dropdown --}}
                        <li
                            x-data="{ open: false }"
                            @mouseenter="open = true"
                            @mouseleave="open = false"
                            class="relative"
                        >
                            <button
                                @click="open = !open"
                                class="flex items-center gap-1 px-3 py-2 text-sm font-medium rounded-md
                                       transition-colors text-text-base hover:text-primary hover:bg-surface"
                                :aria-expanded="open"
                            >
                                {{ $page->title }}
                                <svg
                                    class="w-4 h-4 transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Level 2 dropdown --}}
                            <ul
                                x-show="open"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute top-full left-0 mt-1 w-52 bg-white rounded-lg
                                       shadow-lg border border-border overflow-hidden"
                                style="display: none;"
                            >
                                @foreach($page->children as $child)
                                    @if($child->children->isEmpty())
                                        {{-- Level 2: no children --}}
                                        <li>
                                            <a
                                                href="{{ $child->full_path }}"
                                                class="block px-4 py-2.5 text-sm text-text-base
                                                       hover:bg-surface hover:text-primary transition-colors"
                                            >
                                                {{ $child->title }}
                                            </a>
                                        </li>
                                    @else
                                        {{-- Level 2: has children — fly-out --}}
                                        <li
                                            x-data="{ openSub: false }"
                                            @mouseenter="openSub = true"
                                            @mouseleave="openSub = false"
                                            class="relative"
                                        >
                                            <button
                                                class="flex items-center justify-between w-full px-4 py-2.5
                                                       text-sm text-text-base hover:bg-surface
                                                       hover:text-primary transition-colors"
                                            >
                                                {{ $child->title }}
                                                <svg class="w-4 h-4 -rotate-90" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>

                                            {{-- Level 3 fly-out --}}
                                            <ul
                                                x-show="openSub"
                                                x-transition:enter="transition ease-out duration-150"
                                                x-transition:enter-start="opacity-0 -translate-x-1"
                                                x-transition:enter-end="opacity-100 translate-x-0"
                                                x-transition:leave="transition ease-in duration-100"
                                                x-transition:leave-start="opacity-100 translate-x-0"
                                                x-transition:leave-end="opacity-0 -translate-x-1"
                                                class="absolute left-full top-0 w-52 bg-white rounded-lg
                                                       shadow-lg border border-border overflow-hidden"
                                                style="display: none;"
                                            >
                                                @foreach($child->children as $grandchild)
                                                    <li>
                                                        <a
                                                            href="{{ $grandchild->full_path }}"
                                                            class="block px-4 py-2.5 text-sm text-text-base
                                                                   hover:bg-surface hover:text-primary transition-colors"
                                                        >
                                                            {{ $grandchild->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach

                {{-- Blog link — conditional --}}
                @if($isBlogEnabled)
                    <li>
                        <a
                            href="/blog"
                            class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   text-text-base hover:text-primary hover:bg-surface"
                        >
                            {{ $settings['blog_title'] ?? 'Blog' }}
                        </a>
                    </li>
                @endif

                {{-- Contact link — conditional --}}
                @if($isContactEnabled)
                    <li>
                        <a
                            href="/contact-us"
                            class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                                   text-text-base hover:text-primary hover:bg-surface"
                        >
                            Contact Us
                        </a>
                    </li>
                @endif

                {{-- Search trigger --}}
                <li class="ml-2">
                    <button
                        @click="searchOpen = !searchOpen"
                        class="p-2 rounded-md text-text-muted hover:text-primary
                               hover:bg-surface transition-colors"
                        aria-label="Cari"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </li>

            </ul>

            {{-- Mobile: search + hamburger --}}
            <div class="flex items-center gap-2 lg:hidden">
                <button
                    @click="searchOpen = !searchOpen"
                    class="p-2 rounded-md text-text-muted hover:text-primary transition-colors"
                    aria-label="Cari"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <button
                    @click="mobileOpen = !mobileOpen"
                    class="p-2 rounded-md text-text-muted hover:text-primary transition-colors"
                    aria-label="Menu"
                    :aria-expanded="mobileOpen"
                >
                    <svg x-show="!mobileOpen" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-6 h-6" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                         style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>

        {{-- Search bar (inline, muncul di bawah nav) --}}
        <div
            x-show="searchOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="border-t border-border py-3"
            style="display: none;"
            @click.outside="searchOpen = false"
        >
            @include('components.search-bar')
        </div>

    </nav>

    {{-- Mobile menu --}}
    <div
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden bg-white border-t border-border shadow-lg"
        style="display: none;"
    >
        <ul class="container-base py-4 space-y-1">

            <li>
                <a href="/"
                   class="block px-3 py-2 rounded-md text-sm font-medium text-text-base
                          hover:bg-surface hover:text-primary transition-colors"
                   @click="mobileOpen = false">
                    Home
                </a>
            </li>

            @foreach($navTree as $page)
                @if($page->children->isEmpty())
                    <li>
                        <a href="{{ $page->full_path }}"
                           class="block px-3 py-2 rounded-md text-sm font-medium text-text-base
                                  hover:bg-surface hover:text-primary transition-colors"
                           @click="mobileOpen = false">
                            {{ $page->title }}
                        </a>
                    </li>
                @else
                    <li x-data="{ openMob: false }">
                        <button
                            @click="openMob = !openMob"
                            class="flex items-center justify-between w-full px-3 py-2 rounded-md
                                   text-sm font-medium text-text-base hover:bg-surface
                                   hover:text-primary transition-colors"
                        >
                            {{ $page->title }}
                            <svg class="w-4 h-4 transition-transform duration-200"
                                 :class="openMob ? 'rotate-180' : ''"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <ul x-show="openMob" class="mt-1 ml-4 space-y-1" style="display: none;">
                            @foreach($page->children as $child)
                                @if($child->children->isEmpty())
                                    <li>
                                        <a href="{{ $child->full_path }}"
                                           class="block px-3 py-2 rounded-md text-sm text-text-muted
                                                  hover:bg-surface hover:text-primary transition-colors"
                                           @click="mobileOpen = false">
                                            {{ $child->title }}
                                        </a>
                                    </li>
                                @else
                                    <li x-data="{ openMobSub: false }">
                                        <button
                                            @click="openMobSub = !openMobSub"
                                            class="flex items-center justify-between w-full px-3 py-2
                                                   rounded-md text-sm text-text-muted hover:bg-surface
                                                   hover:text-primary transition-colors"
                                        >
                                            {{ $child->title }}
                                            <svg class="w-4 h-4 transition-transform duration-200"
                                                 :class="openMobSub ? 'rotate-180' : ''"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <ul x-show="openMobSub" class="mt-1 ml-4 space-y-1" style="display: none;">
                                            @foreach($child->children as $grandchild)
                                                <li>
                                                    <a href="{{ $grandchild->full_path }}"
                                                       class="block px-3 py-2 rounded-md text-xs
                                                              text-text-light hover:bg-surface
                                                              hover:text-primary transition-colors"
                                                       @click="mobileOpen = false">
                                                        {{ $grandchild->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

            @if($isBlogEnabled)
                <li>
                    <a href="/blog"
                       class="block px-3 py-2 rounded-md text-sm font-medium text-text-base
                              hover:bg-surface hover:text-primary transition-colors"
                       @click="mobileOpen = false">
                        {{ $settings['blog_title'] ?? 'Blog' }}
                    </a>
                </li>
            @endif

            @if($isContactEnabled)
                <li>
                    <a href="/contact-us"
                       class="block px-3 py-2 rounded-md text-sm font-medium text-text-base
                              hover:bg-surface hover:text-primary transition-colors"
                       @click="mobileOpen = false">
                        Contact Us
                    </a>
                </li>
            @endif

        </ul>
    </div>

</header>

{{-- Spacer agar konten tidak tertutup navbar fixed --}}
<div class="h-16 lg:h-18"></div>

{{-- Alpine component --}}
<script>
    function navbar() {
        return {
            scrolled: false,
            mobileOpen: false,
            searchOpen: false,
            init() {
                window.addEventListener('scroll', () => {
                    this.scrolled = window.scrollY > 10;
                });
            }
        }
    }
</script>