<?php

use App\Http\Controllers\DisplayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('antrian/tv', [DisplayController::class, 'getData'])->name('antrian.tv.get');

Route::post('antrian/tv', [DisplayController::class, 'data'])->name('antrian.tv.data');

Route::delete('/antrian/tv', [DisplayController::class, 'deleteData'])->name('antrian.tv.delete');


