<?php

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\LoginHistoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AppointmentIndexController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Webhooks\TwilioWhatsAppStatusController;
use App\Livewire\AgendaIndex;
use App\Livewire\AppointmentForm;
use App\Livewire\CalendarIndex;
use App\Livewire\ClientAppointments;
use App\Livewire\ClientCsvImporter;
use App\Livewire\ClientForm;
use App\Livewire\ClientListAll;
use App\Livewire\DashboardOverview;
use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Illuminate\Http\Request;
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
    Route::get('/dashboard', DashboardOverview::class)->name('dashboard');
    Route::get('/agenda', AgendaIndex::class)->name('agenda.index');
    Route::view('/agenda/{date}', 'agenda.day')
        ->where('date', '\d{4}-\d{2}-\d{2}')
        ->name('agenda.day');
    Route::get('/calendario', CalendarIndex::class)->name('calendar.index');
    Route::view('/clients', 'clients.index')->name('clients.index');
    Route::get('/clients/list', ClientListAll::class)->name('clients.list');
    Route::get('/clients/create', ClientForm::class)->name('clients.create');
    Route::get('/clients/{client}/edit', ClientForm::class)->name('clients.edit');
    Route::get('/clients/{client}/appointments', ClientAppointments::class)
        ->whereNumber('client')
        ->name('clients.appointments');
    Route::get('/appointments', AppointmentIndexController::class)->name('appointments.index');

    Route::get('/appointments/create', AppointmentForm::class)->name('appointments.create');
    Route::get('/appointments/{appointment}/edit', AppointmentForm::class)->name('appointments.edit');

    Route::post('/appointments/{appointment}/toggle', function (Appointment $appointment, Request $request) {
        abort_unless($appointment->canBeChanged(), 403);
        $field = $request->validate(['field' => 'required|in:activo,cita_activa'])['field'];
        $value = (bool) $request->validate(['value' => 'required|boolean'])['value'];
        $appointment->update([$field => $value]);
        if ($field === 'activo' && ! $value) {
            $appointment->whatsAppMessages()
                ->where('status', WhatsAppMessage::STATUS_PENDING)
                ->delete();
        }

        return response()->json(['ok' => true]);
    })->name('appointments.toggle');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/login-history', [LoginHistoryController::class, 'index'])->name('admin.login-history');

        Route::view('/admin/tools', 'admin.tools.index')->name('admin.tools');
        Route::view('/admin/settings', 'settings.index')->name('settings.index');
        Route::get('/admin/imports', ClientCsvImporter::class)->name('imports.index');
        Route::get('/admin/export/appointments', [ExportController::class, 'appointments'])->name('admin.export.appointments');
        Route::get('/admin/export/appointments-json', [ExportController::class, 'appointmentsJson'])->name('admin.export.appointments-json');
        Route::get('/admin/export/clients', [ExportController::class, 'clients'])->name('admin.export.clients');
        Route::get('/admin/export/clients-json', [ExportController::class, 'clientsJson'])->name('admin.export.clients-json');
        Route::get('/admin/export/users', [ExportController::class, 'users'])->name('admin.export.users');
        Route::get('/admin/export/users-json', [ExportController::class, 'usersJson'])->name('admin.export.users-json');
        Route::get('/admin/export/database', [ExportController::class, 'database'])->name('admin.export.database');
        Route::get('/admin/export/settings', [ExportController::class, 'settings'])->name('admin.export.settings');
        Route::get('/admin/export/settings-csv', [ExportController::class, 'settingsCsv'])->name('admin.export.settings-csv');
        Route::get('/admin/export/all-json', [ExportController::class, 'allJson'])->name('admin.export.all-json');
        Route::get('/admin/export/all-csv', [ExportController::class, 'allCsv'])->name('admin.export.all-csv');
    });
});
