<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Agen\AgenController;
use App\Http\Controllers\Pembeli\PembeliController;
use App\Http\Controllers\Agen\AgenPropertyController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Pembeli\BookingController;

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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/properties', [AdminController::class, 'properties'])->name('properties');
    Route::patch('/properties/{property}/approve', [AdminController::class, 'approve'])->name('properties.approve');
});

// Hanya agen
Route::middleware(['auth', 'role:agen'])->prefix('agen')->name('agen.')->group(function () {
    Route::get('/dashboard', [AgenController::class, 'index'])->name('dashboard');
    Route::resource('properties', AgenPropertyController::class);
});

// Hanya pembeli (atau guest bisa lihat listing)
Route::middleware(['auth', 'role:pembeli'])->prefix('pembeli')->group(function () {
    Route::get('/dashboard', [PembeliController::class, 'index'])->name('pembeli.dashboard');
}); 

// Public routes — siapapun bisa akses
Route::get('/', [PropertyController::class, 'index'])->name('home');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

Route::middleware(['auth', 'role:pembeli'])->prefix('pembeli')->name('pembeli.')->group(function () {
    Route::get('/dashboard', [PembeliController::class, 'index'])->name('dashboard');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/properties/{property}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/properties/{property}/book', [BookingController::class, 'store'])->name('bookings.store');
});

require __DIR__.'/auth.php';
