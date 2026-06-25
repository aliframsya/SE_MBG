<?php

use App\Http\Controllers\Karyawan\KaryawanAuthController;
use App\Http\Controllers\Karyawan\KaryawanDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('karyawan.guest')->group(function () {
    Route::get('karyawan/login', [KaryawanAuthController::class, 'create'])->name('karyawan.login');
    Route::post('karyawan/login', [KaryawanAuthController::class, 'store'])->name('karyawan.login.store');
});

Route::middleware('karyawan.auth')->group(function () {
    Route::get('karyawan/dashboard', [KaryawanDashboardController::class, 'index'])->name('karyawan.dashboard');
    Route::put('karyawan/password', [KaryawanDashboardController::class, 'updatePassword'])->name('karyawan.password.update');
    Route::post('karyawan/logout', [KaryawanAuthController::class, 'destroy'])->name('karyawan.logout');
});