@extends('layouts.app')

@section('title', 'Tambah 3D Part — Liveria Engineering Hub')

@section('content')
<div class="mx-auto max-w-4xl px-6 py-10">

    {{-- Breadcrumb & Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 text-xs text-zinc-500 mb-2 font-mono">
            <a href="{{ route('home') }}" class="hover:text-zinc-300 transition">Home</a>
            <span>›</span>
            <a href="{{ route('designs.index') }}" class="hover:text-zinc-300 transition">Library</a>
            <span>›</span>
            <span class="text-sw-blue font-bold">New Part Check-In</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Upload 3D Part Baru</h1>
        <p class="mt-1 text-sm text-zinc-400">Daftarkan spesifikasi desain CAD atau model 3D ke dalam sistem PDM</p>
    </div>

    {{-- Form Container Card --}}
    <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-6 sm:p-8 shadow-2xl">
        <form action="{{ route('designs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('designs._form')

            {{-- Form Action Footer --}}
            <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-zinc-800/80 pt-6">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-sw-blue px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/40 hover:bg-blue-500 active:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    <span>Simpan &amp; Daftarkan Part</span>
                </button>
                <a href="{{ route('designs.index') }}"
                   class="rounded-xl border border-zinc-700 bg-zinc-800/40 px-5 py-3 text-sm font-semibold text-zinc-300 hover:border-zinc-500 hover:text-white transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
