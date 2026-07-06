# Handoff

## Objective
Implementar lógica de respuesta WhatsApp basada en `ButtonPayload` de templates Quick Reply, y corregir el syncer de estado de entrega.

## Current state
Todo funcional. Tests pasan (7/7 webhook tests). Los 27 fallos en AppointmentManagerTest son preexistentes (Vite/Livewire).

## Completed

### 1. ButtonPayload en lugar de texto para respuestas
- Si `button_payload` empieza con `confirm` → Confirmada (verde)
- Si `button_payload` existe pero no empieza con `confirm` → Consultar (rojo, icono alert)
- Si no hay `button_payload` → lógica anterior (texto)

### 2. Archivos modificados

**`app/Models/Appointment.php`** — `responseStatusLabel()`:
- Verifica `button_payload` primero
- `confirm*` → retorna `'Confirmar'` (blade lo muestra como "Confirmada")
- Cualquier otro payload → retorna `'Consultar'` (blade lo muestra en rojo)
- Sin payload → usa `$latest->respuesta` (texto)

**`app/Models/WhatsAppMessage.php`** — `isConfirmed()`:
- Verifica `button_payload` empieza con `confirm`, fallback a texto
- `isRescheduleRequested()` sin cambios de payload

**`app/Services/WhatsApp/WhatsAppResponseHandler.php`** — `process()`:
- Simplificado: solo confirma, sin caso reprogram
- Usa `resolveAction()` con payload primero, fallback a texto
- Si payload empieza con `confirm` → `confirmada=true, pendiente_reprogramacion=false`
- Si no → log sin acción

**`app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php`** — `refreshMessageFromTwilio()`:
- **Bug corregido**: ahora actualiza `status` del mensaje cuando Twilio reporta `failed`/`undelivered`
- Antes solo actualizaba `provider_payload['raw']` sin cambiar el status del mensaje

**`resources/views/livewire/appointment-list.blade.php`**:
- Vista tabla: "Consultar" muestra rojo + icono alert (default en match)
- Vista tarjeta: "'Consultar' => bg-red-500/15 text-red-300..." agregado

### 3. Investigación Twilio
- Status callback NO recibe datos de botones, solo estado de entrega
- Botones Quick Reply se reciben como **mensaje entrante** con `ButtonText`, `ButtonPayload`, `ButtonType`
- Documentación: https://www.twilio.com/docs/whatsapp/buttons#receiving-quick-replies

## Files touched
- `/Users/juan/PhpstormProjects/citasdentista/app/Models/Appointment.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Models/WhatsAppMessage.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Services/WhatsApp/WhatsAppResponseHandler.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php`
- `/Users/juan/PhpstormProjects/citasdentista/resources/views/livewire/appointment-list.blade.php`

## Commands / tests
- `vendor/bin/pint --dirty --format agent` → passed
- `php artisan test --compact tests/Feature/TwilioWhatsAppStatusWebhookTest.php` → 7 passed
- `php artisan test --compact tests/Feature/AppointmentManagerTest.php` → 27 failed (preexistentes)

## Blockers
- Sandbox de Twilio: solo envía a números registrados, por eso la mayoría de los 144 mensajes automáticos fallaron con `error_code: 63015`
- Los 27 fallos en AppointmentManagerTest son preexistentes (issues con Vite manifest y Livewire wire:snapshot)

## Next steps
1. Si se necesita soporte para reprogramar vía botón, agregar caso `'reprogram*'` en `resolveAction()` y `responseStatusLabel()`
2. Considerar agregar el syncer al flujo de dispatch para detectar `failed` inmediatamente después del envío

## Notes for another computer
- El proyecto usa `WHATSAPP_DRIVER=log` por defecto en `.env.example` — cambiar a `twilio` para probar envíos reales
- `composer run dev` levanta server + queue + pail + vite
- La tabla `appointment_reminder_preferences` tiene `lead_days=1` habilitado para WhatsApp
- El schedule ejecuta `whatsapp:dispatch-due` a las 09:00, 12:00 y 15:00
- Formatear con `vendor/bin/pint --dirty --format agent` después de cada cambio PHP
