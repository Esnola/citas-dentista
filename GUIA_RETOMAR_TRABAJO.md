# Guía para retomar el trabajo

Proyecto: `citasdentista`

Ruta local actual: `/Users/juan/PhpstormProjects/citasdentista`

## Estado actual

- Aplicación Laravel con Livewire para gestionar clientes, citas y envíos WhatsApp.
- El listado de citas y clientes está separado de sus formularios de creacion/edicion.
- La página de mensajes fue eliminada porque no tenía uso.
- Las citas se ordenan por fecha ascendente por defecto.
- Las citas pasadas o ya enviadas no se pueden editar ni cambiar de estado; solo eliminar.
- En ficha de cliente, las citas futuras no enviadas permiten toggle de activo; las pasadas/enviadas muestran acción de
  eliminar.
- El envío automático de WhatsApp está activo mediante el comando `whatsapp:dispatch-due`.
- El scheduler ejecuta ese comando cada minuto en `routes/console.php`.

## Configuración Twilio

El flujo real de Twilio está preparado en `app/Services/WhatsApp/WhatsAppSender.php`.

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

Se corrigió la normalizacion de destinatarios con prefijo `whatsapp:`.

Antes, si se ponía:

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

Limpiar caché de configuración después de cambiar `.env`:

```bash
php artisan config:clear --no-interaction
```

Validar configuración Twilio sin mostrar secretos:

```bash
php artisan tinker --execute '$twilio = config("whatsapp.twilio"); $sender = app(\App\Services\WhatsApp\WhatsAppSender::class); echo json_encode(["driver" => config("whatsapp.driver"), "mode" => $twilio["mode"] ?? null, "resolved_mode" => $sender->resolveTwilioMode(), "has_account_sid" => filled($twilio["account_sid"] ?? null), "has_auth_token" => filled($twilio["auth_token"] ?? null), "has_from" => filled($twilio["from"] ?? null), "has_messaging_service_sid" => filled($twilio["messaging_service_sid"] ?? null), "has_test_recipient" => filled($twilio["test_recipient"] ?? null)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);'
```

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

## Últimas verificaciones ejecutadas

```bash
vendor/bin/pint --dirty --format agent
php artisan test --compact tests/Feature/WhatsAppTwilioDispatchTest.php tests/Feature/WhatsAppConnectionTestComponentTest.php
php artisan config:clear --no-interaction
```

Resultado de pruebas del último fix:

```text
8 tests passed, 36 assertions
```

## Envío automático realizado

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

Los 6 pendientes tienen fecha futura y se enviarán cuando llegue su `scheduled_for`.

## Rutas principales

- `/appointments`: listado de citas.
- `/appointments/create`: crear cita.
- `/clients`: listado de clientes.
- `/clients/create`: crear cliente.
- `/settings`: ajustes, plantillas y prueba de conexión WhatsApp.
- `/imports`: importaciones.

## Pendiente recomendado al retomar

1. ~~Ejecutar `git status --short`.~~
2. ~~Revisar el diff de los cuatro archivos del fix Twilio.~~~
3. ~~Probar visualmente en `/settings` que `TWILIO_TEST_RECIPIENT=whatsapp:+34...` se muestra sin duplicar `+34`.~~
4. ~~Sí se cambia `.env`, ejecutar `php artisan config:clear --no-interaction`.~~
5. ~~Si se quiere probar envío real, usar primero la prueba de conexión con un número unido al sandbox de Twilio.~~
6. ~~Ver el porqué no se puede seleccionar nada en la zona de ajustes.~~
7. ~~Hacer que en el archivo .env se pueda cambiar el modo de Twilio y que se refleje en la vista previa de conexión.~~
8. Preparar la plantilla de correo de WhatsApp.
9. Preparar la plantilla de correo de cita cancelada.
10. Preparar la plantilla de correo de cita reprogramada.
11. Preparar la plantilla de correo de cita confirmada.
12. Preparar la plantilla de correo de cita enviada.
13. Preparar la plantilla de correo de cita rechazada.
14. Preparar la plantilla de correo de cita rechazada por el cliente.
15. Preparar la plantilla de correo de cita rechazada por el dentista.
16. Preparar para enviar correos de recordatorio de cita.
17. Preparar para enviar correos de confirmación de cita.
18. Los envíos son marcados como correctos solo con que tengan el estado queued habrá que marcarlos como en espera y
    chequear el estado de alguna manera mas tarde para actualizar. El estado de activo pasará a false y enviado a true
    en la Base de Datos cuando se verifique la correcta entrega al destinatario.,
    19.~~Preparar para seleccionar los envíos de WhatsApp y email 1 día, 2 días, 3 días y/o una semana antes de la
    cita.~~
