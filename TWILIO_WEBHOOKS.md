# Twilio WhatsApp Webhooks - Guía de Configuración

## Configuración de Sincronización (Webhook + Polling)

### Toggle Webhook y Poll Interval

En **Ajustes → Credenciales Twilio → Sincronización** se configura:

| Campo | Descripción | Valores |
|-------|-------------|---------|
| **Webhook activado/desactivado** | Toggle para habilitar/deshabilitar webhook | ON/OFF |
| **Intervalo de sincronización** | Segundos entre polls a API Twilio (solo visible si webhook OFF) | 5-60 segundos |

### Comportamiento según configuración

| Webhook | Intervalo | Comportamiento |
|---------|-----------|----------------|
| **ON** | X segundos | Datos llegan via webhook (instantáneo). Poll como fallback cada Xs |
| **OFF** | X segundos | Sin webhook. Poll a API Twilio cada Xs |

### Campos en BD (`whatsapp_credentials`)

```sql
webhook_enabled BOOLEAN DEFAULT true   -- Toggle webhook
poll_interval SMALLINT DEFAULT 10      -- Intervalo en segundos (5-60)
```

### Métodos del modelo WhatsAppCredential

```php
WhatsAppCredential::webhookEnabled()  // bool
WhatsAppCredential::pollInterval()    // int (clamp 5-60)
```

### Observer automático

`WhatsAppCredentialObserver` limpia la cache de vistas automáticamente al guardar/eliminar credenciales, reflejando cambios en `poll_interval` sin intervención manual.

---

## Tipos de Webhooks

### 1. Inbound Message Webhook (Mensajes entrantes)

**Cuándo se usa**: Cuando un cliente envía un mensaje WhatsApp (respuesta a template, texto libre, botones).

**Twilio envía un POST a tu URL con:**

| Parámetro | Descripción |
|-----------|-------------|
| `MessageSid` | ID del mensaje |
| `From` | Número remitente (`whatsapp:+34618287914`) |
| `To` | Tu número Twilio (`whatsapp:+15559355880`) |
| `Body` | Texto del mensaje |
| `ButtonPayload` | Payload del botón pulsado |
| `ButtonText` | Texto visible del botón |
| `ParentMessageSid` | SID del mensaje original (respuestas a templates) |
| `WaId` | WhatsApp ID del remitente |
| `ProfileName` | Nombre del perfil WhatsApp |

### 2. Status Callback URL (Estados de mensajes salientes)

**Cuándo se usa**: Cuando el estado de un mensaje enviado cambia.

**Estados posibles:**
`queued` → `sending` → `sent` → `delivered` → `read`

**Twilio envía un POST con:**

| Parámetro | Descripción |
|-----------|-------------|
| `MessageSid` | ID del mensaje |
| `MessageStatus` | Estado actual |
| `ErrorCode` | Código de error si falló |
| `ChannelStatusMessage` | Mensaje de error del canal |
| `EventType` | `READ` cuando el destinatario lee el mensaje |

---

## Dónde configurar los webhooks

### Opción 1: WhatsApp Sender Endpoint Configuration

```
Twilio Console → Messaging → Senders → WhatsApp Senders
→ Seleccionar sender → Endpoint Configuration
```

Configurar:
- **"A message comes in"** URL → Webhook de mensajes entrantes
- **"Status callback URL"** → Webhook de estados de mensajes salientes

### Opción 2: Messaging Service Integration

```
Twilio Console → Messaging → Services → Seleccionar servicio
→ Integration
```

Configurar ambos webhooks en la sección Integration.

### Opción 3: Conversations API (REQUIERE ESTA SI EL SENDER PERTENECE A UN MESSAGING SERVICE)

```
Twilio Console → Develop → Conversations → Services
→ Seleccionar servicio → Webhooks
→ "On message added" URL
```

### Opción 4: Sandbox (solo para pruebas)

```
Twilio Console → Messaging → Try it out → WhatsApp
→ Sandbox configuration
```

---

## Orden de prioridad

1. **Parámetro en payload API** (`StatusCallback`) - Máxima prioridad
2. **Conversations API Webhooks** - Si el sender está en Conversations API, SOBREESCRIBE todo
3. **Messaging Service Integration** - Si el sender pertenece a un Messaging Service
4. **WhatsApp Sender Endpoint Configuration** - Para senders individuales

---

## IMPORTANTE: Conversations API override

Si tu sender pertenece a un **Messaging Service** (aparece como "Default Messaging Service for Conversations" en la página del sender), la configuración en **Endpoint Configuration** es **IGNORADA**.

**Solución**: Configurar el webhook en Conversations API vía API:

```bash
# Obtener SID del servicio de Conversations
curl -s -u 'ACCOUNT_SID:AUTH_TOKEN' \
  'https://api.twilio.com/2010-04-01/Accounts/ACCOUNT_SID/Conversations/Services.json'

# Configurar webhook de inbound messages
curl -X POST 'https://conversations.twilio.com/v1/Services/SERVICE_SID/Configuration/Webhooks' \
  -u 'ACCOUNT_SID:AUTH_TOKEN' \
  -d 'PostWebhookUrl=https://tu-dominio.com/webhooks/twilio/whatsapp-status' \
  -d 'Method=POST' \
  -d 'Filters=onMessageAdded'
```

---

## Datos de tu cuenta (referencia)

