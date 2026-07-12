# Citas Dentista — Agent Instructions

Laravel 13 app for dental appointment management with WhatsApp reminders. Livewire 4 + Flux UI + Tailwind CSS 4. PHPUnit 12. PHP 8.4. MySQL production, SQLite for tests.

## Commands

```bash
composer run dev          # full stack: server + queue + scheduler + pail + vite
php artisan test --compact                              # all tests
php artisan test --compact tests/Feature/ClientManagerTest.php  # single file
php artisan test --compact --filter=testName           # single test
vendor/bin/pint --dirty --format agent                 # format PHP (run after every PHP change)
npm run build / npm run dev                             # frontend assets
```

## Architecture

- **Models**: `Client`, `Appointment`, `WhatsAppMessage`, `WhatsAppTemplate`, `User`, `AppointmentReminderPreference`, `AppSetting`, `WhatsAppCredential`, `WhatsAppSenderNumber`, `TwilioContentTemplate`, `AppointmentChange`
- **Livewire**: components in `app/Livewire/`, views in `resources/views/livewire/`
- **Settings Livewire**: `AppointmentCleanupSettings`, `AppointmentReminderSettings`, `TwilioCredentialSettings`, `TwilioContentTemplateSettings`, `SettingsBackup` (settings import/export), `DatabaseBackup` (full DB import/export), `TableBackup` (per-table import/export)
- **WhatsApp**: `app/Services/WhatsApp/WhatsAppSender.php` — drivers: `log` (default dev), `twilio`, `cloud_api`. Config at `config/whatsapp.php`. Twilio supports modes: `sandbox`, `sender`, `service`, `auto`.
- **Imports**: Excel via `maatwebsite/excel`, preview in Livewire `ExcelImporter`
- **Admin routes**: behind `admin` middleware, user management + security settings + tools
- **Policies**: `ClientPolicy`, `AppointmentPolicy`, `WhatsAppMessagePolicy`, `UserPolicy`
- **Jobs**: `SendWhatsAppMessage` (queued, `QUEUE_CONNECTION=database`)

## Database

### Tables (active)
`users`, `clients`, `appointments`, `appointment_changes`, `whatsapp_messages`, `whatsapp_credentials`, `whatsapp_sender_numbers`, `twilio_content_templates`, `appointment_reminder_preferences`, `app_settings`, `login_history`, `password_reset_tokens`, `sessions`, `cache`, `jobs`, `job_batches`, `failed_jobs`

### Settings tables
- `app_settings` — single-row: `retention_period`, `dispatch_enabled`, `dispatch_hours` (merged from old `sistema_opciones` + `whatsapp_dispatch_settings`)
- `appointment_reminder_preferences` — multi-row: channel + lead_days + enabled
- `whatsapp_credentials` — API config with 5 encrypted fields (`account_sid`, `auth_token`, `api_key_sid`, `api_key_secret`, `cloud_api_access_token`)
- `whatsapp_sender_numbers` — FK to `whatsapp_credentials`, cascade delete
- `twilio_content_templates` — unique `content_sid`, JSON `content_variables`

## Backup / Import / Export

### Per-table (`/admin/tools`)
- Export: JSON or CSV per table (clients, appointments, users)
- Import: JSON or CSV, select target table, upsert by ID (or phone+name for clients)

### Settings (`/admin/tools`)
- Export: JSON or CSV ZIP
- Import: JSON (v1 + v2 backward compat) or CSV ZIP

### Full database (`/admin/tools`)
- Export: JSON (all tables) or CSV ZIP (one CSV per table)
- Import: JSON or ZIP with CSVs, FK order respected

### Artisan commands
```bash
php artisan settings:export {path?}    # export settings to JSON
php artisan settings:import {path?}    # import settings from JSON (supports v1 + v2)
```

## Conventions

- Spanish field names on models: `nombre`, `apellidos`, `telefono`, `fecha`, `hora`, `enviado`, `entregado`, `activo`
- Phone normalization via `App\Traits\NormalizesPhone` trait (shared by `Client` and `WhatsAppSender`)
- Blade components: `resources/views/components/iconos/`, `botones/`, `formularios/`, `navegacion/`
- Routes are view-based for most pages (Livewire embedded in Blade views), except admin CRUD and webhooks
- Livewire components registered manually in `AppServiceProvider` via `Livewire::component()`

## Testing

- Tests use SQLite in-memory (`phpunit.xml` sets `DB_DATABASE=:memory:`)
- Use factories for models; `UserFactory` exists, check for custom states
- PHPUnit classes only — convert any Pest tests to PHPUnit
- Test WhatsApp with `log` driver (default in `.env.example`)

## Gotchas

- Run `vendor/bin/pint --dirty --format agent` after any PHP edit — CI expects formatted code
- If you see `ViteException: Unable to locate file in Vite manifest`, run `npm run build`
- `WHATSAPP_DRIVER=log` is the safe default — never hardcode Twilio/Cloud API credentials
- `composer run dev` spawns 5 processes concurrently via `npx concurrently`
- Database migrations are timestamped with `2026_06_*` and `2026_07_*` dates — newer files sort correctly
- MySQL compatibility: never use `->after()` in `Schema::create()` (only works in `Schema::table()`)
- MySQL compatibility: foreign keys in `Schema::create()` require the referenced table to already exist — check migration order or use `foreignId()->index()` without `constrained()` and add FKs later
- Eloquent pluralizes model names for table lookup — set `protected $table` if the table name doesn't match
- Custom Blade components use Spanish names: `<x-iconos.whatsapp>`, `<x-botones.accion>`, `<x-formularios.input>`
- Livewire components are mounted via `<livewire:component-name>` in Blade views — routes point to views, not controllers
- Export/import JSON uses v2 format (app_settings as single table). Import supports v1 backward compat (sistema_opciones + whatsapp_dispatch_settings)
