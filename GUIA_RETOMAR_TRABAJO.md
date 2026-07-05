# Guia para retomar el trabajo

Proyecto: `citasdentista`

Ruta local: `/Users/juanjose/PhpstormProjects/citasdentista`

## Estado actual

Aplicacion Laravel 13 con Livewire 4, Flux UI y Tailwind CSS 4 para gestionar clientes, citas y envios de WhatsApp. PHP 8.4. MySQL en produccion, SQLite para tests.

### Stack tecnico

- PHP 8.4, Laravel 13, Livewire 4, Flux UI 2, Tailwind CSS 4
- PHPUnit 12, SQLite in-memory para tests
- Maatwebsite/excel para importaciones CSV
- Twilio SDK para WhatsApp (driver principal)
- Soft deletes en Client y Appointment
- Phone normalization via trait `NormalizesPhone` (E.164)
- Sunday validation via trait `ValidatesSelectableDate`

### Arquitectura

- **Livewire-first**: las rutas apuntan a vistas Blade que montan componentes Livewire
- **Service layer WhatsApp**: `app/Services/WhatsApp/` separa la logica de envio de los modelos
- **Config-based templates**: las plantillas de WhatsApp viven en `config/whatsapp.php`, no en BD (tabla `whatsapp_templates` eliminada)
- **Traits compartidos**: `NormalizesPhone` (Client, WhatsAppMessage, WhatsAppSender), `ValidatesSelectableDate` (AppointmentForm, ClientMessageScheduler)

### Modelos

| Modelo | Descripcion |
|---|---|
| `Client` | Pacientes. Soft deletes. Normalizacion de telefono. |
| `Appointment` | Citas. Soft deletes. Campos: `activo`, `cita_activa`, `enviado`, `entregado`, `confirmada`, `pendiente_reprogramacion`. Timestamps: `whatsapp_sent_at`, `whatsapp_delivered_at`, `whatsapp_read_at`. |
| `WhatsAppMessage` | Mensajes WhatsApp. Campos: `status` (pending/sent/failed), `respuesta` (Confirmar/Reprogramar), `responded_at`, `provider_payload`, `metadata`. |
| `WhatsAppTemplate` | Clase final (no Eloquent). Resuelve plantillas desde config. |
| `AppointmentReminderPreference` | Preferencias de recordatorio por canal (whatsapp/email) y dias de anticipacion. |
| `User` | Usuarios. Campo `is_admin` para guard de administracion. |
| `LoginHistory` | Registro de historial de login de usuarios. |

### Componentes Livewire

| Componente | Funcion |
|---|---|
| `DashboardOverview` | Panel principal: total citas, pendientes, caducados, cancelados, enviados, fallidos |
| `ClientList` | Listado y busqueda de clientes |
| `ClientForm` | Crear/editar cliente |
| `ClientMessageScheduler` | Programar WhatsApp desde ficha de cliente |
| `ClientCsvImporter` | Importar clientes desde CSV |
| `AppointmentList` | Listado de citas con filtros, ordenacion, eliminacion masiva, reenvio |
| `AppointmentForm` | Crear/editar cita con busqueda de cliente |
| `AppointmentOverview` | Vista resumen de citas |
| `DailyAgenda` | Agenda diaria con navegacion por fecha (hoy/manana) |
| `WhatsAppConnectionTest` | Prueba de conexion WhatsApp en ajustes |
| `AppointmentReminderSettings` | Configurar tiempos de envio de recordatorios |

### Comandos Artisan

| Comando | Funcion | Programacion |
|---|---|---|
| `whatsapp:dispatch-due` | Encola y envia mensajes WhatsApp pendientes | 09:00, 12:00, 15:00 (sin overlap) |
| `whatsapp:sync-delivery-status` | Sincroniza estado de entrega desde Twilio API | Cada minuto (sin overlap) |
| `whatsapp:backfill-delivery-state` | Backfill de estados de entrega desde mensajes almacenados | Manual |

### Servicios WhatsApp

| Servicio | Funcion |
|---|---|
| `WhatsAppSender` | Envia mensajes via Twilio/Cloud API/log. Modos: sandbox, sender, service, auto. |
| `AppointmentImmediateSender` | Envio inmediato desde UI (dispatchSync para feedback al usuario) |
| `AppointmentDeliveryStatusSyncer` | Sincroniza estados: via webhook Twilio, polling API, o sync manual desde UI |
| `WhatsAppResponseHandler` | Procesa respuestas del cliente (Confirmar/Reprogramar) |

### Rutas principales

| Ruta | Funcion |
|---|---|
| `/` | Home (redirige a dashboard) |
| `/dashboard` | Panel principal |
| `/agenda` | Agenda diaria |
| `/clients` | Listado de clientes |
| `/clients/list` | Lista de clientes (vista separada) |
| `/clients/create` | Crear cliente |
| `/clients/{id}/edit` | Editar cliente |
| `/clients/{id}/appointments` | Citas de un cliente |
| `/appointments` | Listado de citas |
| `/appointments/enviadas` | Citas enviadas (vista filtrada) |
| `/appointments/create` | Crear cita |
| `/appointments/{id}/edit` | Editar cita |
| `/admin/users` | CRUD de usuarios (admin) |
| `/admin/login-history` | Historial de login (admin) |
| `/admin/tools` | Herramientas: importar/exportar (admin) |
| `/admin/settings` | Ajustes de WhatsApp (admin) |
| `/admin/imports` | Importar CSV (admin) |
| `/admin/export/*` | Exportar CSV: clientes, citas, usuarios, base de datos |
| `/webhooks/twilio/whatsapp-status` | Webhook de estado de Twilio |

