# Handoff

## Objective
Gestión de citas dentales con recordatorios WhatsApp. Sistema completo con envíos programados, respuestas vía botones, y panel de administración.

## Current state
Funcional. Tests pasan (9/10 — 1 fallo preexistente en AppointmentManagerTest).

## Últimos cambios (Sesión 2026-07-07/08)

### 1. Nombre gris para citas inactivas
- `resources/views/livewire/client-appointments.blade.php:195` — nombre del cliente en gris cuando `cita_activa=false`

### 2. Error Twilio 63027 — Template no existe
- **Causa**: el sandbox de Twilio NO soporta templates custom. Solo 3 plantillas pre-aprobadas en inglés.
- **Solución**: modo `text` funciona, modo `template` requiere sender registrado en Twilio.
- Config cache estaba desactualizado (`WHATSAPP_MESSAGE_MODE=text` en cache vs `template` en .env).

### 3. Envíos programados configurables
- **Tabla** `whatsapp_dispatch_settings`: `enabled` (bool), `hours` (json)
- **Modelo** `WhatsAppDispatchSettings` — singleton con `Schema::hasTable` guard
- **UI**: toggle activar/desactivar + grid horas 06:00-21:00 (09:00/12:00/15:00 por defecto)
- **Schedule**: `everyMinute()` con `->when()` que verifica enabled
- **Comando** `DispatchDueWhatsAppMessages`: verifica `enabled` al inicio

### 4. Banner reactivo "Envíos deshabilitados"
- **Componente** `DispatchBanner` (Livewire) con `#[On('dispatchToggled')]` y `#[On('dispatchSettingsChanged')]`
- Aparece/desaparece al girar toggle en Ajustes, sin recarga
- Link a `/admin/settings`

### 5. Credenciales Twilio en Base de Datos
- **Tabla** `whatsapp_credentials`: `mode`, `from_number`, `test_recipient`, `api_key_sid` (encrypted), `api_key_secret` (encrypted), `selected`
- **Modelo** `WhatsAppCredential` — singleton con `encrypted` casts, métodos `resolve*()` con fallback a .env
- **UI** en Ajustes: toggle Sandbox/Sender, campos API key (opcional), remitente, destinatario prueba
- **Resolvedor**: DB → .env (API key优先, luego Auth Token)

## Archivos nuevos (esta sesión)
- `app/Models/WhatsAppDispatchSettings.php`
- `app/Models/WhatsAppCredential.php`
- `app/Livewire/DispatchBanner.php`
- `app/Livewire/TwilioCredentialSettings.php`
- `resources/views/livewire/dispatch-banner.blade.php`
- `resources/views/livewire/twilio-credential-settings.blade.php`
- `database/migrations/2026_07_07_232323_create_whatsapp_dispatch_settings_table.php`
- `database/migrations/2026_07_08_010450_create_whatsapp_credentials_table.php`

## Archivos modificados
- `app/Console/Commands/DispatchDueWhatsAppMessages.php` — check enabled
- `app/Livewire/AppointmentReminderSettings.php` — dispatch hours + enabled
- `app/Providers/AppServiceProvider.php` — registra DispatchBanner, TwilioCredentialSettings
- `app/Services/WhatsApp/WhatsAppSender.php` — DB credentials fallback
- `app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php` — DB credentials fallback
- `resources/views/layouts/app.blade.php` — `<livewire:dispatch-banner />`
- `resources/views/settings/appointment-reminder-settings.blade.php` — toggle + horas
- `resources/views/settings/index.blade.php` — sección Credenciales Twilio
- `routes/console.php` — schedule con `->when()`

## Blockers
- **Twilio sandbox**: solo envía a números registrados (error 63015)
- **Templates custom**: requieren sender registrado (error 63027 en sandbox)
- **27 fallos preexistentes**: Vite manifest + Livewire wire:snapshot en AppointmentManagerTest

## Next steps
1. Registrar sender de WhatsApp en Twilio para usar templates custom en español con botones
2. Implementar envío de correos (plantillas pendientes en GUIA_RETOMAR_TRABAJO.md)
3. Considerar reprogramar vía botón (caso `'reprogram*'` en `resolveAction()`)

## Notes for another computer
- `WHATSAPP_DRIVER=log` por defecto en `.env.example` — cambiar a `twilio` para probar
- `composer run dev` levanta server + queue + pail + vite
- Formatear con `vendor/bin/pint --dirty --format agent` después de cada cambio PHP
- Credenciales Twilio ahora se pueden editar desde `/admin/settings` → Credenciales Twilio
- El schedule ahora usa `everyMinute()` + check de enabled en BD (no más horas hardcodeadas)
- Despachar envío manual: `php artisan whatsapp:dispatch-due --no-interaction`
