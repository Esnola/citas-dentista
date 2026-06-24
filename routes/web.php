<?php

use App\Http\Controllers\Admin\SecurityController as AdminSecurityController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/clients', 'clients.index')->name('clients.index');
    Route::view('/clients/list', 'clients.list')->name('clients.list');
    Route::view('/clients/create', 'clients.form')->name('clients.create');
    Route::view('/clients/{client}/edit', 'clients.form')->name('clients.edit');
    Route::view('/appointments', 'appointments.index')->name('appointments.index');
    Route::view('/appointments/create', 'appointments.form')->name('appointments.create');
    Route::view('/appointments/{appointment}/edit', 'appointments.form')->name('appointments.edit');
    Route::view('/imports', 'imports.index')->name('imports.index');
    Route::view('/settings', 'settings.index')->name('settings.index');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/security', [AdminSecurityController::class, 'edit'])->name('admin.security.edit');
        Route::put('/admin/security', [AdminSecurityController::class, 'update'])->name('admin.security.update');
    });
});
