<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DesignController;
use Illuminate\Support\Facades\Route;

// ── Landing Page ─────────────────────────────────────────────
Route::get('/', fn () => view('landing'))->name('home');

// ── Autentikasi ──────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── CRUD Admin (harus login) ─────────────────────────────────
// Catatan: Route create harus didaftarkan SEBELUM wildcard /designs/{design}
Route::middleware('auth')->group(function () {
    Route::get('/designs/create', [DesignController::class, 'create'])->name('designs.create');
    Route::post('/designs', [DesignController::class, 'store'])->name('designs.store');
    Route::get('/designs/{design}/edit', [DesignController::class, 'edit'])->name('designs.edit')->whereNumber('design');
    Route::put('/designs/{design}', [DesignController::class, 'update'])->name('designs.update')->whereNumber('design');
    Route::delete('/designs/{design}', [DesignController::class, 'destroy'])->name('designs.destroy')->whereNumber('design');
});

// ── Route Publik (tanpa login) ───────────────────────────────
Route::get('/designs', [DesignController::class, 'index'])->name('designs.index');
Route::get('/designs/{design}', [DesignController::class, 'show'])->name('designs.show')->whereNumber('design');
