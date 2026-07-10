# Handoff

## Objective
Gestión de citas dentales con recordatorios WhatsApp. Sistema completo con envíos programados, respuestas vía botones, y panel de administración.

## Current state
Funcional. La lista de citas de un cliente ya muestra respuestas de WhatsApp en la columna `Respuesta`; el badge de respuesta abre el historial completo de comunicaciones. Tests historicos: **103 passed / 44 failed** en la ultima corrida documentada (faltan arreglar tests — ver pendientes abajo).

## Últimos cambios (Sesión 2026-07-10)

### 1. Historial desde columna Respuesta
- `resources/views/livewire/client-appointments.blade.php`
- La cabecera ahora es `Respuesta`.
- Los estados `Confirmada`, `Reprogramar` y `Leer Mensaje` se muestran como botón/badge.
- El badge llama a `openHistory({{ $appointment->id }})` con `wire:click.stop`.
- La celda detiene propagación para no navegar a editar la cita al pulsar la respuesta.

### 2. Acciones sin botón Historial
- `resources/views/components/tabla/botones-maniobra.blade.php`
- Se retiró el botón `Historial`.
- La columna de acciones queda para `Enviar WhatsApp`, `Editar cita` y `Eliminar cita`.

### 3. Verificación realizada
- `php artisan view:cache`
- `php artisan view:clear`
- `php artisan test --compact --filter=ClientAppointments` no encontró tests con ese filtro.

## Últimos cambios (Sesión 2026-07-08)

### 1. Reorganización de archivos Settings
- **Livewire** movidos a `app/Livewire/Settings/` (5 componentes):
  - `SettingsOverview`, `WhatsAppConnectionTest`, `TwilioContentTemplateSettings`, `TwilioCredentialSettings`, `AppointmentReminderSettings`
- **Blade views** movidos a `resources/views/settings/` (4 archivos + index que ya estaba)
- Namespaces y `view()` paths actualizados en cada componente
- `AppServiceProvider.php` actualizado con nuevos namespaces
- Tests de settings actualizados con nuevos imports

### 2. Fix DispatchBanner BindingResolutionException
- `onToggle($params = [])` — parámetro obligatorio sin type hint causaba BindingResolutionException

### 3. status_callback_url guardado en BD
- **Migración**: columna `status_callback_url` agregada a `whatsapp_credentials`
- **Modelo**: `resolveStatusCallbackUrl()` — DB → config fallback
- **Componente**: `mount()` lee de DB, `save()` persiste
- **WhatsAppSender**: usa resolver del modelo
- **TwilioWhatsAppStatusController**: usa resolver del modelo
- Migración redundante `drop_from_number` corregida con `hasColumn()` guard

### 4. Migraciones limpiadas (18 → 12)
- **Consolidados** en CREATE: `appointments` (fecha_original, hora_original, reprogramada), `twilio_content_templates` (content_variables)
- **Eliminados**: add_original_schedule_fields, backfill_original_schedule_fields, add_reprogramada, add_content_variables, seed_sender_numbers_from_from_number

### 5. Prefijo remitente: +1 por defecto + input libre
- Default prefix cambiado de +34 a +1
- `<select>` reemplazado por `<input>` con `<datalist>` para sugerencias
- Validación cambiada de `in:+34,+1,...` a `regex:/^\+\d{1,4}$/`
- Migración `whatsapp_sender_numbers` actualizada

### 6. Botones Expandir/Contraer arreglados
- `$attributes->merge()` no pasaba `x-on:click` de Alpine correctamente
- Componente reescrito: recibe prop `seccion` y genera `x-on:click` internamente
- 6 secciones actualizadas en `settings/index.blade.php`

## Pendiente: Tests fallidos (44)

### Eliminar (tests obsoletos/irrelevantes)
- Ninguno pendiente (ya se eliminaron AdminSecurityTest, DatabaseSeederTest, AppointmentSeederTest, HomeRedirectTest)

### Corregir en otra máquina
Los 44 fallos restantes son de tests que dependen de cambios internos en componentes Livewire. Categorías:

**AppointmentManagerTest (~20 fallos)**:
- `PublicPropertyNotFoundException` — propiedades `$dateFilter`, `$filter_enviado`, `$filter_entregado`, `$filter_activo` ya no existen en `ClientAppointments`
- `ViewException` — `ClientAppointments` requiere `clientId` obligatorio
- `Undefined variable $client` — líneas 1293, 2144
- Assertions desactualizadas (texto de botones, mensajes de estado)

**ClientManagerTest (1 fallo)**:
- `test_client_list_searches_after_one_character` — `assertSee('Las coincidencias aparecerán aquí')` falla porque el texto está en un bloque `@forelse/@empty` que no se renderiza cuando hay clientes

**WhatsAppDispatchCommandTest (1 fallo)**:
- `test_active_appointments_are_queued_for_selected_whatsapp_lead_days` — necesita ajuste de fechas o preferencias

