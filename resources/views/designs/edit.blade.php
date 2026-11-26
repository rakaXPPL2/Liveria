@extends('layouts.app')

@section('title', 'Edit — ' . $design->title . ' — Liveria Engineering Hub')

@section('content')
<div class="mx-auto max-w-4xl px-6 py-10">

    {{-- Breadcrumb & Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 text-xs text-zinc-500 mb-2 font-mono">
            <a href="{{ route('home') }}" class="hover:text-zinc-300 transition">Home</a>
            <span>›</span>
            <a href="{{ route('designs.index') }}" class="hover:text-zinc-300 transition">Library</a>
            <span>›</span>
            <a href="{{ route('designs.show', $design) }}" class="text-sw-blue hover:underline">{{ $design->code_id }}</a>
            <span>›</span>
            <span class="text-zinc-400 font-bold">Edit Part</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Edit: {{ $design->title }}</h1>
        <p class="mt-1 text-sm text-zinc-400 font-mono">Modifikasi parameter teknis dan pembaruan file 3D</p>
    </div>

    {{-- Form Container Card --}}
    <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-6 sm:p-8 shadow-2xl">
        <form id="edit-form" action="{{ route('designs.update', $design) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('designs._form', ['design' => $design])

            {{-- Form Action Footer --}}
            <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-zinc-800/80 pt-6">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-sw-blue px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/40 hover:bg-blue-500 active:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>Perbarui Desain</span>
                    </button>
                    <a href="{{ route('designs.show', $design) }}"
                       class="rounded-xl border border-zinc-700 bg-zinc-800/40 px-5 py-3 text-sm font-semibold text-zinc-300 hover:border-zinc-500 hover:text-white transition">
                        Batal
                    </a>
                </div>

                <button type="submit" form="delete-design-form"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-red-900/80 bg-red-950/30 px-4 py-3 text-xs font-semibold text-red-400 hover:bg-red-950/60 hover:text-red-300 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Hapus Desain</span>
                </button>
            </div>
        </form>

        <form id="delete-design-form" action="{{ route('designs.destroy', $design) }}" method="POST" class="hidden"
              onsubmit="return confirm('Hapus desain ini secara permanen? Seluruh berkas file fisik dan komponen BOM akan ikut dihapus.')">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