### Webhook de Twilio

`POST /webhooks/twilio/whatsapp-status`
- Verifica firma de Twilio
- Sincroniza estado de entrega via `AppointmentDeliveryStatusSyncer::syncFromTwilioWebhook()`
- No recarga la pagina — el usuario navega manualmente para ver cambios

### Respuestas del cliente

Las plantillas pueden incluir botones de respuesta (config en `config/whatsapp.php` > `response_actions`):
- **Confirmar** -> marca la cita como `confirmada = true`
- **Reprogramar** -> marca la cita como `pendiente_reprogramacion = true`

Esto se procesa via `WhatsAppResponseHandler` y se sincroniza en el webhook o polling.

### Ajustes (drag-and-drop)

La pagina de ajustes (`/admin/settings`) tiene secciones reordenables y plegables:
1. **Resumen**: driver, plantilla, credenciales Twilio, modo
2. **Twilio Sandbox**: guia rapida
3. **Estado actual**: credenciales, sender, destino de prueba
4. **Prueba de conexion**: envio real de WhatsApp
5. **Tiempos de envio**: configuracion de recordatorios WhatsApp/email

### Herramientas admin (`/admin/tools`)

- Importar CSV (clientes)
- Exportar: clientes CSV, citas CSV, usuarios CSV, base de datos ZIP (SQLite)

### Dashboard

Muestra 6 contadores:
- Total de citas
- Citas pendientes (futuras, activas, sin enviar)
- Citas caducadas (pasadas, activas, sin enviar, no entregadas)
- Citas canceladas (inactivas, sin enviar, no entregadas)
- Mensajes enviados
- Mensajes fallidos

### Agenda diaria

- Vista por hora del dia
- Navegacion: hoy, manana, y hasta 10 dias (saltando domingos)
- Muestra incidencias: desactivada, sin enviar, no entregada, leida

## Configuracion Twilio

Variables en `.env`:

```env
WHATSAPP_DRIVER=twilio
TWILIO_WHATSAPP_MODE=auto
TWILIO_ACCOUNT_SID=...
TWILIO_AUTH_TOKEN=...
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
TWILIO_TEST_RECIPIENT=whatsapp:+34XXXXXXXXX
```

Modos disponibles:
- `sandbox`: usa `TWILIO_WHATSAPP_FROM` (normalmente `whatsapp:+14155238886`)
- `sender`: usa un remitente real de WhatsApp configurado en Twilio
- `service`: usa `TWILIO_MESSAGING_SERVICE_SID`
- `auto`: prioriza Messaging Service si existe; si no, detecta sandbox; si no, usa sender

No guardar credenciales reales en este documento.

## Comandos utiles

```bash
# Arrancar todo junto
composer run dev

# Tests
php artisan test --compact
php artisan test --compact tests/Feature/ClientManagerTest.php
php artisan test --compact --filter=testName

# Formatear PHP (ejecutar despues de cualquier cambio en PHP)
vendor/bin/pint --dirty --format agent

# Frontend
npm run dev
npm run build

# WhatsApp
php artisan whatsapp:dispatch-due --no-interaction
php artisan whatsapp:sync-delivery-status --no-interaction

# Limpiar cache despues de cambiar .env
php artisan config:clear --no-interaction

# Validar configuracion Twilio sin mostrar secretos
php artisan tinker --execute '$twilio = config("whatsapp.twilio"); $sender = app(\App\Services\WhatsApp\WhatsAppSender::class); echo json_encode(["driver" => config("whatsapp.driver"), "mode" => $twilio["mode"] ?? null, "resolved_mode" => $sender->resolveTwilioMode(), "has_account_sid" => filled($twilio["account_sid"] ?? null), "has_auth_token" => filled($twilio["auth_token"] ?? null), "has_from" => filled($twilio["from"] ?? null), "has_messaging_service_sid" => filled($twilio["messaging_service_sid"] ?? null), "has_test_recipient" => filled($twilio["test_recipient"] ?? null)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);'
```

## Pruebas relacionadas con WhatsApp

```bash
php artisan test --compact tests/Feature/WhatsAppTwilioDispatchTest.php tests/Feature/WhatsAppConnectionTestComponentTest.php tests/Feature/WhatsAppDispatchCommandTest.php
```

## Pendientes

1. Preparar la plantilla de correo de WhatsApp.
2. Preparar la plantilla de correo de cita cancelada.
3. Preparar la plantilla de correo de cita reprogramada.
4. Preparar la plantilla de correo de cita confirmada.
5. Preparar la plantilla de correo de cita enviada.
6. Preparar la plantilla de correo de cita rechazada.
7. Preparar la plantilla de correo de cita rechazada por el cliente.
8. Preparar la plantilla de correo de cita rechazada por el dentista.
9. Preparar para enviar correos de recordatorio de cita.
10. Preparar para enviar correos de confirmacion de cita.
11. Los envios marcados como `queued` deberian verificarse periodicamente para confirmar entrega real. El estado `activo` pasa a `false` y `enviado` a `true` en la BD cuando se verifique la correcta entrega al destinatario.
