<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\FirebaseNotificationController;
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

    // Route untuk form perpanjang asrama
    Route::get('/residents/{resident}/extend', [ResidentController::class, 'extend'])->name('residents.extend');
    Route::put('/residents/{resident}/extend', [ResidentController::class, 'updateExtension'])->name('residents.updateExtension');

    // Route untuk form keluar asrama
    Route::get('/residents/{resident}/checkout', [App\Http\Controllers\ResidentController::class, 'checkout'])->name('residents.checkout');
    Route::post('/residents/{resident}/checkout', [App\Http\Controllers\ResidentController::class, 'processCheckout'])->name('residents.processCheckout');

    // Route Data Taruna Keluar (Sesuai Sidebar)
    Route::get('/taruna-keluar', [App\Http\Controllers\ResidentOutController::class, 'index'])->name('resident_outs.index');
    });

Route::get('/test-firebase', [FirebaseNotificationController::class, 'send']);

require __DIR__.'/auth.php';
