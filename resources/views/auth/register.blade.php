@extends('layouts.app')

@section('title', 'Buat Akun — Liveria Engineering Hub')

@section('content')
<div class="eng-grid min-h-[calc(100vh-140px)] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">
        <div class="mb-8 text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl border border-sw-blue/40 bg-blue-950/20 mb-4 shadow-xl shadow-blue-950/50">
                <svg class="h-8 w-8 text-sw-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 5v14m-7-7h14m-5-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="inline-flex items-center gap-1.5 rounded-full border border-sw-blue/30 bg-blue-950/30 px-3 py-1 text-[10px] font-mono text-sw-blue uppercase tracking-widest mb-2">
                Buat akun Liveria
            </div>
            <h1 class="text-2xl font-extrabold text-white">Buat akun pengguna</h1>
            <p class="mt-1 text-xs text-zinc-500 font-mono">Daftar untuk mengunggah dan mengelola aset 3D</p>
        </div>

        <div class="rounded-2xl border border-zinc-800 bg-[#16181d] p-7 shadow-2xl">
            @if($errors->any())
                <div class="mb-5 rounded-xl border border-red-800/80 bg-red-950/40 p-3.5 text-xs text-red-300">
                    <p class="mb-1.5 font-bold text-red-400">Periksa kembali data pendaftaran:</p>
                    <ul class="list-disc pl-5 space-y-0.5 font-mono">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono">
                        Nama
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        maxlength="255"
                        autocomplete="name"
                        class="w-full rounded-xl border border-zinc-700 bg-[#121316] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono"
                        placeholder="Nama lengkap"
                    >
                </div>

                <div>
                    <label for="email" class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        maxlength="255"
                        autocomplete="email"
                        class="w-full rounded-xl border border-zinc-700 bg-[#121316] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono"
                        placeholder="nama@email.com"
                    >
                </div>

                <div>
                    <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-zinc-700 bg-[#121316] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono"
                        placeholder="Minimal 8 karakter"
                    >
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-xs font-bold uppercase tracking-wider text-zinc-300 font-mono">
                        Konfirmasi password
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        class="w-full rounded-xl border border-zinc-700 bg-[#121316] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-sw-blue focus:outline-none focus:ring-1 focus:ring-sw-blue transition font-mono"
                        placeholder="Ulangi password"
                    >
                </div>

                <button
                    type="submit"
                    class="mt-2 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-sw-blue py-3 text-sm font-bold text-white transition">
                    <span>Buat akun dan masuk</span>
                </button>
            </form>

            <p class="mt-5 text-center text-xs text-zinc-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-sw-blue hover:underline">Login</a>
            </p>
        </div>
    </div>
</div>
@endsection
