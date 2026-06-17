@extends('layouts.app')

@section('title', '404 — Halaman Tidak Ditemukan')

@section('content')

    <div class="container-base py-20 text-center">

        <p class="text-7xl font-bold text-border">404</p>

        <h1 class="mt-4 text-xl font-bold text-primary-dark">
            Halaman tidak ditemukan
        </h1>

        <p class="mt-2 text-sm text-text-muted max-w-sm mx-auto">
            Halaman yang Anda cari tidak ada atau telah dipindahkan.
        </p>

        <div class="mt-8 flex items-center justify-center gap-3">
            <a href="/"
               class="px-5 py-2 bg-primary text-white text-sm font-semibold
                      rounded-lg hover:bg-primary-light transition-colors">
                Kembali ke Home
            </a>
            <a href="javascript:history.back()"
               class="px-5 py-2 bg-surface-alt text-text-base text-sm font-semibold
                      rounded-lg border border-border hover:bg-border transition-colors">
                Kembali
            </a>
        </div>

    </div>

@endsection