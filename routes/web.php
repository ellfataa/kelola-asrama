<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ResidentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route Profile Bawaan
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === ROUTE PENGELOLAAN ASRAMA ===

    // 1. Route untuk Data Kamar (URL: /rooms)
    Route::resource('rooms', RoomController::class);

    // 2. Route untuk Data Penghuni (URL: /residents)
    Route::resource('residents', ResidentController::class);
});

require __DIR__.'/auth.php';
