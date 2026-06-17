@extends('layouts.app')

@section('title', 'Pencarian: ' . $query . ' — ' . ($settings['site_name'] ?? config('app.name')))

@section('content')

    <div class="bg-surface-alt border-b border-border">
        <div class="container-base py-6">
            <h1 class="text-xl font-bold text-primary-dark">
                Hasil Pencarian
            </h1>
            @if($query)
                <p class="mt-1 text-sm text-text-muted">
                    {{ $results->count() }} hasil untuk
                    <span class="font-medium text-text-base">"{{ $query }}"</span>
                </p>
            @endif
        </div>
    </div>

    <div class="container-base py-8">

        @if(strlen($query) < 2)
            <p class="text-text-muted text-sm">Masukkan minimal 2 karakter untuk mencari.</p>

        @elseif($results->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-border mx-auto mb-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-text-muted">
                    Tidak ada hasil untuk <span class="font-medium">"{{ $query }}"</span>.
                </p>
                <p class="mt-1 text-sm text-text-light">Coba kata kunci yang berbeda.</p>
            </div>

        @else
            <div class="divide-y divide-border">
                @foreach($results as $result)
                    <a href="{{ $result['url'] }}"
                       class="flex items-start gap-4 py-4 group hover:bg-surface-alt
                              -mx-4 px-4 rounded-lg transition-colors">

                        <span class="shrink-0 mt-0.5 px-2 py-0.5 text-xs font-medium rounded
                                     {{ $result['type'] === 'page'
                                         ? 'bg-primary/10 text-primary'
                                         : 'bg-secondary/10 text-secondary' }}">
                            {{ $result['type'] === 'page' ? 'Halaman' : 'Blog' }}
                        </span>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-primary-dark
                                      group-hover:text-primary transition-colors">
                                {{ $result['title'] }}
                            </p>
                            @if(!empty($result['excerpt']))
                                <p class="mt-1 text-xs text-text-muted leading-relaxed line-clamp-2">
                                    {{ $result['excerpt'] }}
                                </p>
                            @endif
                            <p class="mt-1 text-xs text-text-light">
                                {{ $result['url'] }}
                            </p>
                        </div>

                        <svg class="shrink-0 w-4 h-4 text-border group-hover:text-primary
                                    transition-colors mt-0.5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>

                    </a>
                @endforeach
            </div>
        @endif

    </div>

@endsection