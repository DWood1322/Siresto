<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PelayanController;
use App\Http\Controllers\KokiController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PemilikController;

// Portal & Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes per Role
Route::middleware(['auth'])->group(function () {

    // Role: Pelayan Sub-Menus
    Route::middleware(['role:Pelayan,Pemilik Restoran'])->prefix('pelayan')->name('pelayan.')->group(function () {
        Route::get('/order', [PelayanController::class, 'searchMeja'])->name('order.form');
        Route::get('/ready', [PelayanController::class, 'readyOrders'])->name('ready');
        Route::post('/order', [PelayanController::class, 'storeOrder'])->name('order.store');
        Route::patch('/order/{noPesanan}/serve', [PelayanController::class, 'markServed'])->name('order.serve');
    });

    // Role: Koki Sub-Menus
    Route::middleware(['role:Koki,Pemilik Restoran'])->prefix('koki')->name('koki.')->group(function () {
        Route::get('/kds', [KokiController::class, 'index'])->name('kds');
        Route::get('/menu', [KokiController::class, 'menuIndex'])->name('menu');
        Route::patch('/order/{noPesanan}/complete', [KokiController::class, 'completeOrder'])->name('order.complete');
        Route::patch('/item/{id}/status', [KokiController::class, 'updateItemStatus'])->name('item.status');
        Route::patch('/menu/{kodeMenu}/toggle', [KokiController::class, 'toggleMenuStatus'])->name('menu.toggle');
        Route::post('/menu', [KokiController::class, 'storeMenu'])->name('menu.store');
    });

    // Role: Kasir Sub-Menus
    Route::middleware(['role:Kasir,Pemilik Restoran'])->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('/pos', [KasirController::class, 'index'])->name('index');
        Route::get('/history', [KasirController::class, 'historyIndex'])->name('history');
        Route::post('/payment', [KasirController::class, 'processPayment'])->name('payment.process');
    });

    // Role: Pemilik Restoran Sub-Menus
    Route::middleware(['role:Pemilik Restoran'])->prefix('pemilik')->name('pemilik.')->group(function () {
        Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard');
        Route::post('/laporan/generate', [PemilikController::class, 'generateLaporan'])->name('laporan.generate');
    });
});
