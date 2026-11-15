<?php

use Illuminate\Support\Facades\Route;
use Modules\Perpustakaan\Http\Controllers\BukuController;

Route::middleware('auth')->prefix('perpustakaan')->name('perpustakaan.')->group(function () {
    Route::get('buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('buku/data', [BukuController::class, 'data'])->name('buku.data');
    Route::post('buku', [BukuController::class, 'store'])->name('buku.store');

    Route::middleware('role:admin')->group(function () {
        Route::put('buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });
});
