@extends('layouts.app')

@section('title', '3D Design Library — Liveria Engineering Hub')

@section('content')

{{-- ═══════════ HERO BANNER ═══════════ --}}
<section class="eng-grid relative overflow-hidden border-b border-zinc-800/80 bg-[#14161c]">
    <div class="relative mx-auto max-w-7xl px-6 py-12 sm:py-16">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
            <div class="max-w-2xl">
                <div class="mb-3.5 inline-flex items-center gap-2 rounded-full border border-sw-blue/40 bg-sw-blue/10 px-3 py-1 text-xs text-sw-blue">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-sw-blue animate-pulse"></span>
                    <span>Engineering Asset Registry</span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white leading-tight">
                    3D Design &amp; <span class="text-sw-blue">CAD Library</span>
                </h1>

                <p class="mt-3 text-sm leading-relaxed text-zinc-400">
                    Katalog suku cadang mekanikal SolidWorks CAD dan model 3D Blender.
                    Lengkap dengan spesifikasi teknis material, massa part, kerapatan polygon, dan Bill of Materials (BOM).
                </p>
            </div>

            {{-- Quick Metric Pills --}}
            <div class="flex flex-wrap items-center gap-3">
                <div class="rounded-xl border border-zinc-800 bg-[#1a1d24] px-4 py-2.5">
                    <span class="block text-[10px] uppercase font-mono text-zinc-500">Total Assets</span>
                    <span class="text-xl font-bold text-white">{{ $designs->count() }}</span>
                </div>
                <div class="rounded-xl border border-sw-blue/30 bg-blue-950/20 px-4 py-2.5">
                    <span class="block text-[10px] uppercase font-mono text-sw-blue">SolidWorks</span>
                    <span class="text-xl font-bold text-sw-blue">{{ $designs->where('workflow_type', 'solidworks')->count() }}</span>
                </div>
                <div class="rounded-xl border border-bld-orange/30 bg-orange-950/20 px-4 py-2.5">
                    <span class="block text-[10px] uppercase font-mono text-bld-orange">Blender 3D</span>
                    <span class="text-xl font-bold text-bld-orange">{{ $designs->where('workflow_type', 'blender')->count() }}</span>
                </div>
                <div class="rounded-xl border border-emerald-700/30 bg-emerald-950/20 px-4 py-2.5">
                    <span class="block text-[10px] uppercase font-mono text-emerald-400">Hybrid</span>
                    <span class="text-xl font-bold text-emerald-400">{{ $designs->where('workflow_type', 'hybrid')->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ LIBRARY TABLE & CONTROLS ═══════════ --}}
