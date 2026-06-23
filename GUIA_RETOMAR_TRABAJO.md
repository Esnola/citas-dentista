# Guia para retomar el trabajo

Proyecto: `citasdentista`

Ruta local actual: `/Users/juanjosegonzalez/PhpstormProjects/citasdentista`

## Estado actual

- Aplicacion Laravel con Livewire para gestionar clientes, citas y envios WhatsApp.
- El listado de citas y clientes esta separado de sus formularios de creacion/edicion.
- La pagina de mensajes fue eliminada porque no tenia uso.
- Las citas se ordenan por fecha ascendente por defecto.
- Las citas pasadas o ya enviadas no se pueden editar ni cambiar de estado; solo eliminar.
- En ficha de cliente, las citas futuras no enviadas permiten toggle de activo; las pasadas/enviadas muestran accion de eliminar.
- El envio automatico de WhatsApp esta activo mediante el comando `whatsapp:dispatch-due`.
- El scheduler ejecuta ese comando cada minuto en `routes/console.php`.

## Configuracion Twilio

El flujo real de Twilio esta preparado en `app/Services/WhatsApp/WhatsAppSender.php`.

Variables esperadas en `.env`:

```env
WHATSAPP_DRIVER=twilio
TWILIO_WHATSAPP_MODE=sandbox
TWILIO_ACCOUNT_SID=...
TWILIO_AUTH_TOKEN=...
TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
TWILIO_TEST_RECIPIENT=whatsapp:+34XXXXXXXXX
```

Modos disponibles:

- `sandbox`: usa `TWILIO_WHATSAPP_FROM`, normalmente `whatsapp:+14155238886`.
- `sender`: usa un remitente real de WhatsApp configurado en Twilio.
- `service`: usa `TWILIO_MESSAGING_SERVICE_SID`.
- `auto`: prioriza Messaging Service si existe; si no, detecta sandbox; si no, usa sender.

No guardar credenciales reales en este documento.

## Ultimo ajuste importante

Se corrigio la normalizacion de destinatarios con prefijo `whatsapp:`.

Antes, si se ponia:

```env
TWILIO_TEST_RECIPIENT=whatsapp:+34618287914
```

la vista previa podia mostrar erroneamente:

```text
whatsapp:+3434618287914
```

Ahora debe mostrar:

```text
whatsapp:+34618287914
```

Archivos tocados por este fix:

- `app/Services/WhatsApp/WhatsAppSender.php`
- `app/Livewire/WhatsAppConnectionTest.php`
- `tests/Feature/WhatsAppConnectionTestComponentTest.php`
- `tests/Feature/WhatsAppTwilioDispatchTest.php`

## Comandos utiles

Limpiar cache de configuracion despues de cambiar `.env`:

```bash
php artisan config:clear --no-interaction
```

Validar configuracion Twilio sin mostrar secretos:

```bash
php artisan tinker --execute '$twilio = config("whatsapp.twilio"); $sender = app(\App\Services\WhatsApp\WhatsAppSender::class); echo json_encode(["driver" => config("whatsapp.driver"), "mode" => $twilio["mode"] ?? null, "resolved_mode" => $sender->resolveTwilioMode(), "has_account_sid" => filled($twilio["account_sid"] ?? null), "has_auth_token" => filled($twilio["auth_token"] ?? null), "has_from" => filled($twilio["from"] ?? null), "has_messaging_service_sid" => filled($twilio["messaging_service_sid"] ?? null), "has_test_recipient" => filled($twilio["test_recipient"] ?? null)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);'
```

Lanzar envio automatico de mensajes pendientes:

```bash
php artisan whatsapp:dispatch-due --no-interaction
```

Pasar pruebas relacionadas con WhatsApp/Twilio:

```bash
php artisan test --compact tests/Feature/WhatsAppTwilioDispatchTest.php tests/Feature/WhatsAppConnectionTestComponentTest.php tests/Feature/WhatsAppDispatchCommandTest.php
```

Formatear PHP antes de cerrar cambios:

```bash
vendor/bin/pint --dirty --format agent
```

## Ultimas verificaciones ejecutadas

```bash
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/WhatsAppTwilioDispatchTest.php tests/Feature/WhatsAppConnectionTestComponentTest.php
php artisan config:clear --no-interaction
```

Resultado de pruebas del ultimo fix:

```text
8 tests passed, 36 assertions
```

## Envio automatico realizado

Se ejecuto:

```bash
php artisan whatsapp:dispatch-due --no-interaction
```

Resultado:

```text
Queued 7 appointment message(s).
Processed 1 due message(s).
```

Estado posterior en ese momento:

- Enviados: 1
- Pendientes: 6
- Fallidos: 0

Los 6 pendientes tienen fecha futura y se enviaran cuando llegue su `scheduled_for`.

## Rutas principales

- `/appointments`: listado de citas.
- `/appointments/create`: crear cita.
- `/clients`: listado de clientes.
- `/clients/create`: crear cliente.
- `/settings`: ajustes, plantillas y prueba de conexion WhatsApp.
- `/imports`: importaciones.

## Pendiente recomendado al retomar

1. Ejecutar `git status --short`.
2. Revisar el diff de los cuatro archivos del fix Twilio.
3. Probar visualmente en `/settings` que `TWILIO_TEST_RECIPIENT=whatsapp:+34...` se muestra sin duplicar `+34`.
4. Si se cambia `.env`, ejecutar `php artisan config:clear --no-interaction`.
5. Si se quiere probar envio real, usar primero la prueba de conexion con un numero unido al sandbox de Twilio.
