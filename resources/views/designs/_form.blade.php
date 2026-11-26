{{--
    Partial: designs/_form.blade.php
    Digunakan oleh: designs/create.blade.php dan designs/edit.blade.php
--}}
@php
    $isEdit     = isset($design);
    $workflow   = old('workflow_type', $isEdit ? $design->workflow_type : 'solidworks');
    $bomItems   = $isEdit ? $design->bomItems->map(fn($b) => [
                    'part_name' => $b->part_name,
                    'quantity'  => $b->quantity,
                    'spec_note' => $b->spec_note ?? '',
                  ])->values()->all()
                  : [['part_name' => '', 'quantity' => 1, 'spec_note' => '']];
@endphp

<div
    x-data="{
        workflow: @js($workflow),
        rows: @js($bomItems)
    }"
    class="space-y-8"
>
    {{-- ── Validation Errors Banner ── --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-800/80 bg-red-950/40 p-4 text-xs text-red-300">
            <div class="flex items-center gap-2 mb-2 font-bold text-red-400">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Mohon periksa dan perbaiki kesalahan berikut:</span>
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── Code ID & Title ── --}}
    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-400 font-mono">
                Code ID <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="code_id"
                value="{{ old('code_id', $isEdit ? $design->code_id : '') }}"
                required
                placeholder="contoh: CAD-001 / BLD-002"
                class="w-full rounded-xl border @error('code_id') border-red-700 bg-red-950/20 @else border-zinc-700 bg-[#121316] @enderror px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono"
            >
            <p class="mt-1.5 text-[11px] text-zinc-500">Nomor identifikasi unik suku cadang di sistem PDM</p>
        </div>

        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-400 font-mono">
                Judul Model <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="title"
                value="{{ old('title', $isEdit ? $design->title : '') }}"
                required
                placeholder="Nama desain atau model 3D"
                class="w-full rounded-xl border @error('title') border-red-700 bg-red-950/20 @else border-zinc-700 bg-[#121316] @enderror px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition"
            >
            <p class="mt-1.5 text-[11px] text-zinc-500">Nama resmi komponen perakitan atau model 3D</p>
        </div>
    </div>

    {{-- ── Workflow Pipeline Selector ── --}}
    <div>
        <label class="mb-2.5 block text-xs font-bold uppercase tracking-wider text-zinc-400 font-mono">
            Workflow Pipeline <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <label
                class="cursor-pointer rounded-xl border p-4 transition flex flex-col justify-between"
                :class="workflow === 'solidworks'
                    ? 'border-sw-blue bg-blue-950/20 ring-1 ring-sw-blue'
                    : 'border-zinc-800 bg-[#16181d] hover:border-zinc-700'"
            >
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold" :class="workflow === 'solidworks' ? 'text-sw-blue' : 'text-white'">
                        SolidWorks CAD
                    </span>
                    <input type="radio" name="workflow_type" value="solidworks" x-model="workflow" class="sr-only">
                    <span class="w-2.5 h-2.5 rounded-full" :class="workflow === 'solidworks' ? 'bg-sw-blue' : 'bg-zinc-700'"></span>
                </div>
                <p class="text-[11px] text-zinc-500 leading-relaxed">
                    Khusus part mekanikal parametrik. Dilengkapi input material paduan &amp; estimasi massa.
                </p>
            </label>

            <label
                class="cursor-pointer rounded-xl border p-4 transition flex flex-col justify-between"
                :class="workflow === 'blender'
                    ? 'border-bld-orange bg-orange-950/20 ring-1 ring-bld-orange'
                    : 'border-zinc-800 bg-[#16181d] hover:border-zinc-700'"
            >
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold" :class="workflow === 'blender' ? 'text-bld-orange' : 'text-white'">
                        Blender 3D (.blend)
                    </span>
                    <input type="radio" name="workflow_type" value="blender" x-model="workflow" class="sr-only">
                    <span class="w-2.5 h-2.5 rounded-full" :class="workflow === 'blender' ? 'bg-bld-orange' : 'bg-zinc-700'"></span>
                </div>
                <p class="text-[11px] text-zinc-500 leading-relaxed">
                    Aset 3D visual atau game-ready. Dilengkapi tracking kerapatan polygon &amp; GLB export.
                </p>
            </label>

            <label
                class="cursor-pointer rounded-xl border p-4 transition flex flex-col justify-between"
                :class="workflow === 'hybrid'
                    ? 'border-emerald-500 bg-emerald-950/20 ring-1 ring-emerald-500'
                    : 'border-zinc-800 bg-[#16181d] hover:border-zinc-700'"
            >
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold" :class="workflow === 'hybrid' ? 'text-emerald-400' : 'text-white'">
                        Hybrid Pipeline
                    </span>
                    <input type="radio" name="workflow_type" value="hybrid" x-model="workflow" class="sr-only">
                    <span class="w-2.5 h-2.5 rounded-full" :class="workflow === 'hybrid' ? 'bg-emerald-400' : 'bg-zinc-700'"></span>
                </div>
                <p class="text-[11px] text-zinc-500 leading-relaxed">
                    Penggabungan CAD SolidWorks ke rendering visual Blender (mengaktifkan semua parameter).
                </p>
            </label>
        </div>
    </div>

    {{-- ── SolidWorks Fields: Material & Massa ── --}}
    <div
        class="grid gap-6 sm:grid-cols-2 rounded-2xl border border-sw-blue/20 bg-blue-950/10 p-5"
        x-show="workflow === 'solidworks' || workflow === 'hybrid'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak
    >
        <div class="sm:col-span-2 flex items-center gap-2 pb-2 border-b border-sw-blue/20">
            <svg class="w-4 h-4 text-sw-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="text-xs font-bold text-sw-blue uppercase tracking-wider font-mono">SolidWorks CAD Parameters</p>
        </div>
        <div>
            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-zinc-300 font-mono">Material CAD</label>
            <input
                type="text"
                name="material"
                value="{{ old('material', $isEdit ? $design->material : '') }}"
                placeholder="contoh: AISI 304 Stainless Steel"
                class="w-full rounded-xl border border-zinc-700 bg-[#121316] px-4 py-2.5 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition"
            >
        </div>
        <div>
            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-zinc-300 font-mono">Massa Part (Gram)</label>
            <div class="relative">
                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="mass_grams"
                    value="{{ old('mass_grams', $isEdit ? $design->mass_grams : '') }}"
                    placeholder="0.00"
                    class="w-full rounded-xl border border-zinc-700 bg-[#121316] pl-4 pr-12 py-2.5 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono"
                >
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-xs text-zinc-500 font-mono">
                    grams
                </div>
            </div>
        </div>
    </div>

    {{-- ── Blender Field: Poly Count ── --}}
    <div
        class="rounded-2xl border border-bld-orange/20 bg-orange-950/10 p-5"
        x-show="workflow === 'blender' || workflow === 'hybrid'"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak
    >
        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-bld-orange/20">
            <svg class="w-4 h-4 text-bld-orange" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.5 8.2c.4 0 .7.1 1.1.2l3.4-3.4c-.6-.4-1.3-.7-2.1-.9l-2.4 4.1zm-4.6 2.3c.2-.5.5-.9.9-1.2L4.7 5.2c-.6.6-1.1 1.3-1.4 2.1l4.6 3.2zm-.9 3.5c0 .7.2 1.4.5 2l-4.2 2.3c-.2-.7-.3-1.5-.3-2.3 0-.7.1-1.3.2-2l3.8 0zm3.2 4.1c.5.3 1.1.5 1.8.5l.7 4.7c-.8 0-1.6-.2-2.3-.5l-.2-4.7zm6.8-4.1c0 2.8-2.2 5-5 5s-5-2.2-5-5 2.2-5 5-5 5 2.2 5 5zm-3 0c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2z"/>
            </svg>
            <p class="text-xs font-bold text-bld-orange uppercase tracking-wider font-mono">Blender 3D Parameters</p>
        </div>
        <div>
            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-zinc-300 font-mono">Polygon Count / Topology</label>
            <input
                type="text"
                name="poly_count"
                value="{{ old('poly_count', $isEdit ? $design->poly_count : '') }}"
                placeholder="contoh: 1.25M Triangles / 48.5K Quads"
                class="w-full rounded-xl border border-zinc-700 bg-[#121316] px-4 py-2.5 text-sm text-white placeholder-zinc-600 focus:border-bld-orange focus:outline-none focus:ring-1 focus:ring-orange-500 transition font-mono"
            >
        </div>
    </div>

    {{-- ── Bill of Materials (BOM) Editor ── --}}
    <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-6 shadow-xl">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Bill of Materials (BOM)</span>
                </h3>
                <p class="text-xs text-zinc-500 mt-0.5">Daftar komponen perakitan, suku cadang, dan toleransi</p>
            </div>
            <button
                type="button"
                @click="rows.push({ part_name: '', quantity: 1, spec_note: '' })"
                class="inline-flex items-center gap-1.5 rounded-xl border border-bld-orange/50 bg-orange-950/30 px-3.5 py-1.5 text-xs font-bold text-bld-orange hover:bg-orange-950/60 transition"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Komponen</span>
            </button>
        </div>

        <div class="mb-2 hidden grid-cols-[2fr_100px_2.5fr_44px] gap-2.5 text-xs uppercase tracking-wider text-zinc-500 sm:grid px-2 font-mono">
            <span>Nama Komponen</span>
            <span>Qty</span>
            <span>Catatan Spesifikasi</span>
            <span></span>
        </div>

        <div class="space-y-2">
            <template x-for="(row, index) in rows" :key="index">
                <div class="grid gap-2.5 sm:grid-cols-[2fr_100px_2.5fr_44px] items-center">
                    <input
                        :name="`part_names[${index}]`"
                        x-model="row.part_name"
                        placeholder="Nama komponen / part"
                        class="rounded-xl border border-zinc-700 bg-[#121316] px-3.5 py-2.5 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition"
                    >
                    <input
                        type="number"
                        min="1"
                        :name="`quantities[${index}]`"
                        x-model="row.quantity"
                        class="rounded-xl border border-zinc-700 bg-[#121316] px-3.5 py-2.5 text-sm text-white focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono text-center"
                    >
                    <input
                        :name="`spec_notes[${index}]`"
                        x-model="row.spec_note"
                        placeholder="Catatan / toleransi"
                        class="rounded-xl border border-zinc-700 bg-[#121316] px-3.5 py-2.5 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition"
                    >
                    <button
                        type="button"
                        @click="rows.splice(index, 1)"
                        title="Hapus baris ini"
                        class="h-10 w-full inline-flex items-center justify-center rounded-xl border border-red-900/60 bg-red-950/20 text-red-400 hover:bg-red-950/60 hover:text-red-300 transition"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <p x-show="rows.length === 0" class="py-6 text-center text-xs text-zinc-500 font-mono" x-cloak>
            Belum ada komponen BOM terdaftar. Klik tombol "+ Tambah Komponen" untuk menambahkan.
        </p>
    </div>

    {{-- ── File Upload Zones ── --}}
    <div class="grid gap-6 sm:grid-cols-2">
        <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-5">
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono flex items-center gap-1.5">
                <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Render Thumbnail</span>
            </label>
            <input
                type="file"
                name="render_image"
                accept="image/*"
                class="w-full text-xs text-zinc-400 file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-800 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-zinc-700 transition"
            >
            @if($isEdit && $design->render_image)
                <div class="mt-3 flex items-center gap-2 p-2 rounded-lg bg-zinc-900/60 border border-zinc-800">
                    <img src="{{ asset('storage/' . $design->render_image) }}"
                         alt="Current render"
                         class="h-9 w-9 rounded object-cover border border-zinc-700">
                    <span class="text-xs font-mono text-zinc-400 truncate">{{ basename($design->render_image) }}</span>
                </div>
            @endif
            <p class="mt-2 text-[11px] text-zinc-500">Mendukung file PNG, JPG, WebP (Maksimal 4 MB)</p>
        </div>

        <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-5">
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Berkas Model 3D (.glb)</span>
            </label>
            <input
                type="file"
                name="glb_file"
                accept=".glb,model/gltf-binary"
                class="w-full text-xs text-zinc-400 file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-800 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-zinc-700 transition"
            >
            @if($isEdit && $design->glb_file)
                <div class="mt-3 flex items-center gap-2 p-2 rounded-lg bg-emerald-950/20 border border-emerald-800/40 text-xs font-mono text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span class="truncate">{{ basename($design->glb_file) }}</span>
                </div>
            @endif
            <p class="mt-2 text-[11px] text-zinc-500">Format biner .glb untuk visualisasi web 360° (Maksimal 30 MB)</p>
        </div>

        <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-5">
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono">
                Berkas Model 3D (.stl)
            </label>
            <input
                type="file"
                name="stl_file"
                accept=".stl"
                class="w-full text-xs text-zinc-400 file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-800 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-zinc-700 transition"
            >
            @if($isEdit && $design->stl_file)
                <div class="mt-3 flex items-center gap-2 rounded-lg border border-zinc-800 bg-zinc-900/60 p-2 text-xs font-mono text-zinc-300">
                    <span class="rounded bg-zinc-800 px-2 py-1 text-[10px] font-bold text-zinc-300">STL</span>
                    <span class="truncate">{{ basename($design->stl_file) }}</span>
                </div>
            @endif
            <p class="mt-2 text-[11px] text-zinc-500">Model mesh untuk pencetakan 3D (Maksimal 30 MB)</p>
        </div>

        <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-5">
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono">
                Berkas Sumber Blender (.blend)
            </label>
            <input
                type="file"
                name="blend_file"
                accept=".blend"
                class="w-full text-xs text-zinc-400 file:mr-3 file:rounded-lg file:border-0 file:bg-zinc-800 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white hover:file:bg-zinc-700 transition"
            >
            @if($isEdit && $design->blend_file)
                <div class="mt-3 flex items-center gap-2 rounded-lg border border-orange-800/40 bg-orange-950/20 p-2 text-xs font-mono text-bld-orange">
                    <span class="rounded bg-orange-950/60 px-2 py-1 text-[10px] font-bold">BLEND</span>
                    <span class="truncate">{{ basename($design->blend_file) }}</span>
                </div>
            @endif
            <p class="mt-2 text-[11px] text-zinc-500">Berkas proyek sumber Blender (Maksimal 30 MB)</p>
        </div>
    </div>
</div>
