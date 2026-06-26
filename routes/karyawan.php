<?php

use App\Http\Controllers\Karyawan\KaryawanAuthController;
use App\Http\Controllers\Karyawan\KaryawanDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('karyawan.guest')->group(function () {
    Route::get('karyawan/login', [KaryawanAuthController::class, 'create'])->name('karyawan.login');
    Route::post('karyawan/login', [KaryawanAuthController::class, 'store'])->name('karyawan.login.store');
});

Route::middleware('auth:web,karyawan')->group(function () {
    Route::get('karyawan/dashboard', [KaryawanDashboardController::class, 'index'])->name('karyawan.dashboard');
    Route::put('karyawan/password', [KaryawanDashboardController::class, 'updatePassword'])->name('karyawan.password.update');
    Route::post('karyawan/logout', [KaryawanAuthController::class, 'destroy'])->name('karyawan.logout');

    // Features from use case & class diagram
    Route::get('karyawan/ajax/tentukan-buffer', [KaryawanDashboardController::class, 'ajaxTentukanBuffer'])->name('karyawan.ajax.tentukan-buffer');
    
    // Medical Checkup
    Route::post('karyawan/medical-checkup', [KaryawanDashboardController::class, 'updateMedicalCheckup'])->name('karyawan.medicalcheckup.update');
    Route::delete('karyawan/medical-checkup', [KaryawanDashboardController::class, 'destroyMedicalCheckup'])->name('karyawan.medicalcheckup.destroy');

    // Attendance / Absensi
    Route::post('karyawan/fingerprint', [KaryawanDashboardController::class, 'rekamFingerprint'])->name('karyawan.fingerprint');
    Route::post('karyawan/absensi', [KaryawanDashboardController::class, 'storeAbsensi'])->name('karyawan.absensi.store');
    Route::put('karyawan/absensi/{id}', [KaryawanDashboardController::class, 'updateAbsensi'])->name('karyawan.absensi.update');
    Route::delete('karyawan/absensi/{id}', [KaryawanDashboardController::class, 'destroyAbsensi'])->name('karyawan.absensi.destroy');

    // Nutrition Needs / Perencanaan Gizi
    Route::post('karyawan/nutrition', [KaryawanDashboardController::class, 'storeNutrition'])->name('karyawan.nutrition.store');
    Route::put('karyawan/nutrition/{id}', [KaryawanDashboardController::class, 'updateNutrition'])->name('karyawan.nutrition.update');
    Route::delete('karyawan/nutrition/{id}', [KaryawanDashboardController::class, 'destroyNutrition'])->name('karyawan.nutrition.destroy');

    // Menu Planner
    Route::post('karyawan/menu', [KaryawanDashboardController::class, 'storeMenu'])->name('karyawan.menu.store');
    Route::put('karyawan/menu/{id}', [KaryawanDashboardController::class, 'updateMenu'])->name('karyawan.menu.update');
    Route::delete('karyawan/menu/{id}', [KaryawanDashboardController::class, 'destroyMenu'])->name('karyawan.menu.destroy');
    Route::post('karyawan/menu/publish/{id}', [KaryawanDashboardController::class, 'publishMenu'])->name('karyawan.menu.publish');

    // Budget
    Route::post('karyawan/budget', [KaryawanDashboardController::class, 'storeBudget'])->name('karyawan.budget.store');
    Route::put('karyawan/budget/{id}', [KaryawanDashboardController::class, 'updateBudget'])->name('karyawan.budget.update');
    Route::delete('karyawan/budget/{id}', [KaryawanDashboardController::class, 'destroyBudget'])->name('karyawan.budget.destroy');

    // Payroll / Penggajian
    Route::post('karyawan/payroll', [KaryawanDashboardController::class, 'prosesGaji'])->name('karyawan.payroll.store');
    Route::put('karyawan/payroll/{id}', [KaryawanDashboardController::class, 'updateGaji'])->name('karyawan.payroll.update');
    Route::delete('karyawan/payroll/{id}', [KaryawanDashboardController::class, 'destroyGaji'])->name('karyawan.payroll.destroy');
});