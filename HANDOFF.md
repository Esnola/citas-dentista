# Handoff — Backup/Import/Export System

## Objective
Implement a complete backup/import/export system for the dental appointment app: settings, per-table data, and full database — in JSON and CSV formats.

## Current state
All features implemented and working. Tests passing. Documentation updated.

## Completed

### Settings merge (6 tables → 5)
- Merged `sistema_opciones` + `whatsapp_dispatch_settings` → `app_settings`
- New model: `AppSetting` with columns: `retention_period`, `dispatch_enabled`, `dispatch_hours`
- Migrations: `2026_07_12_120000_create_app_settings_table.php`, `2026_07_12_120001_merge_settings_tables.php`
- Deleted: `SistemaOpcion.php`, `WhatsAppDispatchSettings.php`
- Updated 15+ files (Livewire, Controllers, Commands, Routes, Tests, Seeders)
- Export version bumped to v2; import supports v1 backward compat

### Per-table export/import (`/admin/tools`)
- `TableBackup` Livewire component
- Export: JSON + CSV per table (clients, appointments, users)
- Import: JSON or CSV, with table selector radio buttons
- Client import uses `Client::upsertFromImport()` (phone+name dedup)
- Appointment/User import uses ID-based upsert

### Settings export/import (`/admin/tools`)
- `SettingsBackup` Livewire component
- Export: JSON or CSV ZIP (one CSV per table)
- Import: JSON (v1+v2) or ZIP with CSVs
- Artisan commands: `settings:export`, `settings:import`
- Encrypted fields (5 in whatsapp_credentials) decrypted on export, re-encrypted on import

### Full database export/import (`/admin/tools`)
- `DatabaseBackup` Livewire component
- Export: JSON (all tables) or CSV ZIP
- Import: JSON or ZIP with CSVs
- FK order respected: users → clients → appointments → messages → settings

### Admin tools page restructured
- Section 1: Exportar/Importar por tabla (TableBackup)
- Section 2: Ajustes del Sistema (SettingsBackup)
- Section 3: Toda la Base de datos (DatabaseBackup)

## Files touched

### New files
- `/Users/juan/PhpstormProjects/citasdentista/app/Models/AppSetting.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Livewire/Settings/SettingsBackup.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Livewire/Settings/DatabaseBackup.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Livewire/Settings/TableBackup.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Console/Commands/SettingsExport.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Console/Commands/SettingsImport.php`
- `/Users/juan/PhpstormProjects/citasdentista/resources/views/settings/settings-backup.blade.php`
- `/Users/juan/PhpstormProjects/citasdentista/resources/views/settings/database-backup.blade.php`
- `/Users/juan/PhpstormProjects/citasdentista/resources/views/settings/table-backup.blade.php`
- `/Users/juan/PhpstormProjects/citasdentista/database/migrations/2026_07_12_120000_create_app_settings_table.php`
- `/Users/juan/PhpstormProjects/citasdentista/database/migrations/2026_07_12_120001_merge_settings_tables.php`

### Modified files
- `/Users/juan/PhpstormProjects/citasdentista/app/Http/Controllers/Admin/ExportController.php` — added settings, settingsCsv, allJson, allCsv, clientsJson, appointmentsJson, usersJson methods
- `/Users/juan/PhpstormProjects/citasdentista/app/Providers/AppServiceProvider.php` — registered 3 new Livewire components
- `/Users/juan/PhpstormProjects/citasdentista/routes/web.php` — 11 export routes
- `/Users/juan/PhpstormProjects/citasdentista/resources/views/admin/tools/index.blade.php` — restructured with 3 sections
- `/Users/juan/PhpstormProjects/citasdentista/database/seeders/SettingsSeeder.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/app/Livewire/Settings/AppointmentCleanupSettings.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/app/Livewire/Settings/AppointmentReminderSettings.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/app/Livewire/DispatchBanner.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/app/Console/Commands/DispatchDueWhatsAppMessages.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/app/Console/Commands/PurgePastAppointments.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/app/Console/Commands/ResetClientData.php` — updated PROTECTED_TABLES
- `/Users/juan/PhpstormProjects/citasdentista/routes/console.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/tests/Feature/PurgePastAppointmentsCommandTest.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/tests/Feature/AppointmentCleanupSettingsTest.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/tests/Feature/ResetClientDataCommandTest.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/tests/Feature/WhatsAppDispatchCommandTest.php` — uses AppSetting
- `/Users/juan/PhpstormProjects/citasdentista/AGENTS.md` — updated architecture, database, backup sections
- `/Users/juan/PhpstormProjects/citasdentista/README.md` — added backup features

### Deleted files
- `/Users/juan/PhpstormProjects/citasdentista/app/Models/SistemaOpcion.php`
- `/Users/juan/PhpstormProjects/citasdentista/app/Models/WhatsAppDispatchSettings.php`

## Commands / tests
- `php artisan migrate` → both new migrations ran successfully
- `php artisan test --compact tests/Feature/PurgePastAppointmentsCommandTest.php tests/Feature/AppointmentCleanupSettingsTest.php` → 5 passed
- `vendor/bin/pint --dirty --format agent` → passed
- `php artisan settings:export` → JSON exported with v2 format
- `php artisan settings:import --force` → imported successfully
- JSON export of clients → detected existing records by ID correctly

## Blockers
- None

## Next steps
1. Test the full flow in browser: export per-table JSON → modify data → import JSON → verify
2. Test full database export → import on a clean DB
3. Test CSV import for appointments (date format handling)

## Notes for another computer
- Run `php artisan migrate` to create `app_settings` table and migrate data from old tables
- Run `php artisan view:clear` if you see stale component errors
- All Livewire components must be registered in `AppServiceProvider` — auto-discovery is disabled
- Export routes: `admin.export.clients`, `admin.export.clients-json`, `admin.export.appointments`, `admin.export.appointments-json`, `admin.export.users`, `admin.export.users-json`, `admin.export.settings`, `admin.export.settings-csv`, `admin.export.all-json`, `admin.export.all-csv`, `admin.export.database`
- Import accepts .json and .zip (CSV per table). Settings import also accepts v1 format (sistema_opciones + whatsapp_dispatch_settings keys)
- Encrypted WhatsApp credentials: decrypted on export, re-encrypted on import. If app key changes between environments, encrypted fields will fail to decrypt
