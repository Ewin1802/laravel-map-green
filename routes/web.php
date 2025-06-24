<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\SubkegiatanController;
use App\Http\Controllers\SumberdanaController;
use App\Http\Controllers\DanaController;
use App\Http\Controllers\PemakaianDanaController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\LaporanRealisasiController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\RekbelanjaController;


// Semua peran bisa mengakses halaman login
// Route::get('/', function () {
//     return view('pages.auth.login');
// });
Route::get('/', function () {
    // return view('pages.auth.login');
    return view('pages.landing');
});
Route::get('/login', function () {
    return view('pages.auth.login');
})->name('login');

Route::get('/pilih-tahun', [App\Http\Controllers\TahunController::class, 'show'])->name('pilih.tahun');
Route::post('/pilih-tahun', [App\Http\Controllers\TahunController::class, 'set'])->name('set.tahun');

Route::middleware(['auth', 'cek.tahun'])->group(function () {
    Route::get('home', function () {
        return redirect()->route('dashboard');
        })->name('home');

    Route::middleware(['auth', 'role:admin', 'cek.tahun' ])->group(function () {
        Route::get('/dashboard', [ChartController::class, 'index'])->name('dashboard');
        Route::resource('user', UserController::class);
        Route::resource('kegiatans', KegiatanController::class);
        Route::resource('programs', ProgramController::class);
        Route::resource('organisasis', OrganisasiController::class);
        Route::resource('subkegiatans', SubkegiatanController::class);
        Route::resource('sumberdanas', SumberdanaController::class);
        Route::resource('rekbelanjas', RekbelanjaController::class);
        Route::resource('danas', DanaController::class);
        // Route::get('danas/{id}/edit', [DanaController::class, 'edit'])->name('dana.edit');
        Route::resource('pemakaiandanas', PemakaianDanaController::class);
        Route::get('/laporan/realisasi', [LaporanRealisasiController::class, 'index'])->name('laporan.realisasi');
        // web.php
        Route::get('/laporan/realisasi/print', [LaporanRealisasiController::class, 'print'])->name('laporan.realisasi.print');





    });
});
