<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\PostDec;

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

    Route::get('/admin-panel',[AuthController::class, 'admin'])->name('loginadmin');
    Route::post('/postlogin-admin',[AuthController::class,'postloginadmin'])->name('post-admin');
});

Route::middleware(['auth'])->group(function(){
    Route::middleware('userAccess:Admin')->group(function(){
        Route::get('/dashboard-admin',[DashboardController::class, 'dashboardadmin']);
        Route::get('/logoutadmin',[LoginController::class, 'logoutadmin'])->name('logout-admin');
        Route::get('/karyawan',[KaryawanController::class,'index']);
        Route::post('/karyawan/store',[KaryawanController::class,'store']);
        Route::get('/karyawan/{id}/edit',[KaryawanController::class,'edit']);
        Route::post('/karyawan/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::get('/karyawan/{id}/delete',[KaryawanController::class,'destroy']);
        Route::get('/presensi/monitoring',[PresensiController::class,'monitoring']);
        Route::post('/getpresensi',[PresensiController::class,'getpresensi']);
        Route::post('/showmap',[PresensiController::class,'showmap']);
        Route::get('/presensi/laporan',[PresensiController::class,'laporan']);
        Route::post('/presensi/cetaklaporan',[PresensiController::class,'cetaklaporan']);
        Route::get('/presensi/rekap',[PresensiController::class,'rekap']);
        Route::post('/presensi/cetakrekap',[PresensiController::class,'cetakrekap']);
    });

    Route::middleware('userAccess:Karyawan')->group(function(){
        Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
        Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    
        Route::get('/presensi/create',[PresensiController::class, 'index']);
        Route::post('/presensi/store',[PresensiController::class, 'store']);
    
        Route::get('/editprofile',[PresensiController::class, 'edit'])->name('profile');
        Route::post('/presensi/{nik}/updateprofile',[PresensiController::class, 'update']);
        Route::get('/presensi/history',[PresensiController::class,'history']);
        Route::post('/gethistory',[PresensiController::class, 'gethistory']);
    
        Route::get('/presensi/izin',[PresensiController::class, 'izin']);
        Route::get('/presensi/buatizin',[PresensiController::class, 'buatizin']);
        Route::post('/presensi/storeizin',[PresensiController::class, 'storeizin']);
    });
});



