<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Agen\AgenController;
use App\Http\Controllers\Pembeli\PembeliController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Hanya admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Hanya agen
Route::middleware(['auth', 'role:agen'])->prefix('agen')->group(function () {
    Route::get('/dashboard', [AgenController::class, 'index'])->name('agen.dashboard');
});

// Hanya pembeli (atau guest bisa lihat listing)
Route::middleware(['auth', 'role:pembeli'])->prefix('pembeli')->group(function () {
    Route::get('/dashboard', [PembeliController::class, 'index'])->name('pembeli.dashboard');
});

require __DIR__.'/auth.php';
