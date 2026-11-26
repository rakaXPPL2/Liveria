@extends('layouts.app')

@section('title', 'Admin Login — Liveria Engineering Hub')

@section('content')
<div class="eng-grid min-h-[calc(100vh-140px)] flex items-center justify-center px-6 py-12"
     x-data="{
         fillDemo() {
             $refs.emailInput.value = 'admin@liveria.test';
             $refs.passwordInput.value = 'password123';
         }
     }">
    <div class="w-full max-w-md">

        {{-- Top Identity Terminal Header --}}
        <div class="mb-8 text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-sw-blue/40 bg-blue-950/20 mb-4 shadow-xl shadow-blue-950/50">
                <svg class="h-8 w-8 text-sw-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <div class="inline-flex items-center gap-1.5 rounded-full border border-sw-blue/30 bg-blue-950/30 px-3 py-1 text-[10px] font-mono text-sw-blue uppercase tracking-widest mb-2">
                <span class="h-1.5 w-1.5 rounded-full bg-sw-blue animate-pulse"></span>
                <span>PDM Access Control Terminal</span>
            </div>
            <h1 class="text-2xl font-extrabold text-white">Engineer &amp; Admin Login</h1>
            <p class="mt-1 text-xs text-zinc-500 font-mono">Autentikasi sesi untuk manajemen aset 3D &amp; BOM</p>
        </div>

        {{-- Card Container --}}
        <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-7 shadow-2xl">

            {{-- Error Alert --}}
            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-800/80 bg-red-950/40 p-3.5 text-xs text-red-300">
                    <div class="flex items-center gap-2 mb-1.5 font-bold text-red-400">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Gagal melakukan otentikasi:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-0.5 font-mono">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono">
                        Email Address
                    </label>
                    <div class="relative">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            x-ref="emailInput"
                            value="{{ old('email', 'admin@liveria.test') }}"
                            required
                            autocomplete="email"
                            class="w-full rounded-xl border @error('email') border-red-700 bg-red-950/30 @else border-zinc-700 bg-[#121316] @enderror pl-4 pr-10 py-3 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono"
                            placeholder="engineer@liveria.test"
                        >
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            x-ref="passwordInput"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-zinc-700 bg-[#121316] pl-4 pr-10 py-3 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono"
                            placeholder="••••••••"
                        >
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                </div>

                <button
                    type="submit"
                    class="mt-2 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-sw-blue py-3 text-sm font-bold text-white shadow-lg shadow-blue-900/40 hover:bg-blue-500 active:bg-blue-700 transition">
                    <span>Masuk ke Terminal</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            {{-- Demo Credentials Quick-Fill Pill --}}
            <div class="mt-6 rounded-xl bg-zinc-900/70 p-3.5 text-xs text-zinc-400 border border-zinc-800 flex items-center justify-between">
                <div>
                    <p class="font-bold text-zinc-300">Akun Demo Standar:</p>
                    <p class="font-mono text-[11px] text-zinc-500 mt-0.5">admin@liveria.test · password123</p>
                </div>
                <button
                    type="button"
                    @click="fillDemo()"
                    class="rounded-lg border border-zinc-700 bg-zinc-800 px-2.5 py-1 text-[11px] font-semibold text-sw-blue hover:text-white hover:border-sw-blue transition">
                    Isi Otomatis
                </button>
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-zinc-500">
            <a href="{{ route('designs.index') }}" class="text-zinc-500 hover:text-zinc-300 transition inline-flex items-center gap-1.5 font-mono">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Katalog Library</span>
            </a>
        </p>
    </div>
</div>
@endsection
