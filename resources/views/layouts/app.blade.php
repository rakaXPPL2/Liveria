<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Liveria 3D Engineering Hub')</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'cad-bg':     '#0d0f12',
                        'cad-panel':  '#16181d',
                        'cad-card':   '#1c1f26',
                        'cad-line':   '#282c35',
                        'sw-blue':    '#3B82F6',
                        'bld-orange': '#F97316',
                        'bld-gold':   '#FBBF24',
                    },
                    fontFamily: {
                        mono: ['"JetBrains Mono"', '"Fira Code"', 'monospace'],
                    },
                }
            }
        }
    </script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Google model-viewer CDN --}}
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>

    {{-- Google Fonts: JetBrains Mono --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'JetBrains Mono', monospace; }

        .eng-grid {
            background-image:
                linear-gradient(rgba(59,130,246,.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59,130,246,.05) 1px, transparent 1px),
                linear-gradient(rgba(249,115,22,.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(249,115,22,.02) 1px, transparent 1px);
            background-size: 80px 80px, 80px 80px, 16px 16px, 16px 16px;
        }

        .mesh-bg {
            background:
                radial-gradient(circle at 10% 15%, rgba(59,130,246,0.08) 0%, transparent 40%),
                radial-gradient(circle at 90% 20%, rgba(249,115,22,0.07) 0%, transparent 45%),
                #0d0f12;
        }

        .glow-blue {
            box-shadow: 0 0 25px rgba(59,130,246,0.2), 0 0 70px rgba(59,130,246,0.05);
        }

        .glow-orange {
            box-shadow: 0 0 25px rgba(249,115,22,0.2), 0 0 70px rgba(249,115,22,0.05);
        }

        model-viewer {
            --progress-bar-color: #F97316;
            --poster-color: transparent;
        }

        [x-cloak] { display: none !important; }
    </style>

    @stack('head')
</head>
<body class="mesh-bg min-h-screen text-zinc-200 selection:bg-sw-blue/30 selection:text-white flex flex-col justify-between">

    <div>
        {{-- ═══════════ NAVBAR ═══════════ --}}
        <nav class="sticky top-0 z-50 border-b border-zinc-800/80 bg-[#0d0f12]/85 backdrop-blur-xl" x-data="{ mobileMenu: false }">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5">

                {{-- Brand Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="relative flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-sw-blue/20 to-bld-orange/20 border border-zinc-700 group-hover:border-sw-blue/60 transition">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 h-2 w-2 rounded-full bg-bld-orange animate-ping"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 leading-none">
                            <span class="text-sm font-extrabold tracking-wider text-white uppercase">Liveria</span>
                            <span class="rounded bg-bld-orange/20 px-1.5 py-0.5 text-[9px] font-bold text-bld-orange border border-bld-orange/30">3D HUB</span>
                        </div>
                        <span class="block text-[9px] text-zinc-500 tracking-widest uppercase mt-0.5">SolidWorks &amp; Blender</span>
                    </div>
                </a>

                {{-- Center Links --}}
                <div class="hidden md:flex items-center gap-7 text-xs font-semibold text-zinc-400">
                    <a href="{{ route('home') }}" class="hover:text-white transition tracking-wider">Home</a>
                    <a href="{{ route('designs.index') }}" class="hover:text-white transition tracking-wider flex items-center gap-1.5 {{ request()->routeIs('designs.index') ? 'text-sw-blue font-bold' : '' }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Library</span>
                    </a>
                    <a href="{{ route('home') }}#blend-vault" class="hover:text-bld-orange transition tracking-wider flex items-center gap-1.5 text-bld-orange/90">
                        <svg class="w-3.5 h-3.5 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>.blend Vault</span>
                    </a>
                    <a href="/api/designs" target="_blank" class="hover:text-emerald-400 transition tracking-wider text-[11px] font-mono flex items-center gap-1">
                        <span class="rounded bg-emerald-950 px-1 py-0.2 text-[9px] text-emerald-400 border border-emerald-800/40">API</span>
                    </a>
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-2.5">
                    @guest
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-700/80 bg-zinc-900/60 px-3.5 py-1.5 text-xs font-medium text-zinc-300 hover:border-zinc-500 hover:text-white transition">
                            <svg class="w-3.5 h-3.5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Admin Login</span>
                        </a>
                    @endguest
                    @auth
                        <span class="hidden sm:inline-flex items-center gap-1 rounded bg-zinc-800/80 px-2.5 py-1 font-mono text-[11px] text-zinc-300 border border-zinc-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                            <span>{{ auth()->user()->name }}</span>
                        </span>
                        <a href="{{ route('designs.create') }}"
                           class="inline-flex items-center gap-1.5 rounded-lg bg-sw-blue px-3.5 py-1.5 text-xs font-bold text-white hover:bg-blue-500 transition shadow-md shadow-blue-900/30">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span class="hidden sm:inline">New 3D Part</span>
                            <span class="sm:hidden">+ Part</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1 rounded-lg border border-zinc-800 px-3 py-1.5 text-xs text-zinc-400 hover:border-red-900 hover:text-red-400 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    @endauth

                    {{-- Mobile Hamburger Button --}}
                    <button type="button" @click="mobileMenu = !mobileMenu"
                            class="md:hidden inline-flex items-center justify-center rounded-lg p-1.5 text-zinc-400 hover:bg-zinc-800 hover:text-white transition">
                        <svg x-show="!mobileMenu" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenu" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile Dropdown Menu --}}
            <div x-show="mobileMenu"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden border-t border-zinc-800/80 bg-[#12141a] px-6 py-4 space-y-3"
                 x-cloak>
                <a href="{{ route('home') }}" class="block text-xs font-semibold text-zinc-300 hover:text-white py-1">Home</a>
                <a href="{{ route('designs.index') }}" class="flex items-center gap-2 text-xs font-semibold {{ request()->routeIs('designs.index') ? 'text-sw-blue' : 'text-zinc-300' }} hover:text-white py-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Library Catalog</span>
                </a>
                <a href="{{ route('home') }}#blend-vault" class="flex items-center gap-2 text-xs font-semibold text-bld-orange hover:text-orange-400 py-1">
                    <svg class="w-3.5 h-3.5 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>.blend Vault Downloads</span>
                </a>
                <a href="/api/designs" target="_blank" class="flex items-center gap-2 text-xs font-mono text-emerald-400 hover:text-emerald-300 py-1">
                    <span class="rounded bg-emerald-950 px-1 py-0.2 text-[9px] text-emerald-400 border border-emerald-800/40">API</span>
                    <span>REST API Endpoint</span>
                </a>
            </div>
        </nav>

        {{-- ═══════════ FLASH ALERTS ═══════════ --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4500)"
                 class="border-b border-emerald-800 bg-emerald-950/60 px-4 py-2.5 text-center text-sm text-emerald-300 flex items-center justify-center gap-2 backdrop-blur-md">
                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show = false" class="ml-4 text-emerald-500 hover:text-emerald-300">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="border-b border-red-800 bg-red-950/60 px-4 py-2.5 text-center text-sm text-red-300 flex items-center justify-center gap-2 backdrop-blur-md">
                <svg class="w-4 h-4 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- ═══════════ MAIN CONTENT ═══════════ --}}
        <main>
            @yield('content')
        </main>
    </div>

    {{-- ═══════════ FOOTER ═══════════ --}}
    <footer class="mt-20 border-t border-zinc-800/80 bg-[#0d0f12] py-8 text-xs text-zinc-600">
        <div class="mx-auto max-w-7xl px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-zinc-400">LIVERIA</span>
                <span>· 3D Engineering &amp; Blender Modeling Hub</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-zinc-500 hover:text-zinc-300 transition">Home</a>
                <span>·</span>
                <a href="{{ route('designs.index') }}" class="text-zinc-500 hover:text-zinc-300 transition">Library</a>
                <span>·</span>
                <span class="text-sw-blue">SolidWorks CAD</span>
                <span>·</span>
                <span class="text-bld-orange">Blender 3D (.blend)</span>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
