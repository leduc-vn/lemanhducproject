<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [FaceController::class, 'index']);      // trang upload
Route::get('/upload', [FaceController::class, 'index']); // trang upload (tùy chọn)
Route::post('/detect', [FaceController::class, 'detect']); // gửi ảnh sang Flask
Route::get('/history', [FaceController::class, 'history']); // lịch sử detect
Route::get('/dashboard', [FaceController::class, 'dashboard']);
