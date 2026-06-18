@php
    use App\Models\FooterProject;
    use App\Models\FooterSocialLink;
    use Illuminate\Support\Facades\Storage;

    $footerProjects    = FooterProject::orderBy('sort_order')->get();
    $footerSocialLinks = FooterSocialLink::orderBy('sort_order')->get();
@endphp

<footer class="bg-primary text-white">

    <div class="container-base py-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">

            {{-- Kolom 1: Logo + Site Name + Social Links --}}
            <div class="lg:col-span-1">
                {{-- Logo --}}
                @if(($settings['logo_mode'] ?? 'text') === 'image' && !empty($settings['logo_path']))
                    <img
                        src="{{ Storage::url($settings['logo_path']) }}"
                        alt="{{ $settings['site_name'] ?? config('app.name') }}"
                        class="h-10 w-auto object-contain mb-3 brightness-0 invert"
                    >
                @endif

                {{-- Site name selalu tampil --}}
                <p class="text-base font-bold text-white tracking-tight">
                    {{ $settings['site_name'] ?? config('app.name') }}
                </p>

                {{-- Social links --}}
                @if($footerSocialLinks->isNotEmpty())
                    <div class="flex items-center gap-3 mt-4">
                        @foreach($footerSocialLinks as $social)
                            <a
                                href="{{ $social->url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-8 h-8 flex items-center justify-center rounded-full
                                       bg-white/10 hover:bg-secondary transition-colors"
                                aria-label="{{ $social->platform }}"
                            >
                                @include('components.partials.social-icon', [
                                    'platform' => $social->icon ?? $social->platform
                                ])
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Kolom 2: About --}}
            <div>
                <h3 class="text-sm font-semibold text-white mb-3 uppercase tracking-wider">
                    About Us
                </h3>
                @if(!empty($settings['footer_about_text']))
                    <p class="text-sm text-slate-300 leading-relaxed">
                        {{ $settings['footer_about_text'] }}
                    </p>
                @else
                    <p class="text-sm text-slate-500 italic">—</p>
                @endif
            </div>

            {{-- Kolom 3: Contacts --}}
            <div>
                <h3 class="text-sm font-semibold text-white mb-3 uppercase tracking-wider">
                    Contacts
                </h3>
                <ul class="space-y-2.5">
                    @if(!empty($settings['footer_contact_address']))
                        <li class="flex gap-2.5 text-sm text-slate-300">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-secondary" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ $settings['footer_contact_address'] }}</span>
                        </li>
                    @endif

                    @if(!empty($settings['footer_contact_phone']))
                        <li class="flex gap-2.5 text-sm text-slate-300">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-secondary" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 8V5z"/>
                            </svg>
                            <a href="tel:{{ $settings['footer_contact_phone'] }}"
                               class="hover:text-white transition-colors">
                                {{ $settings['footer_contact_phone'] }}
                            </a>
                        </li>
                    @endif

                    @if(!empty($settings['footer_contact_fax']))
                        <li class="flex gap-2.5 text-sm text-slate-300">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-secondary" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            <span>Fax: {{ $settings['footer_contact_fax'] }}</span>
                        </li>
                    @endif

                    @if(!empty($settings['footer_contact_email']))
                        <li class="flex gap-2.5 text-sm text-slate-300">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-secondary" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <a href="mailto:{{ $settings['footer_contact_email'] }}"
                               class="hover:text-white transition-colors">
                                {{ $settings['footer_contact_email'] }}
                            </a>
                        </li>
                    @endif

                    @if(empty($settings['footer_contact_address']) &&
                        empty($settings['footer_contact_phone']) &&
                        empty($settings['footer_contact_fax']) &&
                        empty($settings['footer_contact_email']))
                        <li class="text-sm text-slate-500 italic">—</li>
                    @endif
                </ul>
            </div>

            {{-- Kolom 4: Video section (custom title) --}}
            @if($footerProjects->isNotEmpty())
                <div>
                    <h3 class="text-sm font-semibold text-white mb-3 uppercase tracking-wider">
                        {{ $settings['footer_projects_title'] ?? 'Recent Projects' }}
                    </h3>
                    <div class="space-y-3">
                        @foreach($footerProjects->take(3) as $project)
                            <a
                                href="{{ $project->youtube_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex gap-3 group"
                            >
                                <div class="relative w-20 h-14 shrink-0 rounded overflow-hidden bg-slate-700">
                                    <img
                                        src="{{ $project->thumbnail_url }}"
                                        alt="{{ $project->title ?? 'Video' }}"
                                        class="w-full h-full object-cover
                                               group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                    <div class="absolute inset-0 flex items-center justify-center
                                                bg-black/30 group-hover:bg-black/50 transition-colors">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-slate-300 group-hover:text-white
                                               transition-colors line-clamp-2 leading-snug">
                                        {{ $project->title ?? 'Watch Video' }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div></div>
            @endif

        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-white/10">
        <div class="container-base py-4 flex flex-col sm:flex-row items-center
                    justify-between gap-2 text-xs text-slate-400">
            <span>
                &copy; {{ date('Y') }} {{ $settings['site_name'] ?? config('app.name') }}.
                All rights reserved.
            </span>
        </div>
    </div>

</footer>