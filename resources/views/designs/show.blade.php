@extends('layouts.app')

@section('title', $design->title . ' — Liveria Engineering Hub')

@section('content')
<div class="mx-auto max-w-7xl px-6 py-10" x-data="{
    copied: false,
    autoRotate: true,
    copyCode(code) {
        navigator.clipboard.writeText(code);
        this.copied = true;
        setTimeout(() => this.copied = false, 2000);
    }
}">

    {{-- ── Breadcrumb & Top Bar ── --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 text-xs text-zinc-500 mb-3 font-mono">
            <a href="{{ route('home') }}" class="hover:text-zinc-300 transition">Home</a>
            <span>›</span>
            <a href="{{ route('designs.index') }}" class="hover:text-zinc-300 transition">Library</a>
            <span>›</span>
            <span class="text-sw-blue font-bold">{{ $design->code_id }}</span>
        </div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    @if($design->workflow_type === 'solidworks')
                        <span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-blue-950/60 text-sw-blue border border-sw-blue/30 text-xs uppercase font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-sw-blue"></span>
                            <span>SolidWorks CAD</span>
                        </span>
                    @elseif($design->workflow_type === 'blender')
                        <span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-orange-950/60 text-bld-orange border border-orange-500/30 text-xs uppercase font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-bld-orange"></span>
                            <span>Blender 3D</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-emerald-950/60 text-emerald-400 border border-emerald-700/30 text-xs uppercase font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span>Hybrid Pipeline</span>
                        </span>
                    @endif

                    <button type="button"
                            @click="copyCode('{{ $design->code_id }}')"
                            class="inline-flex items-center gap-1 text-[11px] font-mono text-zinc-500 hover:text-white transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                        <span x-text="copied ? 'Disalin!' : 'Salin Kode'"></span>
                    </button>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white">{{ $design->title }}</h1>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('designs.edit', $design) }}"
                       class="inline-flex items-center gap-1.5 rounded-xl border border-amber-800/80 bg-amber-950/30 px-4 py-2 text-xs font-semibold text-amber-400 hover:bg-amber-950/60 transition shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit Part</span>
                    </a>
                    <form action="{{ route('designs.destroy', $design) }}" method="POST"
                          onsubmit="return confirm('Hapus desain ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-red-900 bg-red-950/30 px-4 py-2 text-xs font-semibold text-red-400 hover:bg-red-950/60 transition shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </form>
                @endauth
                <a href="{{ route('designs.index') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-zinc-700 bg-zinc-800/40 px-4 py-2 text-xs text-zinc-300 hover:text-white hover:border-zinc-500 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ── Main Grid: 3D Viewport + Technical Specs ── --}}
    <div class="grid gap-8 lg:grid-cols-12">

        {{-- 3D Interactive Canvas (8 Cols) --}}
        <section class="lg:col-span-8 overflow-hidden rounded-2xl border border-zinc-800 bg-[#16181d] shadow-2xl flex flex-col justify-between">
            @if($design->glb_file)
                {{-- Viewport Header Toolbar --}}
                <div class="border-b border-zinc-800 bg-[#121317] px-4 py-2.5 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded bg-emerald-950/80 px-2 py-0.5 text-[10px] font-bold text-emerald-400 border border-emerald-800/40">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>3D LIVE GLB</span>
                        </span>
                        <span class="text-zinc-600 font-mono">|</span>
                        <span class="text-zinc-400 font-mono text-[11px]">{{ basename($design->glb_file) }}</span>
                    </div>

                    <div class="flex items-center gap-2 text-zinc-400">
                        <button type="button"
                                @click="autoRotate = !autoRotate"
                                class="inline-flex items-center gap-1 rounded bg-zinc-800/80 px-2 py-1 text-[10px] text-zinc-300 hover:text-white transition">
                            <svg class="w-3 h-3 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span x-text="autoRotate ? 'Rotasi: Aktif' : 'Rotasi: Jeda'"></span>
                        </button>
                    </div>
                </div>

                {{-- The 3D model-viewer --}}
                <div class="relative bg-gradient-to-b from-[#0e1014] to-[#16181d]">
                    <model-viewer
                        id="main-viewer"
                        src="{{ asset('storage/' . $design->glb_file) }}"
                        camera-controls
                        :auto-rotate="autoRotate"
                        ar
                        shadow-intensity="1.5"
                        shadow-softness="0.8"
                        environment-image="neutral"
                        exposure="0.95"
                        style="width: 100%; height: 500px; background: transparent;"
                    ></model-viewer>
                </div>

                {{-- Viewport Footer Instructions --}}
                <div class="border-t border-zinc-800 bg-[#121317] px-4 py-2.5 flex flex-wrap items-center justify-between gap-3 text-xs text-zinc-500 font-mono">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1 text-zinc-400">
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                            <span>Klik &amp; Drag untuk rotasi</span>
                        </span>
                        <span>·</span>
                        <span>Scroll untuk zoom</span>
                        <span>·</span>
                        <span>Pinch pada layar sentuh</span>
                    </div>
                    <span class="text-[10px] text-zinc-600">WebGL 2.0 Realtime</span>
                </div>

            @elseif($design->render_image)
                <div class="border-b border-zinc-800 bg-[#121317] px-4 py-2.5 flex items-center justify-between text-xs">
                    <span class="inline-flex items-center gap-1.5 rounded bg-zinc-800 px-2 py-0.5 text-[10px] font-bold text-zinc-300">
                        <svg class="w-3 h-3 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Render Image Preview</span>
                    </span>
                    <span class="text-zinc-500 font-mono text-[10px]">{{ basename($design->render_image) }}</span>
                </div>
                <div class="flex items-center justify-center p-6 bg-[#0e1014] min-h-[460px]">
                    <img src="{{ asset('storage/' . $design->render_image) }}"
                         alt="{{ $design->title }}"
                         class="max-h-[460px] w-full object-contain rounded-lg">
                </div>
            @elseif($design->stl_file || $design->blend_file)
                <div class="flex min-h-[460px] flex-col items-center justify-center p-12 text-center">
                    <svg class="mb-4 h-16 w-16 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-base font-bold text-zinc-300">Berkas Model 3D Tersedia</p>
                    <p class="mt-1 max-w-sm text-xs text-zinc-500">
                        STL dan BLEND tersedia untuk diunduh. Pratinjau interaktif di halaman ini memerlukan berkas GLB.
                    </p>
                </div>
            @else
                <div class="flex min-h-[460px] flex-col items-center justify-center p-12 text-center text-zinc-600">
                    <svg class="w-16 h-16 text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-base font-bold text-zinc-400">Belum Ada Model 3D Atau Render</p>
                    <p class="text-xs text-zinc-600 mt-1 max-w-sm">
                        Unggah berkas model 3D (.glb, .stl, .blend) atau gambar render resolusi tinggi.
                    </p>
                    @auth
                        <a href="{{ route('designs.edit', $design) }}"
                           class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-sw-blue px-4 py-2 text-xs font-bold text-white hover:bg-blue-500 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            <span>Unggah File Sekarang</span>
                        </a>
                    @endauth
                </div>
            @endif
        </section>

        {{-- Technical Specs Sidebar (4 Cols) --}}
        <aside class="lg:col-span-4 space-y-6">

            {{-- Specifications Card --}}
            <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-6 shadow-xl">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-zinc-800">
                    <svg class="w-4 h-4 text-sw-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                    </svg>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Spesifikasi Teknik</h2>
                </div>

                <dl class="space-y-3.5 text-xs">
                    <div class="flex items-center justify-between">
                        <dt class="text-zinc-500 font-mono">Part Code ID</dt>
                        <dd class="font-mono font-bold text-sw-blue">{{ $design->code_id }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-zinc-500 font-mono">Workflow Pipeline</dt>
                        <dd class="font-semibold uppercase text-white">{{ $design->workflow_type }}</dd>
                    </div>

                    @if($design->material)
                        <div class="flex items-center justify-between pt-2 border-t border-zinc-800/80">
                            <dt class="text-zinc-500 font-mono">Material CAD</dt>
                            <dd class="text-zinc-200 font-medium text-right">{{ $design->material }}</dd>
                        </div>
                    @endif

                    @if($design->mass_grams)
                        <div class="flex items-center justify-between">
                            <dt class="text-zinc-500 font-mono">Massa Aktual</dt>
                            <dd class="font-mono text-zinc-200 font-bold">{{ number_format($design->mass_grams, 2) }} gram</dd>
                        </div>
                    @endif

                    @if($design->poly_count)
                        <div class="flex items-center justify-between pt-2 border-t border-zinc-800/80">
                            <dt class="text-zinc-500 font-mono">Polygon Count</dt>
                            <dd class="font-mono text-bld-orange font-bold">{{ $design->poly_count }}</dd>
                        </div>
                    @endif

                    <div class="flex items-center justify-between pt-2 border-t border-zinc-800/80">
                        <dt class="text-zinc-500 font-mono">Komponen BOM</dt>
                        <dd class="font-mono font-bold text-white">{{ $design->bomItems->count() }} items</dd>
                    </div>

                    <div class="flex items-center justify-between">
                        <dt class="text-zinc-500 font-mono">Terdaftar Pada</dt>
                        <dd class="text-zinc-400 font-mono">{{ $design->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Download Assets Card --}}
            @if($design->glb_file || $design->stl_file || $design->blend_file || $design->render_image)
                <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-6 shadow-xl">
                    <div class="flex items-center gap-2 mb-4 pb-3 border-b border-zinc-800">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-zinc-400">Berkas Unduhan</h2>
                    </div>

                    <div class="space-y-3">
                        @if($design->glb_file)
                            <a href="{{ asset('storage/' . $design->glb_file) }}"
                               download
                               class="group flex items-center justify-between p-3 rounded-xl border border-emerald-800/40 bg-emerald-950/20 hover:bg-emerald-950/40 transition">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-emerald-900/40 flex items-center justify-center text-emerald-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-white">GLB 3D Model</p>
                                        <p class="text-[10px] font-mono text-zinc-500 truncate max-w-[150px]">{{ basename($design->glb_file) }}</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-emerald-400 group-hover:translate-y-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                        @endif

                        @if($design->stl_file)
                            <a href="{{ asset('storage/' . $design->stl_file) }}"
                               download
                               class="group flex items-center justify-between rounded-xl border border-zinc-700/60 bg-zinc-800/20 p-3 transition hover:bg-zinc-800/40">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-800 text-zinc-300">
                                        <span class="text-[10px] font-bold">STL</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-white">STL Model</p>
                                        <p class="max-w-[150px] truncate text-[10px] font-mono text-zinc-500">{{ basename($design->stl_file) }}</p>
                                    </div>
                                </div>
                                <svg class="h-4 w-4 text-zinc-400 transition group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                        @endif

                        @if($design->blend_file)
                            <a href="{{ asset('storage/' . $design->blend_file) }}"
                               download
                               class="group flex items-center justify-between rounded-xl border border-orange-800/40 bg-orange-950/20 p-3 transition hover:bg-orange-950/40">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-900/40 text-bld-orange">
                                        <span class="text-[9px] font-bold">BLEND</span>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-white">Blender Source</p>
                                        <p class="max-w-[150px] truncate text-[10px] font-mono text-zinc-500">{{ basename($design->blend_file) }}</p>
                                    </div>
                                </div>
                                <svg class="h-4 w-4 text-bld-orange transition group-hover:translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                        @endif

                        @if($design->render_image)
                            <a href="{{ asset('storage/' . $design->render_image) }}"
                               download
                               class="group flex items-center justify-between p-3 rounded-xl border border-zinc-700/60 bg-zinc-800/20 hover:bg-zinc-800/40 transition">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-zinc-800 flex items-center justify-center text-zinc-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-white">Render Image</p>
                                        <p class="text-[10px] font-mono text-zinc-500 truncate max-w-[150px]">{{ basename($design->render_image) }}</p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-zinc-400 group-hover:translate-y-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        </aside>
    </div>

    {{-- ── Bill of Materials (BOM) Section ── --}}
    <section class="mt-10 overflow-hidden rounded-2xl border border-zinc-800 bg-[#16181d] shadow-2xl">
        <div class="flex items-center justify-between border-b border-zinc-800 px-6 py-4">
            <div>
                <h2 class="text-sm font-bold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Bill of Materials (BOM)</span>
                </h2>
                <p class="text-xs text-zinc-500 mt-0.5">Daftar komponen perakitan teknis &amp; toleransi</p>
            </div>
            @auth
                <a href="{{ route('designs.edit', $design) }}"
                   class="inline-flex items-center gap-1 text-xs text-sw-blue hover:text-blue-400 font-semibold transition">
                    <span>Edit Komponen BOM</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endauth
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#121317] text-xs uppercase tracking-wider text-zinc-500 font-mono">
                    <tr>
                        <th class="px-6 py-3.5 w-16">#</th>
                        <th class="px-6 py-3.5">Nama Komponen / Suku Cadang</th>
                        <th class="px-6 py-3.5 w-28">Kuantitas</th>
                        <th class="px-6 py-3.5">Catatan Spesifikasi &amp; Toleransi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/60">
                    @forelse($design->bomItems as $index => $item)
                        <tr class="hover:bg-zinc-800/30 transition">
                            <td class="px-6 py-4 font-mono text-xs text-zinc-600">{{ sprintf('%02d', $index + 1) }}</td>
                            <td class="px-6 py-4 font-semibold text-white">{{ $item->part_name }}</td>
                            <td class="px-6 py-4 font-mono text-sm">
                                <span class="rounded bg-zinc-800/80 px-2.5 py-1 text-zinc-200 border border-zinc-700/80 font-bold">
                                    {{ $item->quantity }}x
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-zinc-400 font-mono">
                                {{ $item->spec_note ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-500">
                                <p class="text-sm">Belum ada komponen BOM terdaftar untuk desain ini.</p>
                                @auth
                                    <a href="{{ route('designs.edit', $design) }}"
                                       class="mt-2 inline-flex items-center gap-1 text-xs text-sw-blue hover:underline">
                                        + Tambahkan item BOM sekarang
                                    </a>
                                @endauth
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>
@endsection
