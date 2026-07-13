# Handoff — Estado del Proyecto

## Objective
Gestionar clientes, citas y recordatorios WhatsApp para una clínica dental. Backup/import/export completo.

## Ponytail

Este proyecto usa Ponytail (`@dietrichgebert/ponytail`). Ver `AGENTS.md` para la escalera completa. Nivel por defecto: `full`.

## Current state

Sistema completo funcionando. Últimas tareas:
- Response tracking simplificado a 3 estados (confirm*/otro payload/texto)
- Migraciones unificadas (add_xxx fusionadas en create_xxx)
- Backup/import/export: settings, per-table, full DB

## Completed

### Settings merge (6 → 5 tables)
- `sistema_opciones` + `whatsapp_dispatch_settings` → `app_settings`
- Export v2, import v1+v2 backward compat

### Per-table export/import (`/admin/tools`)
- `TableBackup`: JSON + CSV per table, upsert por ID (clients: phone+name)

### Settings export/import
- `SettingsBackup`: JSON + CSV ZIP, encrypted fields handled

### Full database export/import
- `DatabaseBackup`: JSON + CSV ZIP, FK order respected

### WhatsApp response tracking (3 states)
- `WhatsAppResponseHandler::process()`: creates inbound record + updates appointment flags
- `responseStatusLabel()` en `Appointment`: reads from latest inbound's `button_payload`
- Inbound webhook at `/webhooks/twilio/whatsapp-inbound`
- Quick Reply button clicks: `ButtonText`, `ButtonPayload`, `OriginalRepliedMessageSid`
- 3 states: confirm* → Confirmada (green); other payload → Consultar (red); no payload → text

### Auto-dispatch
- `whatsapp:dispatch-due` at 09:00, 12:00, 15:00
- lead_days=1 enabled by default
- Deduplication via `appointmentReminderExists()` checking metadata.channel + lead_days

## Files touched (key)

### Models
- `app/Models/Appointment.php` — `responseStatusLabel()` (payload-first), `isFuture()` (date-only)
- `app/Models/WhatsAppMessage.php` — `isConfirmed()`, `direction`, `parent_id`
- `app/Models/AppSetting.php` — merged singleton
- `app/Models/WhatsAppCredential.php` — encrypted fields, `webhook_enabled`, `poll_interval`

### Services
- `app/Services/WhatsApp/WhatsAppResponseHandler.php` — 3-state matching
- `app/Services/WhatsApp/WhatsAppSender.php` — drivers: log, twilio, cloud_api
- `app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php` — webhook, polling, inbound sync

### Livewire
- `app/Livewire/Settings/TableBackup.php` — per-table export/import
- `app/Livewire/Settings/SettingsBackup.php` — settings export/import
- `app/Livewire/Settings/DatabaseBackup.php` — full DB export/import

### Views
- `resources/views/livewire/appointment-list.blade.php` — Consultar red styling
- `resources/views/livewire/client-appointments.blade.php` — response badges, history modal

### Tests
- `tests/Feature/TwilioWhatsAppStatusWebhookTest.php` — 7/7 passed (40 assertions)

## Blockers
- None

## Next steps
1. Verify auto-dispatch in production (run `whatsapp:dispatch-due` manually)
2. Test full flow: create appointment → send WhatsApp → receive response → verify badge + history
3. Unify migration files (add_xxx → create_xxx) — COMPLETED
4. Pending: email templates (items 3-12 in GUIA_RETOMAR_TRABAJO.md)

## Notes for another computer
- Run `php artisan migrate` after pulling
- Run `php artisan view:clear` if stale component errors
- All Livewire components registered in `AppServiceProvider` — auto-discovery disabled
- Export routes: `admin.export.*` (clients, appointments, users, settings, all, database)
- Encrypted WhatsApp credentials: decrypted on export, re-encrypted on import
- `WHATSAPP_DRIVER=log` is safe default — never hardcode credentials
