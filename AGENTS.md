# Citas Dentista — Agent Instructions

Laravel 13 app for dental appointment management with WhatsApp reminders. Livewire 4 + Flux UI + Tailwind CSS 4. PHPUnit 12. PHP 8.4. SQLite default.

## Commands

```bash
composer run dev          # full stack: server + queue + pail + vite
php artisan test --compact                              # all tests
php artisan test --compact tests/Feature/ClientManagerTest.php  # single file
php artisan test --compact --filter=testName           # single test
vendor/bin/pint --dirty --format agent                 # format PHP (run after every PHP change)
npm run build / npm run dev                             # frontend assets
```

## Architecture

- **Models**: `Client`, `Appointment`, `WhatsAppMessage`, `WhatsAppTemplate`, `User`, `AppointmentReminderPreference`
- **Livewire**: components in `app/Livewire/`, views in `resources/views/livewire/`
- **WhatsApp**: `app/Services/WhatsApp/WhatsAppSender.php` — drivers: `log` (default dev), `twilio`, `cloud_api`. Config at `config/whatsapp.php`. Twilio supports modes: `sandbox`, `sender`, `service`, `auto`.
- **Imports**: Excel via `maatwebsite/excel`, preview in Livewire `ExcelImporter`
- **Admin routes**: behind `admin` middleware, user management + security settings
- **Policies**: `ClientPolicy`, `AppointmentPolicy`, `WhatsAppMessagePolicy`, `UserPolicy`
- **Jobs**: `SendWhatsAppMessage` (queued, `QUEUE_CONNECTION=database`)

## Conventions

- Spanish field names on models: `nombre`, `apellidos`, `telefono`, `fecha`, `hora`, `enviado`, `entregado`, `activo`
- Phone normalization via `App\Traits\NormalizesPhone` trait (shared by `Client` and `WhatsAppSender`)
- Blade components: `resources/views/components/iconos/`, `botones/`, `formularios/`, `navegacion/`
- Routes are view-based for most pages (Livewire embedded in Blade views), except admin CRUD and webhooks

## Testing

- Tests use SQLite in-memory (`phpunit.xml` sets `DB_DATABASE=:memory:`)
- Use factories for models; `UserFactory` exists, check for custom states
- PHPUnit classes only — convert any Pest tests to PHPUnit
- Test WhatsApp with `log` driver (default in `.env.example`)

## Gotchas

- Run `vendor/bin/pint --dirty --format agent` after any PHP edit — CI expects formatted code
- If you see `ViteException: Unable to locate file in Vite manifest`, run `npm run build`
- `WHATSAPP_DRIVER=log` is the safe default — never hardcode Twilio/Cloud API credentials
- `composer run dev` spawns 4 processes concurrently via `npx concurrently`
- Database migrations are timestamped with `2026_06_*` dates — newer files sort correctly
- Custom Blade components use Spanish names: `<x-iconos.whatsapp>`, `<x-botones.accion>`, `<x-formularios.input>`
- Livewire components are mounted via `<livewire:component-name>` in Blade views — routes point to views, not controllers
