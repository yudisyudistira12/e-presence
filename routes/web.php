<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['guest'])->group(function(){
    Route::get('/',[LoginController::class,'index'])->name('login');
    Route::post('/postlogin',[AuthController::class,'postlogin'])->name('post-login');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/admin',[AuthController::class, 'admin'])->middleware('userAccess:kepala toko');
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard')->middleware('userAccess:crew');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/presensi/create',[PresensiController::class, 'index']);
    Route::post('/presensi/store',[PresensiController::class, 'store']);
});