<section id="library" class="mx-auto max-w-7xl px-6 py-10">

    {{-- Filter Bar & Quick Category Buttons --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        {{-- Category Tab Filter Buttons --}}
        <div class="flex items-center gap-1.5 p-1 rounded-xl bg-[#16181d] border border-zinc-800 text-xs overflow-x-auto">
            <button type="button"
                    class="filter-pill active px-3.5 py-1.5 rounded-lg font-semibold transition"
                    data-type="">
                Semua Model
            </button>
            <button type="button"
                    class="filter-pill px-3.5 py-1.5 rounded-lg font-semibold text-zinc-400 hover:text-white transition"
                    data-type="solidworks">
                SolidWorks CAD
            </button>
            <button type="button"
                    class="filter-pill px-3.5 py-1.5 rounded-lg font-semibold text-zinc-400 hover:text-white transition"
                    data-type="blender">
                Blender 3D
            </button>
            <button type="button"
                    class="filter-pill px-3.5 py-1.5 rounded-lg font-semibold text-zinc-400 hover:text-white transition"
                    data-type="hybrid">
                Hybrid
            </button>
        </div>

        {{-- Dropdown Filter & Actions --}}
        <div class="flex items-center gap-3">
            <div class="relative">
                <select id="category-filter"
                        class="rounded-xl border border-zinc-700 bg-[#16181d] pl-3 pr-8 py-2 text-xs text-white focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition appearance-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <option value="solidworks">SolidWorks CAD</option>
                    <option value="blender">Blender 3D</option>
                    <option value="hybrid">Hybrid</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-zinc-400">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            @auth
                <a href="{{ route('designs.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-sw-blue px-4 py-2 text-xs font-bold text-white hover:bg-blue-500 transition shadow-md shadow-blue-900/30">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Upload Part</span>
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-sw-blue px-4 py-2 text-xs font-bold text-white transition">
                    <span>Login untuk Upload</span>
                </a>
            @endauth
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div id="api-loading" class="hidden text-center py-10 text-sm text-zinc-400 items-center justify-center gap-2">
        <svg class="w-5 h-5 animate-spin text-sw-blue inline" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Memuat data dari REST API...</span>
    </div>

    {{-- Table Container --}}
    <div class="overflow-x-auto rounded-2xl border border-zinc-800 bg-[#16181d] shadow-2xl">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-zinc-800 bg-[#121317]">
                <tr class="text-xs uppercase tracking-wider text-zinc-500 font-mono">
                    <th class="px-5 py-3.5">Kode ID</th>
                    <th class="px-5 py-3.5">Nama Model</th>
                    <th class="px-5 py-3.5">Software</th>
                    <th class="px-5 py-3.5">Spesifikasi</th>
                    <th class="px-5 py-3.5">BOM Parts</th>
                    <th class="px-5 py-3.5 text-center">File 3D</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="design-table-body" class="divide-y divide-zinc-800/60">
                @forelse($designs as $item)
                    @include('designs._row', ['item' => $item])
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-zinc-500">
                            <div class="flex justify-center mb-3">
                                <svg class="w-14 h-14 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <p class="font-bold text-white text-base">Belum Ada Desain 3D Terdaftar</p>
                            <p class="text-xs text-zinc-500 mt-1">
                                @auth
                                    <a href="{{ route('designs.create') }}" class="text-sw-blue hover:underline">Unggah desain pertama sekarang →</a>
                                @else
                                    <a href="{{ route('login') }}" class="text-sw-blue hover:underline">Login untuk mengunggah data CAD &amp; Blender</a>
                                @endauth
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Empty State for API Filter --}}
    <div id="api-empty" class="hidden py-16 text-center text-zinc-500">
        <div class="flex justify-center mb-3">
            <svg class="w-12 h-12 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <p class="font-semibold text-zinc-300">Tidak ada model untuk kategori yang dipilih</p>
        <p class="text-xs text-zinc-600 mt-1">Silakan pilih kategori lain atau reset filter</p>
    </div>

</section>

@push('scripts')
<script>
(function() {
    const filterSelect = document.getElementById('category-filter');
    const filterPills  = document.querySelectorAll('.filter-pill');
    const tbody        = document.getElementById('design-table-body');
    const loading      = document.getElementById('api-loading');
    const empty        = document.getElementById('api-empty');
    const isAuth       = {{ auth()->check() ? 'true' : 'false' }};
    const csrfToken    = document.querySelector('meta[name="csrf-token"]').content;

    function setActivePill(type) {
        filterPills.forEach(pill => {
            if (pill.dataset.type === type) {
                pill.classList.add('bg-sw-blue', 'text-white', 'shadow-sm');
                pill.classList.remove('text-zinc-400');
            } else {
                pill.classList.remove('bg-sw-blue', 'text-white', 'shadow-sm');
                pill.classList.add('text-zinc-400');
            }
        });
        filterSelect.value = type;
    }

    // Set initial pill style
    setActivePill('');

    function typeBadge(type) {
        const map = {
            solidworks: `<span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-blue-950/60 text-sw-blue border border-sw-blue/30 text-xs uppercase font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-sw-blue"></span>SolidWorks
            </span>`,
            blender: `<span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-orange-950/60 text-bld-orange border border-orange-500/30 text-xs uppercase font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-bld-orange"></span>Blender
            </span>`,
            hybrid: `<span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-emerald-950/60 text-emerald-400 border border-emerald-700/30 text-xs uppercase font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Hybrid
            </span>`,
        };
        return map[type] || `<span class="text-zinc-400 uppercase">${type}</span>`;
    }

    function specText(item) {
        if (item.material && item.mass_grams != null) return `${item.material} &bull; ${item.mass_grams} g`;
        if (item.material) return item.material;
        if (item.poly_count) return item.poly_count;
        return '<span class="text-zinc-600">—</span>';
    }

    function adminActions(id) {
        if (!isAuth) return '';
        return `
            <a href="/designs/${id}/edit" class="inline-flex items-center gap-1 text-amber-400 hover:text-amber-300 transition mr-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit</span>
            </a>
            <form action="/designs/${id}" method="POST" class="inline ml-1" onsubmit="return confirm('Hapus data ini? Tindakan tidak dapat diurungkan.')">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="inline-flex items-center gap-1 text-red-400 hover:text-red-300 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Hapus</span>
                </button>
            </form>`;
    }

    function modelFileBadges(item) {
        const formats = [
            { label: 'GLB', url: item.glb_url },
            { label: 'STL', url: item.stl_url },
            { label: 'BLEND', url: item.blend_url },
        ].filter(format => format.url);

        if (!formats.length) return '<span class="text-zinc-700">—</span>';

        return `<span class="inline-flex items-center justify-center gap-1">
            ${formats.map(format => `<span class="rounded bg-zinc-800 px-1.5 py-0.5 text-[9px] font-bold text-emerald-400">${format.label}</span>`).join('')}
        </span>`;
    }

    function renderRows(designs) {
        if (!designs.length) {
            tbody.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');
        tbody.innerHTML = designs.map(item => `
            <tr class="hover:bg-zinc-800/40 transition group">
                <td class="px-5 py-4 font-mono text-xs text-sw-blue font-bold">${item.code_id}</td>
                <td class="px-5 py-4 font-semibold text-white">
                    <a href="/designs/${item.id}" class="hover:text-sw-blue transition">${item.title}</a>
                </td>
                <td class="px-5 py-4">${typeBadge(item.workflow_type)}</td>
                <td class="px-5 py-4 font-mono text-xs text-zinc-400">${specText(item)}</td>
                <td class="px-5 py-4 font-mono text-xs">
                    <span class="rounded bg-zinc-800 px-2 py-0.5 text-zinc-300">${item.bom_items ? item.bom_items.length : 0} parts</span>
                </td>
                <td class="px-5 py-4 text-center">${modelFileBadges(item)}</td>
                <td class="px-5 py-4 text-right whitespace-nowrap">
                    <a href="/designs/${item.id}" class="inline-flex items-center gap-1 text-emerald-400 hover:text-emerald-300 transition mr-3">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>View</span>
                    </a>
                    ${adminActions(item.id)}
                </td>
            </tr>
        `).join('');
    }

    async function applyFilter(type) {
        setActivePill(type);
        loading.classList.remove('hidden');
        empty.classList.add('hidden');
        tbody.innerHTML = '';
        try {
            const url = type ? `/api/designs?type=${encodeURIComponent(type)}` : '/api/designs';
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('API error');
            const designs = await res.json();
            renderRows(designs);
        } catch (e) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-5 py-8 text-center text-red-400 text-sm">Gagal memuat data dari REST API. Silakan coba kembali.</td></tr>`;
        } finally {
            loading.classList.add('hidden');
        }
    }

    filterSelect.addEventListener('change', () => applyFilter(filterSelect.value));
    filterPills.forEach(pill => pill.addEventListener('click', () => applyFilter(pill.dataset.type)));
})();
</script>
@endpush

@endsection
