<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DisplayController;
use Illuminate\Support\Facades\Route;

/* route full
Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
Route::get('/siswa/{id}/show', [SiswaController::class, 'show'])->name('siswa.show');
Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
Route::patch('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
Route::post('/siswa/store', [SiswaController::class, 'store'])->name('siswa.store');
Route::delete('/siswa/destroy/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
*/

// Route::post('/antrian/tv', [DisplayController::class, 'index'])->name('display.index')->middleware('cross_site_request_forgery')->excludedMiddleware('verify_csrf_token');
// Route::post('/antrian/tv', [DisplayController::class, 'index'])->name('display.index')->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');
Route::get('/display', [DisplayController::class, 'index'])->name('display.index');

