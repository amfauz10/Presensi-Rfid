<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\PresensiController;

// Jalur khusus untuk menerima tempelan kartu RFID
Route::post('/tap-presensi', [PresensiController::class, 'tapRFID']);