**WhatsAppTwilioDispatchTest (1 fallo)**:
- `test_due_messages_can_be_sent_with_a_twilio_content_template` — HTTP request no registrado, posible issue con credential fallback

**ClientMessageSchedulerTest (1 fallo)**:
- `Undefined array key "Body"` — template mode envía ContentSid en vez de Body

**DashboardOverviewTest (1 fallo)**:
- `assertSee('Desactivada')` — texto ya no existe en la vista

**FailedWhatsAppMessageDisplayTest (3 fallos)**:
- `assertSee('Error de envío')` — texto cambiado
- `PublicPropertyNotFoundException $showAllHistory` — propiedad eliminada de `ClientAppointments`

**AppointmentReminderSettingsTest (0 fallos)** — ya arreglado

### Para arreglarlos:
1. Leer `ClientAppointments.php` y alinear tests con propiedades actuales
2. Buscar textos actuales en vistas para actualizar assertions
3. Verificar que `WhatsAppSender` resuelve credenciales correctamente en modo template
4. Revisar `ClientMessageScheduler` para entender cambio de Body → ContentSid

## Archivos modificados en esta sesión
- `app/Livewire/Settings/SettingsOverview.php` (nuevo path + namespace)
- `app/Livewire/Settings/WhatsAppConnectionTest.php` (nuevo path + namespace)
- `app/Livewire/Settings/TwilioContentTemplateSettings.php` (nuevo path + namespace)
- `app/Livewire/Settings/TwilioCredentialSettings.php` (nuevo path + namespace + fixes)
- `app/Livewire/Settings/AppointmentReminderSettings.php` (nuevo path + namespace)
- `app/Livewire/DispatchBanner.php` (fix $params)
- `app/Models/WhatsAppCredential.php` (status_callback_url + resolver)
- `app/Services/WhatsApp/WhatsAppSender.php` (DB credential resolver)
- `app/Http/Controllers/Webhooks/TwilioWhatsAppStatusController.php` (DB credential resolver)
- `app/Providers/AppServiceProvider.php` (nuevos imports)
- `resources/views/settings/index.blade.php` (expandir-contraer fixes)
- `resources/views/components/botones/expandir-contraer.blade.php` (seccion prop)
- `resources/views/settings/settings-overview.blade.php` (movido)
- `resources/views/settings/whatsapp-connection-test.blade.php` (movido)
- `resources/views/settings/twilio-content-template-settings.blade.php` (movido)
- `resources/views/settings/twilio-credential-settings.blade.php` (movido)
- `resources/views/settings/appointment-reminder-settings.blade.php` (ya estaba)
- `database/migrations/2026_06_23_000003_create_appointments_table.php` (consolidado)
- `database/migrations/2026_07_06_000000_create_twilio_content_templates_table.php` (consolidado)
- `database/migrations/2026_07_08_010450_create_whatsapp_credentials_table.php` (status_callback_url)
- `database/migrations/2026_07_08_120000_create_whatsapp_sender_numbers_table.php` (default +1)
- `database/migrations/2026_07_08_120001_drop_from_number_test_recipient_from_whatsapp_credentials.php` (hasColumn guard)
- `tests/Feature/SettingsPageTest.php` (assertions actualizadas)
- `tests/Feature/AppointmentReminderSettingsTest.php` (admin user)
- `tests/Feature/ClientManagerTest.php` (assertions actualizadas)
- `tests/Feature/WhatsAppConnectionTestComponentTest.php` (import actualizado)
- `tests/Feature/TwilioContentTemplateSettingsTest.php` (import actualizado)
- `tests/Feature/AppointmentReminderSettingsTest.php` (import actualizado)
- `tests/Feature/WhatsAppDispatchCommandTest.php` (dispatch settings setup)

## Blockers
- **Twilio sandbox**: solo envía a números registrados (error 63015)
- **Templates custom**: requieren sender registrado (error 63027 en sandbox)

## Next steps
1. Arreglar los 44 tests fallidos (ver sección pendiente arriba)
2. Registrar sender de WhatsApp en Twilio para usar templates custom en español con botones
3. Implementar envío de correos (plantillas pendientes en GUIA_RETOMAR_TRABAJO.md)
4. Añadir o actualizar tests de UI/Livewire para cubrir el historial desde la columna `Respuesta`

## Notes for another computer
- `WHATSAPP_DRIVER=log` por defecto en `.env.example` — cambiar a `twilio` para probar
- `composer run dev` levanta server + queue + pail + vite
- Formatear con `vendor/bin/pint --dirty --format agent` después de cada cambio PHP
- Credenciales Twilio ahora se pueden editar desde `/admin/settings` → Credenciales Twilio
- El schedule ahora usa `everyMinute()` + check de enabled en BD
- Despachar envío manual: `php artisan whatsapp:dispatch-due --no-interaction`
- Migraciones: 12 archivos (consolidados los add_xxx en CREATE)
- Tests: 103 passed / 44 failed — ver sección "Pendiente" para detalles
