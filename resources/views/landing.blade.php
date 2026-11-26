<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liveria 3D — SolidWorks CAD & Blender 3D Engineering Hub</title>

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
                    animation: {
                        'float':       'float 5s ease-in-out infinite',
                        'float-slow':  'float 8s ease-in-out infinite',
                        'spin-slow':   'spin 24s linear infinite',
                        'pulse-glow':  'pulseGlow 3s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%':      { transform: 'translateY(-10px) rotate(1.5deg)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { opacity: '0.4', transform: 'scale(1)' },
                            '50%':      { opacity: '0.75', transform: 'scale(1.04)' },
                        },
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

        /* Engineering Technical Grid */
        .eng-grid {
            background-image:
                linear-gradient(rgba(59,130,246,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59,130,246,.06) 1px, transparent 1px),
                linear-gradient(rgba(249,115,22,.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(249,115,22,.02) 1px, transparent 1px);
            background-size: 80px 80px, 80px 80px, 16px 16px, 16px 16px;
        }

        /* Subtle radial mesh backdrop */
        .mesh-bg {
            background:
                radial-gradient(circle at 15% 20%, rgba(59,130,246,0.12) 0%, transparent 45%),
                radial-gradient(circle at 85% 35%, rgba(249,115,22,0.10) 0%, transparent 50%),
                radial-gradient(circle at 50% 80%, rgba(16,185,129,0.06) 0%, transparent 50%),
                #0d0f12;
        }

        /* Glowing Accents */
        .glow-blue {
            box-shadow: 0 0 25px rgba(59,130,246,0.25), 0 0 80px rgba(59,130,246,0.08);
        }
        .glow-orange {
            box-shadow: 0 0 25px rgba(249,115,22,0.25), 0 0 80px rgba(249,115,22,0.08);
        }
        .text-glow-blue {
            text-shadow: 0 0 30px rgba(59,130,246,0.6);
        }
        .text-glow-orange {
            text-shadow: 0 0 30px rgba(249,115,22,0.6);
        }

        /* Animated Gradient Border */
        @keyframes border-rotate {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animated-border {
            background: linear-gradient(270deg, #3B82F6, #F97316, #10B981, #3B82F6);
            background-size: 300% 300%;
            animation: border-rotate 4s ease infinite;
        }

        /* model-viewer Customization */
        model-viewer {
            --progress-bar-color: #F97316;
            --progress-bar-height: 2px;
            --poster-color: transparent;
        }

        /* Isometric 3D Wireframe Cube (Pure CSS) */
        .cube-scene { perspective: 800px; }
        .cube-wire {
            width: 70px;
            height: 70px;
            position: relative;
            transform-style: preserve-3d;
            animation: rotateCube 16s linear infinite;
        }
        @keyframes rotateCube {
            0%   { transform: rotateX(20deg) rotateY(0deg); }
            100% { transform: rotateX(20deg) rotateY(360deg); }
        }
        .cube-face {
            position: absolute;
            width: 70px;
            height: 70px;
            border: 1px dashed rgba(249,115,22,0.5);
            background: rgba(249,115,22,0.04);
        }
        .cube-front  { transform: translateZ(35px); }
        .cube-back   { transform: rotateY(180deg) translateZ(35px); }
        .cube-right  { transform: rotateY(90deg) translateZ(35px); }
        .cube-left   { transform: rotateY(-90deg) translateZ(35px); }
        .cube-top    { transform: rotateX(90deg) translateZ(35px); }
        .cube-bottom { transform: rotateX(-90deg) translateZ(35px); }

        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="mesh-bg min-h-screen text-zinc-200 overflow-x-hidden selection:bg-sw-blue/30 selection:text-white"
      x-data="{
          mobileMenu: false,
          activeModel: 'astronaut',
          modelList: {
              'astronaut': {
                  name: 'Blender Exploration Suit',
                  type: 'blender',
                  src: 'https://modelviewer.dev/shared-assets/models/Astronaut.glb',
                  poly: '1.25M Tris',
                  engine: 'Cycles GPU',
                  mem: '380 MB',
                  blendFile: 'cyber_drone_mk4.blend'
              },
              'mech': {
                  name: 'Bionic Mech Robot 3D',
                  type: 'blender',
                  src: 'https://modelviewer.dev/shared-assets/models/RobotExpressive.glb',
                  poly: '640K Polys',
                  engine: 'EEVEE Next',
                  mem: '190 MB',
                  blendFile: 'suzanne_cyber_mascot.blend'
              },
              'helmet': {
                  name: 'Battle Damaged Sci-Fi Visor',
                  type: 'blender',
                  src: 'https://modelviewer.dev/shared-assets/models/DamagedHelmet.glb',
                  poly: '890K Polys',
                  engine: 'PBR Cycles 4.3',
                  mem: '240 MB',
                  blendFile: 'scifi_mech_cockpit.blend'
              },
              'cad': {
                  name: 'SolidWorks Precision Core',
                  type: 'solidworks',
                  src: 'https://modelviewer.dev/shared-assets/models/reflective-sphere.glb',
                  poly: 'NURBS CAD',
                  engine: 'Physical Ansys',
                  mem: '145 MB',
                  blendFile: 'planetary_gearbox_rig.blend'
              }
          },
          autoRotate: true
      }">

    {{-- ══════════════════════════════════════════════
         STICKY TOP NAVIGATION
    ══════════════════════════════════════════════ --}}
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-zinc-800/80 bg-[#0d0f12]/85 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-3.5">

            {{-- Brand Logo --}}
            <a href="#hero" class="flex items-center gap-3 group">
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
                        <span class="rounded bg-bld-orange/20 px-1.5 py-0.5 text-[9px] font-bold text-bld-orange border border-bld-orange/30">.BLEND</span>
                    </div>
                    <span class="block text-[9px] text-zinc-500 tracking-widest uppercase mt-0.5">3D CAD & Modeling Hub</span>
                </div>
            </a>

            {{-- Center Navigation Links --}}
            <div class="hidden md:flex items-center gap-8 text-xs font-semibold text-zinc-400">
                <a href="#hero" class="hover:text-white transition tracking-wider">Home</a>
                <a href="#blend-vault" class="hover:text-bld-orange transition tracking-wider flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>.blend Vault</span>
                </a>
                <a href="#showcase" class="hover:text-white transition tracking-wider">3D Viewport</a>
                <a href="#workflows" class="hover:text-white transition tracking-wider">Workflows</a>
                <a href="{{ route('designs.index') }}" class="hover:text-sw-blue transition tracking-wider">CAD Library</a>
            </div>

            {{-- Right Actions --}}
            <div class="hidden md:flex items-center gap-3">
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
                    <a href="{{ route('designs.create') }}"
                       class="inline-flex items-center gap-1.5 rounded-lg bg-sw-blue px-3.5 py-1.5 text-xs font-bold text-white hover:bg-blue-500 transition shadow-md shadow-blue-900/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>New 3D Part</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-800 px-3 py-1.5 text-xs text-zinc-400 hover:text-red-400 hover:border-red-900 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                @endauth
                <a href="{{ route('designs.index') }}"
                   class="animated-border p-px rounded-lg group">
                    <span class="flex items-center gap-1.5 rounded-lg bg-[#0d0f12] px-3.5 py-1.5 text-xs font-bold text-white group-hover:bg-transparent transition">
                        <span>Browse Library</span>
                        <svg class="w-3.5 h-3.5 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </a>
            </div>

            {{-- Mobile Menu Trigger --}}
            <button class="md:hidden text-zinc-400 hover:text-white transition" @click="mobileMenu = !mobileMenu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu Overlay --}}
        <div x-show="mobileMenu" x-cloak class="border-t border-zinc-800 bg-[#0d0f12]/95 px-6 py-4 md:hidden">
            <div class="flex flex-col gap-3 text-sm">
                <a href="#hero" class="text-zinc-300 hover:text-white" @click="mobileMenu=false">Home</a>
                <a href="#blend-vault" class="text-bld-orange font-semibold flex items-center gap-1.5" @click="mobileMenu=false">
                    <svg class="w-4 h-4 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>.blend Vault</span>
                </a>
                <a href="#showcase" class="text-zinc-300 hover:text-white" @click="mobileMenu=false">3D Viewport</a>
                <a href="{{ route('designs.index') }}" class="text-sw-blue font-semibold">CAD & 3D Library</a>
                @guest
                    <a href="{{ route('login') }}" class="text-zinc-400 mt-2 pt-2 border-t border-zinc-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Admin Login</span>
                    </a>
                @endguest
            </div>
        </div>
    </nav>


    {{-- ══════════════════════════════════════════════
         HERO SECTION: 3D Blender Viewport Centerpiece
    ══════════════════════════════════════════════ --}}
    <section id="hero" class="eng-grid relative min-h-screen flex items-center pt-20 pb-16 overflow-hidden">

        {{-- Background Glow Blurs --}}
        <div class="absolute top-24 left-10 h-80 w-80 rounded-full bg-sw-blue/10 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-20 right-10 h-96 w-96 rounded-full bg-bld-orange/12 blur-[140px] pointer-events-none"></div>

        {{-- Floating CSS 3D Cube Wireframe Ornament (Left-Top) --}}
        <div class="cube-scene absolute top-28 left-6 md:left-16 pointer-events-none opacity-40 hidden sm:block">
            <div class="cube-wire">
                <div class="cube-face cube-front"></div>
                <div class="cube-face cube-back"></div>
                <div class="cube-face cube-right"></div>
                <div class="cube-face cube-left"></div>
                <div class="cube-face cube-top"></div>
                <div class="cube-face cube-bottom"></div>
            </div>
            <p class="text-[9px] font-mono text-bld-orange/60 text-center mt-2">cube.blend</p>
        </div>

        {{-- Floating Blender Badge SVG (Bottom-Left) --}}
        <div class="absolute bottom-24 left-8 pointer-events-none opacity-30 animate-float hidden lg:block">
            <div class="flex items-center gap-2 rounded-lg border border-bld-orange/30 bg-orange-950/20 px-3 py-1.5 backdrop-blur-sm">
                <svg class="h-4 w-4 text-bld-orange" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.5 8.2c.4 0 .7.1 1.1.2l3.4-3.4c-.6-.4-1.3-.7-2.1-.9l-2.4 4.1zm-4.6 2.3c.2-.5.5-.9.9-1.2L4.7 5.2c-.6.6-1.1 1.3-1.4 2.1l4.6 3.2zm-.9 3.5c0 .7.2 1.4.5 2l-4.2 2.3c-.2-.7-.3-1.5-.3-2.3 0-.7.1-1.3.2-2l3.8 0zm3.2 4.1c.5.3 1.1.5 1.8.5l.7 4.7c-.8 0-1.6-.2-2.3-.5l-.2-4.7zm6.8-4.1c0 2.8-2.2 5-5 5s-5-2.2-5-5 2.2-5 5-5 5 2.2 5 5zm-3 0c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2z"/>
                </svg>
                <span class="text-[10px] font-mono text-zinc-400">Blender 4.3 LTS Engine</span>
            </div>
        </div>

        <div class="relative mx-auto max-w-7xl px-6 w-full">
            <div class="grid gap-12 lg:grid-cols-12 lg:items-center">

                {{-- LEFT COLUMN (Text Content, 6 cols) --}}
                <div class="lg:col-span-6 space-y-6">

                    {{-- Tech Pill --}}
                    <div class="inline-flex items-center gap-2 rounded-full border border-bld-orange/40 bg-orange-950/30 px-3.5 py-1.5 text-xs text-bld-orange backdrop-blur-md">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-bld-orange opacity-75 animate-ping"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-bld-orange"></span>
                        </span>
                        <span>Blender 3D (.blend) & SolidWorks CAD</span>
                    </div>

                    {{-- Main Headline --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-[1.1]">
                        Hub Desain <br>
                        <span class="text-glow-orange text-bld-orange">Blender 3D</span> &amp;<br>
                        <span class="text-glow-blue text-sw-blue">SolidWorks</span>
                    </h1>

                    {{-- Subtitle --}}
                    <p class="text-sm leading-relaxed text-zinc-400 max-w-lg">
                        Repositori terintegrasi untuk berkas <strong class="text-zinc-200">.blend</strong>, model 3D interaktif GLB,
                        dan CAD assemblies. Dilengkapi kalkulasi <strong class="text-zinc-200">Bill of Materials (BOM)</strong> otomatis,
                        inspeksi poly-count, dan viewer 360° tanpa plugin tambahan.
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <a href="{{ route('designs.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-sw-blue px-6 py-3 text-xs sm:text-sm font-bold text-white shadow-lg shadow-blue-900/40 hover:bg-blue-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Buka Library CAD</span>
                        </a>
                        <a href="#blend-vault"
                           class="inline-flex items-center gap-2 rounded-xl border border-bld-orange/50 bg-orange-950/30 px-5 py-3 text-xs sm:text-sm font-bold text-bld-orange hover:bg-orange-950/60 transition">
                            <svg class="w-4 h-4 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Unduh File .blend</span>
                        </a>
                        @auth
                            <a href="{{ route('designs.create') }}"
                               class="inline-flex items-center gap-2 rounded-xl border border-zinc-700 bg-zinc-800/40 px-4 py-3 text-xs sm:text-sm font-semibold text-zinc-300 hover:text-white hover:border-zinc-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                <span>Upload Part</span>
                            </a>
                        @endauth
                    </div>

                    {{-- Dynamic Stats Counters --}}
                    @php
                        $totalDesigns = \App\Models\Design::count();
                        $swCount      = \App\Models\Design::where('workflow_type', 'solidworks')->count();
                        $bldCount     = \App\Models\Design::where('workflow_type', 'blender')->count();
                        $bomCount     = \App\Models\BomItem::count();
                    @endphp
                    <div class="pt-6 border-t border-zinc-800/80 grid grid-cols-4 gap-3 text-center sm:text-left">
                        <div>
                            <p class="text-2xl sm:text-3xl font-extrabold text-white">{{ $totalDesigns }}</p>
                            <p class="text-[10px] text-zinc-500 uppercase tracking-widest mt-0.5">3D Assets</p>
                        </div>
                        <div class="border-l border-zinc-800/80 pl-3">
                            <p class="text-2xl sm:text-3xl font-extrabold text-bld-orange">{{ $bldCount }}</p>
                            <p class="text-[10px] text-zinc-500 uppercase tracking-widest mt-0.5">.blend 3D</p>
                        </div>
                        <div class="border-l border-zinc-800/80 pl-3">
                            <p class="text-2xl sm:text-3xl font-extrabold text-sw-blue">{{ $swCount }}</p>
                            <p class="text-[10px] text-zinc-500 uppercase tracking-widest mt-0.5">SolidWorks</p>
                        </div>
                        <div class="border-l border-zinc-800/80 pl-3">
                            <p class="text-2xl sm:text-3xl font-extrabold text-emerald-400">{{ $bomCount }}</p>
                            <p class="text-[10px] text-zinc-500 uppercase tracking-widest mt-0.5">BOM Parts</p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Interactive Blender 3D Viewport (6 cols) --}}
                <div class="lg:col-span-6">
                    <div class="relative w-full max-w-lg mx-auto">

                        {{-- Outer Aura Glow --}}
                        <div class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-sw-blue/30 via-bld-orange/30 to-emerald-500/20 blur-xl opacity-70 animate-pulse-glow pointer-events-none"></div>

                        {{-- Viewport Container Card --}}
                        <div class="relative rounded-2xl border border-zinc-700/80 bg-[#16181d] shadow-2xl overflow-hidden glow-orange">

                            {{-- Blender 3D Viewport Header Bar --}}
                            <div class="border-b border-zinc-800 bg-[#121317] px-4 py-2.5 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex gap-1.5">
                                        <span class="h-2.5 w-2.5 rounded-full bg-red-500/80"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-yellow-500/80"></span>
                                        <span class="h-2.5 w-2.5 rounded-full bg-green-500/80"></span>
                                    </div>
                                    <span class="text-[11px] font-bold text-zinc-300 flex items-center gap-1.5 pl-2 border-l border-zinc-800">
                                        <svg class="w-3.5 h-3.5 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span x-text="modelList[activeModel].name"></span>
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="rounded bg-zinc-800 px-2 py-0.5 font-mono text-[10px] text-zinc-400"
                                          x-text="modelList[activeModel].engine"></span>
                                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                </div>
                            </div>

                            {{-- Model Switcher Tabs with Technical SVGs --}}
                            <div class="border-b border-zinc-800/80 bg-[#14161b] px-3 py-2 flex items-center gap-1.5 overflow-x-auto">
                                <button type="button"
                                        @click="activeModel = 'astronaut'"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[11px] font-semibold transition whitespace-nowrap"
                                        :class="activeModel === 'astronaut' ? 'bg-bld-orange text-white shadow-sm' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <span>Exploration Suit</span>
                                </button>
                                <button type="button"
                                        @click="activeModel = 'mech'"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[11px] font-semibold transition whitespace-nowrap"
                                        :class="activeModel === 'mech' ? 'bg-bld-orange text-white shadow-sm' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                    </svg>
                                    <span>Bionic Mech</span>
                                </button>
                                <button type="button"
                                        @click="activeModel = 'helmet'"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[11px] font-semibold transition whitespace-nowrap"
                                        :class="activeModel === 'helmet' ? 'bg-bld-orange text-white shadow-sm' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <span>Sci-Fi Visor</span>
                                </button>
                                <button type="button"
                                        @click="activeModel = 'cad'"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[11px] font-semibold transition whitespace-nowrap"
                                        :class="activeModel === 'cad' ? 'bg-sw-blue text-white shadow-sm' : 'text-zinc-400 hover:text-white hover:bg-zinc-800/60'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>CAD Core</span>
                                </button>
                            </div>

                            {{-- Live 3D Canvas with model-viewer --}}
                            <div class="relative bg-gradient-to-b from-[#0e1014] to-[#16181d]">

                                {{-- Blender 3D Coordinate Axis Gizmo in corner --}}
                                <div class="absolute top-3 right-3 z-20 flex flex-col items-center bg-black/60 backdrop-blur-md rounded-lg p-2 border border-zinc-800 pointer-events-none">
                                    <div class="text-[9px] font-bold font-mono space-y-0.5">
                                        <span class="block text-red-500 font-extrabold">X : +1.0</span>
                                        <span class="block text-emerald-400 font-extrabold">Y : +0.0</span>
                                        <span class="block text-blue-500 font-extrabold">Z : +0.5</span>
                                    </div>
                                    <span class="text-[8px] text-zinc-500 mt-1 uppercase">3D Gizmo</span>
                                </div>

                                {{-- Real-Time Poly Counter Overlay --}}
                                <div class="absolute bottom-3 left-3 z-20 bg-black/70 backdrop-blur-md rounded-md px-2.5 py-1 border border-zinc-800 text-[10px] text-zinc-300 font-mono flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    <span class="text-bld-orange font-bold">Topology:</span>
                                    <span x-text="modelList[activeModel].poly"></span> ·
                                    <span class="text-zinc-500" x-text="modelList[activeModel].mem"></span>
                                </div>

                                {{-- The 3D model-viewer element --}}
                                <template x-if="activeModel === 'astronaut'">
                                    <model-viewer
                                        src="https://modelviewer.dev/shared-assets/models/Astronaut.glb"
                                        camera-controls
                                        auto-rotate
                                        shadow-intensity="1.5"
                                        shadow-softness="0.8"
                                        exposure="1.0"
                                        environment-image="neutral"
                                        camera-orbit="-20deg 75deg 2.5m"
                                        style="width: 100%; height: 400px; background: transparent;">
                                    </model-viewer>
                                </template>

                                <template x-if="activeModel === 'mech'">
                                    <model-viewer
                                        src="https://modelviewer.dev/shared-assets/models/RobotExpressive.glb"
                                        camera-controls
                                        auto-rotate
                                        autoplay
                                        shadow-intensity="1.5"
                                        exposure="0.95"
                                        environment-image="neutral"
                                        style="width: 100%; height: 400px; background: transparent;">
                                    </model-viewer>
                                </template>

                                <template x-if="activeModel === 'helmet'">
                                    <model-viewer
                                        src="https://modelviewer.dev/shared-assets/models/DamagedHelmet.glb"
                                        camera-controls
                                        auto-rotate
                                        shadow-intensity="2"
                                        exposure="1.1"
                                        environment-image="neutral"
                                        style="width: 100%; height: 400px; background: transparent;">
                                    </model-viewer>
                                </template>

                                <template x-if="activeModel === 'cad'">
                                    <model-viewer
                                        src="https://modelviewer.dev/shared-assets/models/reflective-sphere.glb"
                                        camera-controls
                                        auto-rotate
                                        shadow-intensity="1.5"
                                        exposure="1.2"
                                        environment-image="neutral"
                                        style="width: 100%; height: 400px; background: transparent;">
                                    </model-viewer>
                                </template>
                            </div>

                            {{-- Bottom Viewport Controller Toolbar --}}
                            <div class="border-t border-zinc-800 bg-[#121418] px-4 py-2.5 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-3 text-zinc-400">
                                    <span class="inline-flex items-center gap-1.5 text-[11px] text-zinc-300">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                        </svg>
                                        <span>360° Drag &amp; Zoom</span>
                                    </span>
                                    <span class="text-zinc-700">|</span>
                                    <a :href="'/downloads/' + modelList[activeModel].blendFile"
                                       download
                                       class="text-[11px] font-bold text-bld-orange hover:underline inline-flex items-center gap-1">
                                        <svg class="w-3 h-3 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span>Unduh <span x-text="modelList[activeModel].blendFile"></span></span>
                                    </a>
                                </div>
                                <div class="text-[10px] text-zinc-500 font-mono">
                                    WebGL 2.0 Active
                                </div>
                            </div>
                        </div>

                        {{-- Corner Tech Brackets --}}
                        <div class="absolute -top-2 -left-2 h-4 w-4 border-t-2 border-l-2 border-bld-orange/60 rounded-tl"></div>
                        <div class="absolute -top-2 -right-2 h-4 w-4 border-t-2 border-r-2 border-sw-blue/60 rounded-tr"></div>
                        <div class="absolute -bottom-2 -left-2 h-4 w-4 border-b-2 border-l-2 border-sw-blue/60 rounded-bl"></div>
                        <div class="absolute -bottom-2 -right-2 h-4 w-4 border-b-2 border-r-2 border-bld-orange/60 rounded-br"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         SECTION: .BLEND VAULT (Blender Source Files)
    ══════════════════════════════════════════════ --}}
    <section id="blend-vault" class="py-24 border-y border-zinc-800/80 bg-[#101216] relative">
        <div class="mx-auto max-w-7xl px-6">

            {{-- Section Title --}}
            <div class="mb-14 text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 rounded-full border border-bld-orange/30 bg-orange-950/20 px-3.5 py-1 text-xs text-bld-orange mb-3">
                    <svg class="w-3.5 h-3.5 text-bld-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Berkas Proyek Asli Blender 3D</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                    Arsip Berkas <span class="text-bld-orange text-glow-orange">.blend</span> Terbuka
                </h2>
                <p class="mt-3 text-sm text-zinc-400 leading-relaxed">
                    Setiap desain menyertakan berkas sumber proyek Blender (<code class="text-bld-orange">.blend</code>)
                    lengkap dengan node shader, armature rig, modifier stack utuh, dan tekstur PBR 4K.
                </p>
            </div>

            {{-- 3D Cards Grid for .blend Files --}}
            <div class="grid gap-6 md:grid-cols-3">

                {{-- Card 1: Scout Drone --}}
                <div class="group relative rounded-2xl border border-zinc-800 bg-[#161920] p-6 hover:border-bld-orange/50 transition-all duration-300 hover:-translate-y-1 glow-orange">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-xl bg-orange-950/40 border border-bld-orange/30 flex items-center justify-center text-bld-orange">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-mono text-[10px] text-bld-orange uppercase tracking-wider font-bold">BLD-001</span>
                                <h3 class="text-base font-bold text-white group-hover:text-bld-orange transition">Cyber Drone MK-IV</h3>
                            </div>
                        </div>
                        <span class="rounded bg-orange-950/60 border border-bld-orange/30 px-2 py-0.5 text-[10px] font-bold text-bld-orange">
                            .BLEND
                        </span>
                    </div>

                    <p class="text-xs text-zinc-400 mb-5 leading-relaxed">
                        Chassis drone quadcopter taktis dengan sistem carbon fiber procedur, motor gimbal UV-unwrapped, dan armature IK.
                    </p>

                    <div class="space-y-2 mb-6 text-xs text-zinc-300 font-mono bg-zinc-900/60 p-3 rounded-lg border border-zinc-800">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Render Engine</span>
                            <span class="text-white">Cycles 4.3</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Polygon Count</span>
                            <span class="text-bld-orange font-bold">1.25M Tris</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Geometry Nodes</span>
                            <span class="text-emerald-400">Active (Voronoi)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">File Name</span>
                            <span class="text-zinc-300">cyber_drone_mk4.blend</span>
                        </div>
                    </div>

                    <a href="/downloads/cyber_drone_mk4.blend" download
                       class="w-full flex items-center justify-center gap-2 rounded-xl bg-bld-orange px-4 py-2.5 text-xs font-bold text-white hover:bg-orange-500 transition shadow-md shadow-orange-950/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh .blend (Source File)</span>
                    </a>
                </div>

                {{-- Card 2: Suzanne Mascot --}}
                <div class="group relative rounded-2xl border border-zinc-800 bg-[#161920] p-6 hover:border-bld-orange/50 transition-all duration-300 hover:-translate-y-1 glow-orange">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-xl bg-orange-950/40 border border-bld-orange/30 flex items-center justify-center text-bld-orange">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-mono text-[10px] text-bld-orange uppercase tracking-wider font-bold">BLD-002</span>
                                <h3 class="text-base font-bold text-white group-hover:text-bld-orange transition">Suzanne Cyber-Mesh</h3>
                            </div>
                        </div>
                        <span class="rounded bg-orange-950/60 border border-bld-orange/30 px-2 py-0.5 text-[10px] font-bold text-bld-orange">
                            .BLEND
                        </span>
                    </div>

                    <p class="text-xs text-zinc-400 mb-5 leading-relaxed">
                        Mascot resmi Blender 3D (Suzanne) dengan konfigurasi cybernetic visor, modifier bevel subdivision, dan emission shader.
                    </p>

                    <div class="space-y-2 mb-6 text-xs text-zinc-300 font-mono bg-zinc-900/60 p-3 rounded-lg border border-zinc-800">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Render Engine</span>
                            <span class="text-white">EEVEE Next</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Polygon Count</span>
                            <span class="text-bld-orange font-bold">48.5K Quads</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Subsurf Level</span>
                            <span class="text-emerald-400">Level 2 (Non-applied)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">File Name</span>
                            <span class="text-zinc-300">suzanne_cyber_mascot.blend</span>
                        </div>
                    </div>

                    <a href="/downloads/suzanne_cyber_mascot.blend" download
                       class="w-full flex items-center justify-center gap-2 rounded-xl bg-bld-orange px-4 py-2.5 text-xs font-bold text-white hover:bg-orange-500 transition shadow-md shadow-orange-950/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh .blend (Source File)</span>
                    </a>
                </div>

                {{-- Card 3: Planetary Gearbox CAD-Blend --}}
                <div class="group relative rounded-2xl border border-zinc-800 bg-[#161920] p-6 hover:border-sw-blue/50 transition-all duration-300 hover:-translate-y-1 glow-blue">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-xl bg-blue-950/40 border border-sw-blue/30 flex items-center justify-center text-sw-blue">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-mono text-[10px] text-sw-blue uppercase tracking-wider font-bold">HYB-201</span>
                                <h3 class="text-base font-bold text-white group-hover:text-sw-blue transition">Planetary Gearbox Rig</h3>
                            </div>
                        </div>
                        <span class="rounded bg-blue-950/60 border border-sw-blue/30 px-2 py-0.5 text-[10px] font-bold text-sw-blue">
                            CAD + .BLEND
                        </span>
                    </div>

                    <p class="text-xs text-zinc-400 mb-5 leading-relaxed">
                        Model gabungan SolidWorks STEP yang diimport ke Blender untuk visualisasi animasi rasio transmisi gear dan stress render.
                    </p>

                    <div class="space-y-2 mb-6 text-xs text-zinc-300 font-mono bg-zinc-900/60 p-3 rounded-lg border border-zinc-800">
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Render Engine</span>
                            <span class="text-white">Cycles Physical</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Material</span>
                            <span class="text-sw-blue font-bold">AISI 4140 Steel</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">Driver Rig</span>
                            <span class="text-emerald-400">Rotational Constraint</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-zinc-500">File Name</span>
                            <span class="text-zinc-300">planetary_gearbox_rig.blend</span>
                        </div>
                    </div>

                    <a href="/downloads/planetary_gearbox_rig.blend" download
                       class="w-full flex items-center justify-center gap-2 rounded-xl bg-sw-blue px-4 py-2.5 text-xs font-bold text-white hover:bg-blue-500 transition shadow-md shadow-blue-950/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Unduh .blend (Source File)</span>
                    </a>
                </div>

            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         SECTION: DUAL WORKFLOWS (SolidWorks vs Blender)
    ══════════════════════════════════════════════ --}}
    <section id="workflows" class="eng-grid py-24">
        <div class="mx-auto max-w-7xl px-6">

            <div class="mb-14 text-center max-w-2xl mx-auto">
                <span class="text-xs font-bold uppercase tracking-widest text-zinc-500">Pipelines &amp; Compatibility</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">
                    Dua Standar Industri Dalam Satu Wadah
                </h2>
                <p class="text-sm text-zinc-400 mt-3">
                    Kolaborasi tanpa friksi antara insinyur mekanikal SolidWorks dan seniman 3D Blender.
                </p>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">

                {{-- SolidWorks Pipeline Box --}}
                <div class="rounded-2xl border border-sw-blue/30 bg-gradient-to-br from-blue-950/20 via-[#14161b] to-[#121316] p-8 glow-blue">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sw-blue/20 border border-sw-blue/40 text-sw-blue font-extrabold text-lg">
                            SW
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">SolidWorks CAD Workflow</h3>
                            <p class="text-xs text-zinc-400">Presisi parametrik, toleransi manufaktur, dan kalkulasi massa</p>
                        </div>
                    </div>

                    <ul class="space-y-3.5 text-xs text-zinc-300">
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-sw-blue flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><strong>Material Database:</strong> Pencatatan spesifikasi paduan logam, polimer, dan densitas standar ISO.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-sw-blue flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><strong>Kalkulasi Massa (Grams):</strong> Berat aktual otomatis dari volume part CAD untuk analisis beban.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-sw-blue flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><strong>Bill of Materials (BOM):</strong> Rincian part, kuantitas sub-assembly, serta catatan toleransi mesin CNC.</span>
                        </li>
                    </ul>
                </div>

                {{-- Blender Pipeline Box --}}
                <div class="rounded-2xl border border-bld-orange/30 bg-gradient-to-br from-orange-950/20 via-[#14161b] to-[#121316] p-8 glow-orange">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-bld-orange/20 border border-bld-orange/40 text-bld-orange font-extrabold text-lg">
                            BL
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Blender 3D Modeling</h3>
                            <p class="text-xs text-zinc-400">High-poly rendering, visualisasi tekstur PBR, dan preview web .glb</p>
                        </div>
                    </div>

                    <ul class="space-y-3.5 text-xs text-zinc-300">
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-bld-orange flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><strong>Poly Count &amp; Topology:</strong> Monitoring jumlah quads/tris untuk optimasi game engine atau web realtime.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-bld-orange flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><strong>GLB / GLTF Export:</strong> Konversi siap tayang di browser tanpa install software pihak ketiga.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-bld-orange flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span><strong>Berkas .blend Asli:</strong> Simpan file sumber lengkap dengan modifier stack dan node material shader.</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         SECTION: RECENT DESIGNS (From SQLite Database)
    ══════════════════════════════════════════════ --}}
    @php
        $latestDesigns = \App\Models\Design::withCount('bomItems')->latest()->take(6)->get();
    @endphp
    @if($latestDesigns->isNotEmpty())
    <section class="py-24 border-t border-zinc-800/80 bg-[#121418]">
        <div class="mx-auto max-w-7xl px-6">

            <div class="mb-12 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-zinc-500">Live Database Registry</span>
                    <h2 class="text-3xl font-extrabold text-white mt-1">Daftar Desain Terbaru</h2>
                </div>
                <a href="{{ route('designs.index') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold text-sw-blue hover:text-blue-400 transition">
                    <span>Lihat Semua di Library</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($latestDesigns as $item)
                    <div class="group rounded-2xl border border-zinc-800 bg-[#181b22] p-5 hover:border-zinc-700 transition hover:-translate-y-1">
                        <div class="flex items-start justify-between mb-3">
                            <span class="font-mono text-xs font-bold text-sw-blue">{{ $item->code_id }}</span>
                            <span class="rounded px-2 py-0.5 text-[10px] font-bold uppercase
                                @if($item->workflow_type === 'solidworks') bg-blue-950/60 text-sw-blue border border-sw-blue/30
                                @elseif($item->workflow_type === 'blender') bg-orange-950/60 text-bld-orange border border-bld-orange/30
                                @else bg-emerald-950/60 text-emerald-400 border border-emerald-700/30 @endif">
                                {{ $item->workflow_type }}
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-white group-hover:text-sw-blue transition line-clamp-1 mb-2">
                            {{ $item->title }}
                        </h3>

                        <div class="text-xs text-zinc-400 space-y-1 font-mono mb-4">
                            @if($item->material)
                                <p class="text-zinc-300">Material: <span class="text-zinc-400">{{ $item->material }}</span></p>
                            @endif
                            @if($item->mass_grams)
                                <p class="text-zinc-300">Massa: <span class="text-zinc-400">{{ number_format($item->mass_grams, 2) }} g</span></p>
                            @endif
                            @if($item->poly_count)
                                <p class="text-zinc-300">Poly: <span class="text-bld-orange">{{ $item->poly_count }}</span></p>
                            @endif
                            <p class="text-zinc-500">BOM: {{ $item->bom_items_count }} komponen</p>
                        </div>

                        <div class="pt-3 border-t border-zinc-800/80 flex items-center justify-between">
                            <a href="{{ route('designs.show', $item) }}"
                               class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-400 hover:underline">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Preview 3D</span>
                            </a>
                            @auth
                                <a href="{{ route('designs.edit', $item) }}"
                                   class="inline-flex items-center gap-1 text-xs text-amber-400 hover:underline">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>Edit</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif


    {{-- ══════════════════════════════════════════════
         SECTION: REST API DOCUMENTATION
    ══════════════════════════════════════════════ --}}
    <section class="eng-grid py-24 border-t border-zinc-800/80">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">

                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Headless API Ready</span>
                    <h2 class="text-3xl font-extrabold text-white mt-1 mb-4">
                        Akses Data 3D Lewat REST API
                    </h2>
                    <p class="text-sm text-zinc-400 leading-relaxed mb-6">
                        Endpoint publik JSON tersedia untuk integrasi dengan pipeline produksi,
                        Unreal Engine, Blender Python scripts, atau aplikasi frontend eksternal.
                    </p>

                    <div class="space-y-3 text-xs font-mono">
                        <div class="flex items-center gap-3 bg-zinc-900/80 p-3 rounded-lg border border-zinc-800">
                            <span class="rounded bg-emerald-950 px-2 py-0.5 text-emerald-400 font-bold border border-emerald-800/50">GET</span>
                            <span class="text-zinc-200">/api/designs</span>
                            <span class="ml-auto text-zinc-500">Semua desain + BOM</span>
                        </div>
                        <div class="flex items-center gap-3 bg-zinc-900/80 p-3 rounded-lg border border-zinc-800">
                            <span class="rounded bg-emerald-950 px-2 py-0.5 text-emerald-400 font-bold border border-emerald-800/50">GET</span>
                            <span class="text-zinc-200">/api/designs?type=blender</span>
                            <span class="ml-auto text-bld-orange">Filter .blend</span>
                        </div>
                        <div class="flex items-center gap-3 bg-zinc-900/80 p-3 rounded-lg border border-zinc-800">
                            <span class="rounded bg-emerald-950 px-2 py-0.5 text-emerald-400 font-bold border border-emerald-800/50">GET</span>
                            <span class="text-zinc-200">/api/designs?type=solidworks</span>
                            <span class="ml-auto text-sw-blue">Filter CAD</span>
                        </div>
                    </div>
                </div>

                {{-- JSON Code Mockup --}}
                <div class="rounded-2xl border border-zinc-800 bg-[#16181d] shadow-xl overflow-hidden font-mono text-xs">
                    <div class="bg-[#121317] px-4 py-2.5 border-b border-zinc-800 flex items-center justify-between text-zinc-400">
                        <span>Response · 200 OK</span>
                        <span class="text-emerald-400">application/json</span>
                    </div>
                    <pre class="p-5 text-zinc-300 overflow-x-auto leading-relaxed"><code>[
  {
    <span class="text-sw-blue">"id"</span>: 1,
    <span class="text-sw-blue">"code_id"</span>: <span class="text-bld-orange">"BLD-001"</span>,
    <span class="text-sw-blue">"title"</span>: <span class="text-zinc-100">"Cyber Drone MK-IV (.blend)"</span>,
    <span class="text-sw-blue">"workflow_type"</span>: <span class="text-bld-orange">"blender"</span>,
    <span class="text-sw-blue">"poly_count"</span>: <span class="text-zinc-100">"1.25M Triangles"</span>,
    <span class="text-sw-blue">"glb_url"</span>: <span class="text-emerald-400">"http://.../storage/models/drone.glb"</span>,
    <span class="text-sw-blue">"bom_items"</span>: [
      {
        <span class="text-sw-blue">"part_name"</span>: <span class="text-zinc-100">"Main Carbon Chassis"</span>,
        <span class="text-sw-blue">"quantity"</span>: 1,
        <span class="text-sw-blue">"spec_note"</span>: <span class="text-zinc-400">"Baked Normal 4K UV"</span>
      }
    ]
  }
]</code></pre>
                </div>

            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         CALL TO ACTION FOOTER BANNER
    ══════════════════════════════════════════════ --}}
    <section class="py-20 border-t border-zinc-800 bg-gradient-to-b from-[#14161c] to-[#0d0f12]">
        <div class="mx-auto max-w-4xl px-6 text-center">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                Mulai Kelola Proyek 3D &amp; CAD Sekarang
            </h2>
            <p class="mt-3 text-sm text-zinc-400 max-w-xl mx-auto">
                Buka repositori lengkap atau login sebagai administrator untuk mengunggah model 3D baru.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('designs.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-sw-blue px-7 py-3 text-sm font-bold text-white hover:bg-blue-500 transition shadow-lg shadow-blue-900/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Buka 3D Design Library</span>
                </a>
                @guest
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-zinc-700 bg-zinc-900/60 px-7 py-3 text-sm font-bold text-zinc-300 hover:border-zinc-500 hover:text-white transition">
                        <svg class="w-4 h-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Login Admin</span>
                    </a>
                @endguest
            </div>
        </div>
    </section>


    {{-- ══════════════════════════════════════════════
         PAGE FOOTER
    ══════════════════════════════════════════════ --}}
    <footer class="border-t border-zinc-800/80 bg-[#0d0f12] py-8 text-xs text-zinc-600">
        <div class="mx-auto max-w-7xl px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-zinc-400">LIVERIA</span>
                <span>· 3D Engineering &amp; Blender Modeling Hub</span>
            </div>
            <div class="flex gap-4">
                <span class="text-bld-orange font-semibold">.blend Assets Ready</span>
                <span>·</span>
                <span class="text-sw-blue font-semibold">SolidWorks CAD</span>
                <span>·</span>
                <span>PHP 8.5 &amp; Laravel 12</span>
            </div>
        </div>
    </footer>

</body>
</html>
