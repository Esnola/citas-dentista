# Citas Dentista

Aplicación Laravel para gestionar citas, pacientes y recordatorios por WhatsApp desde una sola interfaz.

## Qué incluye

- Panel principal con métricas y próximos envíos
- Gestión de pacientes y citas
- Programación de mensajes de WhatsApp
- Seguimiento de envío, entrega, lectura y respuestas de WhatsApp
- Historial de comunicaciones por cita (badge en columna Respuesta)
- Importación de datos desde Excel
- Plantillas reutilizables para mensajes
- Envío manual y envío programado
- Pruebas automáticas para las partes principales del flujo
- Backup e importación de ajustes (JSON + CSV ZIP)
- Backup e importación de la base de datos completa (JSON + CSV ZIP)
- Backup por tabla: clientes, citas, usuarios (JSON + CSV)

## Stack

- Laravel 13
- Livewire 4
- Flux UI
- Tailwind CSS 4
- PHPUnit 12
- Ponytail (lazy senior dev plugin for agents)

## Requisitos

- PHP 8.4
- Composer
- Node.js y npm
- Base de datos compatible con Laravel

## Instalación

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configura después la base de datos y el resto de variables en `.env`.

## Arranque local

```bash
php artisan migrate
php artisan serve
npm run dev
```

Si prefieres un arranque completo del entorno:

```bash
composer run dev
```

Ese comando también levanta el scheduler de Laravel con `php artisan schedule:work`, así que las tareas automáticas quedan activas en local.

## Pruebas

```bash
php artisan test --compact
```

Para una prueba concreta:

```bash
php artisan test --compact --filter=NombreDelTest
```

## Flujo principal

1. Crear o importar pacientes
2. Registrar citas o mensajes
3. Elegir una plantilla
4. Programar el envío
5. Revisar el estado y la respuesta desde la lista de citas

## Respuestas e historial

En la lista de citas, la columna `Respuesta` muestra si el paciente confirmó (verde), pidió consultas (rojo) o envió un texto. Cuando hay respuesta, el badge abre el historial completo de comunicaciones de la cita. La columna de acciones queda reservada para enviar WhatsApp, editar y eliminar.

Tres estados de respuesta:
- **Confirmada** (confirm*) — verde, icono usuario-plus
- **Consultar** (cualquier otro payload) — rojo, icono alerta
- **Sin cambio** (sin payload) — usa campo de texto

## WhatsApp

La app soporta distintos drivers de envío mediante configuración. Revisa `config/whatsapp.php` y las credenciales asociadas en `.env` para dejar activo el canal que uses.

### Auto-dispatch

El envío automático corre a las 09:00, 12:00 y 15:00 via `whatsapp:dispatch-due`. Usa lead_days configurados en `appointment_reminder_preferences`. Solo envía si la cita es futura, está activa y no ha sido enviada.

## Estructura útil

- `app/Livewire/`: componentes interactivos
- `app/Models/`: modelos de dominio
- `app/Services/WhatsApp/`: lógica de envío
- `database/migrations/`: esquema de la base de datos
- `tests/Feature/`: pruebas funcionales del flujo

## Backup e Import/Export

Accesible desde `/admin/tools`:

### Por tabla
- Clientes, citas y usuarios: exportar JSON o CSV, importar JSON o CSV

### Ajustes del sistema
- Configuración de WhatsApp, recordatorios, plantillas, retención de datos
- Exportar JSON o CSV ZIP, importar JSON (v1 + v2) o CSV ZIP

### Base de datos completa
- Exportar JSON o CSV ZIP (una tabla por archivo CSV)
- Importar desde JSON o ZIP con CSVs

## Notas

- El proyecto usa Ponytail (`@dietrichgebert/ponytail`) para que los agentes escriban el mínimo código necesario — menos líneas, menos tokens, misma seguridad.
- Si cambias frontend, recuerda ejecutar `npm run dev` o `npm run build`.
