<?php

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\SecurityController as AdminSecurityController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Webhooks\TwilioWhatsAppStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/webhooks/twilio/whatsapp-status', TwilioWhatsAppStatusController::class)
    ->name('webhooks.twilio.whatsapp-status');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/debug-livewire', function () {
        $results = [];

        $class = 'App\\Livewire\\WhatsAppConnectionTest';
        $results['class_exists'] = class_exists($class);
        $results['file_exists'] = file_exists(app_path('Livewire/WhatsAppConnectionTest.php'));
        $results['app_path'] = app_path();
        $results['php_version'] = phpversion();

        // Check classmap
        $classMapFile = base_path('vendor/composer/autoload_classmap.php');
        if (file_exists($classMapFile)) {
            $map = require $classMapFile;
            $results['classmap_has_entry'] = isset($map[$class]);
            $results['classmap_entry_path'] = $map[$class] ?? null;
        } else {
            $results['classmap_file'] = 'NOT FOUND';
        }

        // Check static autoload
        $staticFile = base_path('vendor/composer/autoload_static.php');
        $results['static_file_exists'] = file_exists($staticFile);

        // Check PSR4 autoload
        $psr4File = base_path('vendor/composer/autoload_psr4.php');
        if (file_exists($psr4File)) {
            $psr4 = require $psr4File;
            $results['psr4_has_app'] = isset($psr4['App\\']);
            $results['psr4_app_path'] = $psr4['App\\'] ?? null;
        }

        // Check if file is readable
        $filePath = app_path('Livewire/WhatsAppConnectionTest.php');
        $results['file_readable'] = is_readable($filePath);
        $results['file_size'] = file_exists($filePath) ? filesize($filePath) : 0;

        // Try to check Finder state via reflection
        try {
            $finder = app('livewire.finder');
            $ref = new ReflectionClass($finder);
            $classLocationsProp = $ref->getProperty('classLocations');
            $classLocationsProp->setAccessible(true);
            $results['classLocations'] = $classLocationsProp->getValue($finder);

            $viewLocationsProp = $ref->getProperty('viewLocations');
            $viewLocationsProp->setAccessible(true);
            $results['viewLocations'] = $viewLocationsProp->getValue($finder);
        } catch (\Throwable $e) {
            $results['finder_error'] = $e->getMessage();
        }

        return response()->json($results, 200, [], JSON_PRETTY_PRINT);
    })->name('debug.livewire');

    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/clients', 'clients.index')->name('clients.index');
    Route::view('/clients/list', 'clients.list')->name('clients.list');
    Route::view('/clients/create', 'clients.form')->name('clients.create');
    Route::view('/clients/{client}/edit', 'clients.form')->name('clients.edit');
    Route::view('/appointments', 'appointments.index')->name('appointments.index');
    Route::view('/appointments/enviadas', 'appointments.sent')->name('appointments.sent');
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
        Route::view('/admin/tools', 'admin.tools.index')->name('admin.tools');
        Route::get('/admin/export/appointments', [ExportController::class, 'appointments'])->name('admin.export.appointments');
        Route::get('/admin/export/clients', [ExportController::class, 'clients'])->name('admin.export.clients');
    });
});