- **Account SID**: `ACe9070c44db0a98324b84059781d64b40`
- **Sender**: `whatsapp:+15559355880`
- **Conversations Service SID**: `ISe51fd657f90c4fdc884791e1b392ac85`
- **Webhook URL**: `https://juanjota.eu/webhooks/twilio/whatsapp-status`

---

## Parámetros adicionales para WhatsApp

| Parámetro | Descripción |
|-----------|-------------|
| `ProfileName` | Nombre del perfil del remitente |
| `WaId` | WhatsApp ID (número de teléfono) |
| `Forwarded` | `true` si el mensaje fue reenviado |
| `FrequentlyForwarded` | `true` si fue reenviado frecuentemente |
| `OriginalRepliedMessageSid` | SID del mensaje al que responde |
| `OriginalRepliedMessageSender` | Sender del mensaje original |

---

## Flujo de mensajes en tu app

### Mensajes salientes (templates):
```
AppointmentImmediateSender → SendWhatsAppMessage job
→ WhatsAppSender::sendViaTwilio()
→ POST a Twilio API con ContentSid + content_variables
→ StatusCallback → /webhooks/twilio/whatsapp-status
→ AppointmentDeliveryStatusSyncer::syncFromTwilioWebhook()
```

### Mensajes entrantes (respuestas):

#### Si webhook está activado:
```
Cliente responde (botón/texto)
→ Twilio POST a Conversations API webhook
→ /webhooks/twilio/whatsapp-status
→ TwilioWhatsAppStatusController
→ WhatsAppResponseHandler::process()
→ Actualiza WhatsAppMessage.respuesta + Appointment.confirmada/pendiente_reprogramacion
```

#### Si webhook está desactivado:
```
Cliente responde (botón/texto)
→ wire:poll.Xs ejecuta autoSync()
→ AppointmentDeliveryStatusSyncer::syncAll()
→ syncInboundResponses() consulta API Twilio
→ fetchInboundFromTwilio() obtiene mensajes entrantes
→ matchInboundToOutbound() hace matching con mensajes salientes
→ Actualiza WhatsAppMessage.respuesta + Appointment.confirmada/pendiente_reprogramacion
```

### Vista de citas (`client-appointments.blade.php`):
```html
wire:poll.{{ $pollInterval }}s="autoSync"
```
El intervalo de poll es dinámico, viene de `WhatsAppCredential::pollInterval()` en la BD.

---

## Errores comunes

| Error | Causa | Solución |
|-------|-------|----------|
| Webhook no llega al servidor | Sender en Conversations API | Configurar webhook en Conversations API |
| 63027 | Template no aprobado en sandbox | Usar sender real o template aprobado |
| 63112 | Meta deshabilitó la WABA | Verificar estado en Meta Business Suite |
| 63046 | Template pendiente de aprobación | Esperar aprobación de Meta |
| Webhook vacío en Twilio | URL se borró por prueba inválida | Restaurar via API o desde settings |

---

## Archivos clave

| Archivo | Función |
|---------|---------|
| `app/Models/WhatsAppCredential.php` | Modelo con `webhookEnabled()`, `pollInterval()` |
| `app/Observers/WhatsAppCredentialObserver.php` | Limpia cache de vistas al guardar credenciales |
| `app/Livewire/Settings/TwilioCredentialSettings.php` | UI de settings con toggle webhook + input poll interval |
| `app/Services/WhatsApp/TwilioConversationsWebhook.php` | Verificar/actualizar webhook en Twilio API |
| `app/Http/Controllers/Webhooks/TwilioWhatsAppStatusController.php` | Handler de webhook entrante |
| `app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php` | Sync manual via polling a API Twilio |
| `resources/views/livewire/client-appointments.blade.php` | Vista con `wire:poll.{{ $pollInterval }}s` |
| `resources/views/settings/twilio-credential-settings.blade.php` | Vista de settings con toggle e input |

---

## Comandos útiles

```bash
# Verificar configuración en servidor
grep TWILIO .env

# Limpiar cache de configuración
php artisan config:cache
php artisan config:clear

# Verificar webhook funciona
curl -X POST https://tu-dominio.com/webhooks/twilio/whatsapp-status \
  -d "Body=Test&From=whatsapp:+34618287914&To=whatsapp:+15559355880"

# Verificar estado del sender en Twilio API
curl -s -u 'ACCOUNT_SID:AUTH_TOKEN' \
  'https://api.twilio.com/2010-04-01/Accounts/ACCOUNT_SID/Messages.json?To=whatsapp:+15559355880&PageSize=5'

# Verificar webhook actual en Conversations API
curl -s -u 'ACCOUNT_SID:AUTH_TOKEN' \
  'https://conversations.twilio.com/v1/Services/SERVICE_SID/Configuration/Webhooks' | jq .

# Configurar webhook en Conversations API
curl -X POST 'https://conversations.twilio.com/v1/Services/SERVICE_SID/Configuration/Webhooks' \
  -u 'ACCOUNT_SID:AUTH_TOKEN' \
  -d 'PostWebhookUrl=https://tu-dominio.com/webhooks/twilio/whatsapp-status' \
  -d 'Method=POST' \
  -d 'Filters=onMessageAdded'

# Verificar estado de webhook en BD
mysql -u root citations -e "SELECT webhook_enabled, poll_interval, status_callback_url FROM whatsapp_credentials WHERE selected = 1;"

# Forzar sync de delivery status
php artisan whatsapp:sync-delivery-status
```
