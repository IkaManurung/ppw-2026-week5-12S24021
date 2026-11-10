<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiBukuController;
use App\Http\Controllers\Api\ApiPeminjamanController;

/*
|--------------------------------------------------------------------------
| API SIPUS-Del V1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Autentikasi API
    |--------------------------------------------------------------------------
    */

    Route::prefix('auth')->name('auth.')->group(function () {

        // Login API - publik
        Route::post('/login', [ApiAuthController::class, 'login'])
            ->name('login');

        // Endpoint yang membutuhkan token Sanctum
        Route::middleware('auth:sanctum')->group(function () {

            Route::get('/me', [ApiAuthController::class, 'me'])
                ->name('me');

            Route::post('/logout', [ApiAuthController::class, 'logout'])
                ->name('logout');

        });
    });

    /*
    |--------------------------------------------------------------------------
    | Katalog Buku - endpoint publik
    |--------------------------------------------------------------------------
    */

    Route::get('/bukus', [ApiBukuController::class, 'index'])
        ->name('bukus.index');

    // Consumer API - referensi eksternal OpenLibrary
    Route::get('/bukus/{buku}/referensi-eksternal', [
        ApiBukuController::class,
        'referensiEksternal'
    ])->name('bukus.referensi-eksternal');

    // Detail buku
    Route::get('/bukus/{buku}', [ApiBukuController::class, 'show'])
        ->name('bukus.show');

    /*
    |--------------------------------------------------------------------------
    | Endpoint yang membutuhkan autentikasi Sanctum
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        // Tambah, ubah, dan hapus buku
        Route::post('/bukus', [ApiBukuController::class, 'store'])
            ->name('bukus.store');

        Route::put('/bukus/{buku}', [ApiBukuController::class, 'update'])
            ->name('bukus.update');

        Route::patch('/bukus/{buku}', [ApiBukuController::class, 'patch'])
            ->name('bukus.patch');

        Route::delete('/bukus/{buku}', [ApiBukuController::class, 'destroy'])
            ->name('bukus.destroy');

        // Daftar peminjaman
        Route::get('/peminjamans', [ApiPeminjamanController::class, 'index'])
            ->name('peminjamans.index');

        // Membuat peminjaman
        Route::post('/peminjamans', [ApiPeminjamanController::class, 'store'])
            ->name('peminjamans.store');

        // Mengembalikan buku
        Route::patch('/peminjamans/{peminjaman}/kembalikan', [
            ApiPeminjamanController::class,
            'kembalikan'
        ])->name('peminjamans.kembalikan');

    });

});

/*
|--------------------------------------------------------------------------
| Route Bawaan Laravel
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
