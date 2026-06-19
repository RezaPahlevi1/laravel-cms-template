@extends('layouts.app')

@section('title', 'Contact Us — ' . ($settings['site_name'] ?? config('app.name')))

@section('meta_description', 'Get in touch with us. We would love to hear from you.')

@section('content')

    {{-- Hero Banner --}}
    <div class="bg-surface-alt border-b border-border">
        <div class="container-base py-6">
            <h1 class="text-2xl lg:text-3xl font-bold text-primary-dark">
                Contact Us
            </h1>
            <nav class="mt-1.5 flex items-center gap-1.5 text-xs text-text-muted">
                <a href="/" class="hover:text-primary transition-colors">Home</a>
                <span class="opacity-50">/</span>
                <span>Contact Us</span>
            </nav>
        </div>
    </div>

    <div class="container-base py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Kolom kiri: Form --}}
            <div>
                <h2 class="text-lg font-semibold text-primary-dark mb-6">
                    Send Us a Message
                </h2>

                {{-- Success --}}
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Error --}}
                @if(session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-text-base mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2.5 rounded-lg border text-sm text-text-base
                                   bg-white transition-colors
                                   @error('name') border-red-400 focus:ring-red-400
                                   @else border-border focus:ring-primary @enderror
                                   focus:outline-none focus:ring-2 focus:border-transparent"
                            placeholder="Your full name"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-text-base mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2.5 rounded-lg border text-sm text-text-base
                                   bg-white transition-colors
                                   @error('email') border-red-400 focus:ring-red-400
                                   @else border-border focus:ring-primary @enderror
                                   focus:outline-none focus:ring-2 focus:border-transparent"
                            placeholder="your@email.com"
                        >
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium text-text-base mb-1">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="subject"
                            name="subject"
                            value="{{ old('subject') }}"
                            class="w-full px-4 py-2.5 rounded-lg border text-sm text-text-base
                                   bg-white transition-colors
                                   @error('subject') border-red-400 focus:ring-red-400
                                   @else border-border focus:ring-primary @enderror
                                   focus:outline-none focus:ring-2 focus:border-transparent"
                            placeholder="What is this about?"
                        >
                        @error('subject')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium text-text-base mb-1">
                            Message <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="message"
                            name="message"
                            rows="6"
                            class="w-full px-4 py-2.5 rounded-lg border text-sm text-text-base
                                   bg-white transition-colors resize-none
                                   @error('message') border-red-400 focus:ring-red-400
                                   @else border-border focus:ring-primary @enderror
                                   focus:outline-none focus:ring-2 focus:border-transparent"
                            placeholder="Write your message here..."
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full sm:w-auto px-8 py-2.5 bg-secondary hover:bg-secondary-light
                               text-white text-sm font-semibold rounded-lg transition-colors"
                    >
                        Send Message
                    </button>

                </form>
            </div>

            {{-- Kolom kanan: Info + Map --}}
            <div class="space-y-6">

                {{-- Info kontak --}}
                @if(!empty($settings['footer_contact_address']) ||
                    !empty($settings['footer_contact_phone']) ||
                    !empty($settings['footer_contact_email']))
                    <div>
                        <h2 class="text-lg font-semibold text-primary-dark mb-4">
                            Our Information
                        </h2>
                        <ul class="space-y-3">
                            @if(!empty($settings['footer_contact_address']))
                                <li class="flex gap-3 text-sm text-text-muted">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0 text-secondary" fill="none"
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
                                <li class="flex gap-3 text-sm text-text-muted">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0 text-secondary" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.948V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 8V5z"/>
                                    </svg>
                                    <a href="tel:{{ $settings['footer_contact_phone'] }}"
                                       class="hover:text-primary transition-colors">
                                        {{ $settings['footer_contact_phone'] }}
                                    </a>
                                </li>
                            @endif
                            @if(!empty($settings['footer_contact_email']))
                                <li class="flex gap-3 text-sm text-text-muted">
                                    <svg class="w-5 h-5 mt-0.5 shrink-0 text-secondary" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <a href="mailto:{{ $settings['footer_contact_email'] }}"
                                       class="hover:text-primary transition-colors">
                                        {{ $settings['footer_contact_email'] }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif

                {{-- Map embed --}}
                @if(!empty($settings['map_embed_url']))
                    <div>
                        <h2 class="text-lg font-semibold text-primary-dark mb-4">
                            Our Location
                        </h2>
                        <div class="rounded-lg overflow-hidden border border-border aspect-video">
                            <iframe
                                src="{{ $settings['map_embed_url'] }}"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection