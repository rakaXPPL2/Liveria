{{-- Partial: designs/_row.blade.php --}}
{{-- Usage: @include('designs._row', ['item' => $item]) --}}
<tr class="hover:bg-zinc-800/40 transition group">
    <td class="px-5 py-4 font-mono text-xs font-bold text-sw-blue">{{ $item->code_id }}</td>
    <td class="px-5 py-4 font-semibold text-white">
        <a href="{{ route('designs.show', $item) }}" class="hover:text-sw-blue transition">{{ $item->title }}</a>
    </td>
    <td class="px-5 py-4">
        @if($item->workflow_type === 'solidworks')
            <span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-blue-950/60 text-sw-blue border border-sw-blue/30 text-xs uppercase font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-sw-blue"></span>
                <span>SolidWorks</span>
            </span>
        @elseif($item->workflow_type === 'blender')
            <span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-orange-950/60 text-bld-orange border border-orange-500/30 text-xs uppercase font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-bld-orange"></span>
                <span>Blender</span>
            </span>
        @else
            <span class="inline-flex items-center gap-1.5 rounded px-2.5 py-0.5 bg-emerald-950/60 text-emerald-400 border border-emerald-700/30 text-xs uppercase font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span>Hybrid</span>
            </span>
        @endif
    </td>
    <td class="px-5 py-4 font-mono text-xs text-zinc-400">
        @if($item->material && $item->mass_grams)
            {{ $item->material }} &bull; {{ number_format($item->mass_grams, 2) }} g
        @elseif($item->material)
            {{ $item->material }}
        @elseif($item->poly_count)
            {{ $item->poly_count }}
        @else
            <span class="text-zinc-600">—</span>
        @endif
    </td>
    <td class="px-5 py-4 font-mono text-xs">
        <span class="rounded bg-zinc-800 px-2 py-0.5 text-zinc-300">
            {{ $item->bom_items_count ?? $item->bomItems->count() }} parts
        </span>
    </td>
    <td class="px-5 py-4 text-center text-sm">
        @if($item->glb_file || $item->stl_file || $item->blend_file)
            <span class="inline-flex items-center justify-center gap-1">
                @if($item->glb_file)
                    <span class="rounded bg-zinc-800 px-1.5 py-0.5 text-[9px] font-bold text-emerald-400">GLB</span>
                @endif
                @if($item->stl_file)
                    <span class="rounded bg-zinc-800 px-1.5 py-0.5 text-[9px] font-bold text-zinc-300">STL</span>
                @endif
                @if($item->blend_file)
                    <span class="rounded bg-orange-950/60 px-1.5 py-0.5 text-[9px] font-bold text-bld-orange">BLEND</span>
                @endif
            </span>
        @else
            <span class="text-zinc-700">—</span>
        @endif
    </td>
    <td class="px-5 py-4 text-right whitespace-nowrap">
        <a href="{{ route('designs.show', $item) }}" class="inline-flex items-center gap-1 text-emerald-400 hover:text-emerald-300 transition mr-3">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <span>View</span>
        </a>
        @auth
            <a href="{{ route('designs.edit', $item) }}" class="inline-flex items-center gap-1 text-amber-400 hover:text-amber-300 transition mr-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit</span>
            </a>
            <form action="{{ route('designs.destroy', $item) }}" method="POST" class="inline ml-1"
                  onsubmit="return confirm('Hapus data ini? Tindakan tidak dapat diurungkan.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1 text-red-400 hover:text-red-300 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Hapus</span>
                </button>
            </form>
        @endauth
    </td>
</tr>