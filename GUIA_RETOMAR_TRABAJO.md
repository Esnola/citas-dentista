# Guia para retomar el trabajo

Proyecto: `citasdentista`

Ruta local: `/Users/juanjose/PhpstormProjects/citasdentista`

## Ponytail

Este proyecto usa [`@dietrichgebert/ponytail`](https://github.com/DietrichGebert/ponytail) (configurado en `opencode.json`). Los agentes DEBEN aplicar la escalera Ponytail antes de escribir código:

1. ¿Esto necesita existir? → Si no, saltar (YAGNI)
2. ¿Ya existe en este codebase? → Reusar patrones/helpers existentes
3. ¿Lo hace stdlib? → Usar funciones nativas de Laravel/PHP/Blade
4. ¿Feature nativa de la plataforma? → Usar HTML/Livewire/Browser APIs primero
5. ¿Dependencia instalada? → Usar Flux, Livewire, etc. — no añadir paquetes nuevos
6. ¿Una línea? → Una línea
7. Solo entonces: el mínimo que funciona

**Nunca simplificar**: validación, manejo de errores, seguridad, accesibilidad o integridad de datos.

**Niveles de intensidad**: `/ponytail lite|full|ultra|off`. Default: `full`.

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
- **Config-based templates**: las plantillas de WhatsApp viven en `config/whatsapp.php`, no en BD
- **Traits compartidos**: `NormalizesPhone` (Client, WhatsAppMessage, WhatsAppSender), `ValidatesSelectableDate` (AppointmentForm, ClientMessageScheduler)

### Modelos

| Modelo | Descripcion |
|---|---|
| `Client` | Pacientes. Soft deletes. Normalizacion de telefono. |
| `Appointment` | Citas. Soft deletes. Campos: `activo`, `cita_activa`, `enviado`, `entregado`, `confirmada`, `pendiente_reprogramacion`. Timestamps: `whatsapp_sent_at`, `whatsapp_delivered_at`, `whatsapp_read_at`, `last_inbound_seen_at`. |
| `WhatsAppMessage` | Mensajes WhatsApp. Campos: `direction` (outbound/inbound), `parent_id` (self-FK), `status` (pending/sent/failed), `respuesta`, `responded_at`, `provider_payload`, `metadata`. |
| `WhatsAppTemplate` | Clase final (no Eloquent). Resuelve plantillas desde config. |
| `AppointmentReminderPreference` | Preferencias de recordatorio por canal (whatsapp/email) y dias de anticipacion. |
| `AppSetting` | Singleton unificado (fusion de `sistema_opciones` + `whatsapp_dispatch_settings`). |
| `WhatsAppCredential` | Credenciales API en BD: `driver`, `account_sid` (encrypted), `auth_token` (encrypted), `webhook_enabled`, `poll_interval`. Fallback a .env. |
| `TwilioContentTemplate` | Templates de contenido Twilio en BD. `content_sid`, `content_variables`. |
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
| `ClientAppointments` | Citas de un cliente: filtros, ordenacion, eliminacion masiva, reenvio, respuesta e historial |
| `AppointmentList` | Listado general de citas con filtros y ordenacion |
| `AppointmentForm` | Crear/editar cita con busqueda de cliente |
| `AppointmentOverview` | Vista resumen de citas |
| `DailyAgenda` | Agenda diaria con navegacion por fecha (hoy/manana) |
| `WhatsAppConnectionTest` | Prueba de conexion WhatsApp en ajustes |
| `AppointmentReminderSettings` | Configurar tiempos de envio, toggle envios programados, horas |
| `TwilioContentTemplateSettings` | Gestionar templates de contenido Twilio |
| `TwilioCredentialSettings` | Credenciales Twilio: modo sandbox/sender, API key, remitente |
| `DispatchBanner` | Banner reactivo: aviso cuando envios automaticos deshabilitados |
| `TableBackup` | Exportar/importar por tabla (clients, appointments, users) |
| `SettingsBackup` | Exportar/importar ajustes del sistema |
| `DatabaseBackup` | Exportar/importar base de datos completa |

### Comandos Artisan

| Comando | Funcion | Programacion |
|---|---|---|
| `whatsapp:dispatch-due` | Encola y envia mensajes WhatsApp pendientes. Verifica `AppSetting.dispatch_enabled`. | 09:00, 12:00, 15:00 (con withoutOverlapping) |
| `whatsapp:sync-delivery-status` | Sincroniza estado de entrega desde Twilio API | Cada minuto (sin overlap) |
| `whatsapp:backfill-delivery-state` | Backfill de estados de entrega desde mensajes almacenados | Manual |
| `settings:export` | Exporta ajustes a JSON | Manual |
| `settings:import` | Importa ajustes desde JSON (v1 + v2) | Manual |

### Servicios WhatsApp

| Servicio | Funcion |
|---|---|
| `WhatsAppSender` | Envia mensajes via Twilio/Cloud API/log. Modos: sandbox, sender, service, auto. |
| `AppointmentImmediateSender` | Envio inmediato desde UI (dispatchSync para feedback al usuario) |
| `AppointmentDeliveryStatusSyncer` | Sincroniza estados: via webhook Twilio, polling API, sync manual desde UI, e inbound responses |
| `WhatsAppResponseHandler` | Procesa respuestas del cliente (3 estados: confirm*, otro payload, texto) |

### Respuestas de WhatsApp (3 estados)

El sistema de matching de respuestas usa 3 estados:

1. **Confirmada** — payload del botón empieza con "confirm" → verde, icono usuario-plus
2. **Consultar** — cualquier otro payload → rojo, icono alerta
3. **Sin cambio** — sin payload → usa campo de texto `respuesta`

- `responseStatusLabel()` en `Appointment` lee del último inbound message
- `WhatsAppResponseHandler::process()` crea registro inbound Y actualiza flags de la cita
- Inbound messages se almacenan con `direction=inbound` y `parent_id` apuntando al outbound original
- Quick Reply button clicks llegan via `/webhooks/twilio/whatsapp-inbound`

### Historial de comunicaciones

- Cada respuesta es un registro SEPARATE en `whatsapp_messages` (no overwrite)
- Columnas: `direction` (outbound/inbound), `parent_id` (self-FK)
- Modal Alpine para ver historial completo
- Badge en columna `Respuesta` abre el historial

### Backup / Import / Export

Accesible desde `/admin/tools`:

#### Por tabla
- Clientes, citas y usuarios: exportar JSON o CSV, importar JSON o CSV

#### Ajustes del sistema
- Configuracion de WhatsApp, recordatorios, plantillas, retencion de datos
- Exportar JSON o CSV ZIP, importar JSON (v1 + v2) o CSV ZIP

#### Base de datos completa
- Exportar JSON o CSV ZIP (una tabla por archivo CSV)
- Importar desde JSON o ZIP con CSVs

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
| `/webhooks/twilio/whatsapp-inbound` | Webhook de mensajes entrantes Twilio |

### Webhook de Twilio

`POST /webhooks/twilio/whatsapp-status`
- Verifica firma de Twilio
- Sincroniza estado de entrega via `AppointmentDeliveryStatusSyncer::syncFromTwilioWebhook()`

`POST /webhooks/twilio/whatsapp-inbound`
- Procesa mensajes entrantes (Quick Reply button clicks)
- Usa `WhatsAppResponseHandler::process()` para crear registro inbound
- Fields: `ButtonText`, `ButtonPayload`, `ButtonType`, `Body`, `OriginalRepliedMessageSid`

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
```

## Pruebas relacionadas con WhatsApp

```bash
php artisan test --compact tests/Feature/WhatsAppTwilioDispatchTest.php tests/Feature/WhatsAppConnectionTestComponentTest.php tests/Feature/WhatsAppDispatchCommandTest.php
```

## Pendientes

### WhatsApp
1. Registrar sender de WhatsApp en Twilio para usar templates custom en español con botones (error 63027 en sandbox).
2. Resolver error 63112 (WABA deshabilitada) si persiste despues de registrar sender.

### Correos
3. Preparar plantilla de correo de WhatsApp.
4. Preparar plantilla de correo de cita cancelada.
5. Preparar plantilla de correo de cita reprogramada.
6. Preparar plantilla de correo de cita confirmada.
7. Preparar plantilla de correo de cita enviada.
8. Preparar plantilla de correo de cita rechazada.
9. Preparar plantilla de correo de cita rechazada por el cliente.
10. Preparar plantilla de correo de cita rechazada por el dentista.
11. Preparar para enviar correos de recordatorio de cita.
12. Preparar para enviar correos de confirmacion de cita.

### Funcionalidad
13. Verificar entrega real de envios marcados como `queued` despues de 24h.
