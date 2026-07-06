# Plan: Separar componentes Livewire por página

## Objetivo
Cada ruta/page tenga su propio componente Livewire (PHP + Blade) independiente, sin compartir componentes entre páginas.

## Mapa actual → nuevo

| Ruta | Componente actual | Componente nuevo (PHP) | Blade nuevo |
|------|-------------------|------------------------|-------------|
| `clients/xx/appointments` | `AppointmentList` (compartido) | `ClientAppointments` | `livewire/client-appointments.blade.php` |
| `clients` | `ClientList` (compartido) | `ClientIndex` | `livewire/client-index.blade.php` |
| `clients/list` | `ClientList` (compartido) | `ClientListAll` | `livewire/client-list-all.blade.php` |
| `appointments` | `AppointmentOverview` | `AppointmentIndex` | `livewire/appointment-index.blade.php` |
| `appointments/create` + `edit` | `AppointmentForm` | Se mantiene (create/edit = mismo form) | Se mantiene |
| `agenda` | `DailyAgenda` | `AgendaIndex` | `livewire/agenda-index.blade.php` |

## Archivos a crear

### 1. `app/Livewire/ClientAppointments.php`
- Copiar TODO de `AppointmentList.php` actual
- Renombrar clase a `ClientAppointments`
- Siempre requiere `clientId` (obligatorio, no nullable)
- render() apunta a `livewire.client-appointments`

### 2. `resources/views/livewire/client-appointments.blade.php`
- Copiar TODO de `livewire/appointment-list.blade.php` actual
- Solo vista de tabla para un cliente (sin vista tarjeta por cliente)

### 3. `app/Livewire/ClientIndex.php`
- Lógica de `ClientList.php` pero sin `showAllClients`
- Siempre muestra clientes (con o sin búsqueda)
- render() apunta a `livewire.client-index`

### 4. `resources/views/livewire/client-index.blade.php`
- Copiar de `livewire/client-list.blade.php`

### 5. `app/Livewire/ClientListAll.php`
- Lógica de `ClientList.php` con `showAllClients = true` fijo
- render() apunta a `livewire.client-list-all`

### 6. `resources/views/livewire/client-list-all.blade.php`
- Copiar de `livewire/client-list.blade.php`

### 7. `app/Livewire/AppointmentIndex.php`
- Renombrar de `AppointmentOverview`
- render() apunta a `livewire.appointment-index`

### 8. `resources/views/livewire/appointment-index.blade.php`
- Copiar de `livewire/appointment-overview.blade.php`

### 9. `app/Livewire/AgendaIndex.php`
- Renombrar de `DailyAgenda`
- render() apunta a `livewire.agenda-index`

### 10. `resources/views/livewire/agenda-index.blade.php`
- Copiar de `livewire/daily-agenda.blade.php`

## Archivos a modificar

### Rutas `routes/web.php`
```php
Route::view('/clients', 'clients.index')->name('clients.index');
Route::view('/clients/list', 'clients.list')->name('clients.list');
Route::view('/clients/{client}/appointments', 'appointments.client')...
Route::get('/appointments', AppointmentIndexController::class)->name('appointments.index');
Route::view('/agenda', 'agenda.index')->name('agenda.index');
```

### Wrappers Blade (mantener thin wrappers)
- `resources/views/clients/index.blade.php` → `<livewire:client-index />`
- `resources/views/clients/list.blade.php` → `<livewire:client-list-all />`
- `resources/views/appointments/client.blade.php` → `<livewire:client-appointments :client-id="(int) request()->route('client')" />`
- `resources/views/appointments/index.blade.php` → `<livewire:appointment-index />`
- `resources/views/agenda/index.blade.php` → `<livewire:agenda-index />`

## Archivos a eliminar (o vaciar)
- `app/Livewire/AppointmentList.php` → reemplazado por `ClientAppointments`
- `app/Livewire/AppointmentOverview.php` → reemplazado por `AppointmentIndex`
- `app/Livewire/ClientList.php` → reemplazado por `ClientIndex` + `ClientListAll`
- `app/Livewire/DailyAgenda.php` → reemplazado por `AgendaIndex`
- `resources/views/livewire/appointment-list.blade.php` → movido a `client-appointments`
- `resources/views/livewire/appointment-overview.blade.php` → movido a `appointment-index`
- `resources/views/livewire/client-list.blade.php` → movido a `client-index` y `client-list-all`
- `resources/views/livewire/daily-agenda.blade.php` → movido a `agenda-index`
- `resources/views/appointments/sent.blade.php` → ya no existe route `appointments.sent`

## Orden de ejecución
1. Crear PHP classes nuevas (copia renombrada de las actuales)
2. Crear Blade views nuevas (copia de las actuales, actualizar `return view()`)
3. Actualizar wrappers Blade para apuntar a nuevos componentes
4. Eliminar archivos antiguos
5. Ejecutar `php artisan view:clear`
6. Verificar cada ruta manualmente

## Verificación
- Navegar a `/clients`, `/clients/list`, `/clients/1/appointments`, `/appointments`, `/agenda`
- Cada página debe funcionar igual que antes
- `vendor/bin/pint --dirty --format agent`
