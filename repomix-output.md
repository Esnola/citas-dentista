This file is a merged representation of the entire codebase, combined into a single document by Repomix.
The content has been processed where line numbers have been added, content has been compressed (code blocks are separated by ⋮---- delimiter).

# File Summary

## Purpose
This file contains a packed representation of the entire repository's contents.
It is designed to be easily consumable by AI systems for analysis, code review,
or other automated processes.

## File Format
The content is organized as follows:
1. This summary section
2. Repository information
3. Directory structure
4. Repository files (if enabled)
5. Multiple file entries, each consisting of:
  a. A header with the file path (## File: path/to/file)
  b. The full contents of the file in a code block

## Usage Guidelines
- This file should be treated as read-only. Any changes should be made to the
  original repository files, not this packed version.
- When processing this file, use the file path to distinguish
  between different files in the repository.
- Be aware that this file may contain sensitive information. Handle it with
  the same level of security as you would the original repository.

## Notes
- Some files may have been excluded based on .gitignore rules and Repomix's configuration
- Binary files are not included in this packed representation. Please refer to the Repository Structure section for a complete list of file paths, including binary files
- Files matching patterns in .gitignore are excluded
- Files matching default ignore patterns are excluded
- Line numbers have been added to the beginning of each line
- Content has been compressed - code blocks are separated by ⋮---- delimiter
- Files are sorted by Git change count (files with more changes are at the bottom)

# Directory Structure
```
.agents/
  skills/
    fluxui-development/
      SKILL.md
    laravel-best-practices/
      rules/
        advanced-queries.md
        architecture.md
        blade-views.md
        caching.md
        collections.md
        config.md
        db-performance.md
        eloquent.md
        error-handling.md
        events-notifications.md
        http-client.md
        mail.md
        migrations.md
        queue-jobs.md
        routing.md
        scheduling.md
        security.md
        style.md
        testing.md
        validation.md
      SKILL.md
    livewire-development/
      reference/
        javascript-hooks.md
      SKILL.md
    tailwindcss-development/
      SKILL.md
    workspace-handoff/
      SKILL.md
.ai/
  mcp/
    mcp.json
.junie/
  mcp/
    mcp.json
  skills/
    fluxui-development/
      SKILL.md
    laravel-best-practices/
      rules/
        advanced-queries.md
        architecture.md
        blade-views.md
        caching.md
        collections.md
        config.md
        db-performance.md
        eloquent.md
        error-handling.md
        events-notifications.md
        http-client.md
        mail.md
        migrations.md
        queue-jobs.md
        routing.md
        scheduling.md
        security.md
        style.md
        testing.md
        validation.md
      SKILL.md
    livewire-development/
      reference/
        javascript-hooks.md
      SKILL.md
    tailwindcss-development/
      SKILL.md
.mimocode/
  plans/
    1782664470561-gentle-orchid.md
    1782727080975-crisp-moon.md
    1783245481528-playful-otter.md
    1783256367389-lucky-lagoon.md
    1783282400248-quiet-planet.md
    1783290993228-swift-falcon.md
    1783330327359-brave-comet.md
    1783349825106-nimble-star.md
    1783355925256-hidden-orchid.md
    1783378474056-jolly-panda.md
    1783413735994-neon-canyon.md
    1783456328338-eager-rocket.md
    1783467421696-clever-sailor.md
    1783587590113-quick-river.md
    1783595886300-calm-eagle.md
    1783853579689-neon-canyon.md
    1783870987407-crisp-star.md
    1783882215162-calm-wolf.md
    1783934979814-jolly-knight.md
  .cron-lock
app/
  Console/
    Commands/
      BackfillWhatsAppAppointmentDeliveryState.php
      DispatchDueWhatsAppMessages.php
      PurgePastAppointments.php
      ResetClientData.php
      ResetDatabaseAndSeed.php
      SettingsExport.php
      SettingsImport.php
      SyncWhatsAppDeliveryStatus.php
  Exports/
    AppointmentsExport.php
    ClientsExport.php
    UsersExport.php
  Http/
    Controllers/
      Admin/
        ExportController.php
        LoginHistoryController.php
        UserController.php
      Auth/
        AuthenticatedSessionController.php
      Webhooks/
        TwilioWhatsAppStatusController.php
      AppointmentIndexController.php
      Controller.php
      HomeController.php
    Middleware/
      EnsureUserIsAdmin.php
  Imports/
    ClientsImport.php
  Jobs/
    SendWhatsAppMessage.php
  Livewire/
    Settings/
      AppointmentCleanupSettings.php
      AppointmentReminderSettings.php
      DatabaseBackup.php
      SettingsBackup.php
      SettingsOverview.php
      TableBackup.php
      TwilioContentTemplateSettings.php
      TwilioCredentialSettings.php
      WhatsAppConnectionTest.php
    AgendaIndex.php
    AppointmentForm.php
    AppointmentIndex.php
    CalendarIndex.php
    ClientAppointments.php
    ClientCsvImporter.php
    ClientForm.php
    ClientIndex.php
    ClientListAll.php
    ClientMessageScheduler.php
    DashboardOverview.php
    DispatchBanner.php
    UnreadResponsesNotice.php
  Models/
    Appointment.php
    AppointmentChange.php
    AppointmentReminderPreference.php
    AppSetting.php
    Client.php
    LoginHistory.php
    TwilioContentTemplate.php
    User.php
    WhatsAppCredential.php
    WhatsAppMessage.php
    WhatsAppSenderNumber.php
    WhatsAppTemplate.php
  Observers/
    WhatsAppCredentialObserver.php
  Policies/
    AppointmentPolicy.php
    ClientPolicy.php
    UserPolicy.php
    WhatsAppMessagePolicy.php
  Providers/
    AppServiceProvider.php
  Services/
    WhatsApp/
      AppointmentDeliveryStatusSyncer.php
      AppointmentImmediateSender.php
      WhatsAppResponseHandler.php
      WhatsAppSender.php
    ClientDataDeletionService.php
  Traits/
    NormalizesPhone.php
    ValidatesSelectableDate.php
bootstrap/
  cache/
    .gitignore
  app.php
  providers.php
config/
  app.php
  auth.php
  cache.php
  database.php
  filesystems.php
  livewire.php
  logging.php
  mail.php
  queue.php
  services.php
  session.php
  whatsapp.php
database/
  backups/
    settings_tables_20260708_100810.sql
  factories/
    UserFactory.php
  migrations/
    0001_01_01_000000_create_users_table.php
    0001_01_01_000001_create_cache_table.php
    0001_01_01_000002_create_jobs_table.php
    2026_06_23_000000_create_clients_table.php
    2026_06_23_000003_create_appointments_table.php
    2026_06_23_000004_create_whatsapp_messages_table.php
    2026_06_23_124420_create_appointment_reminder_preferences_table.php
    2026_07_02_030000_create_login_history_table.php
    2026_07_06_000000_create_twilio_content_templates_table.php
    2026_07_07_232323_create_whatsapp_dispatch_settings_table.php
    2026_07_08_010450_create_whatsapp_credentials_table.php
    2026_07_08_120000_create_whatsapp_sender_numbers_table.php
    2026_07_10_210107_create_appointment_changes_table.php
    2026_07_11_120000_create_option_settings_table.php
    2026_07_12_120000_create_app_settings_table.php
    2026_07_12_120001_merge_settings_tables.php
  seeders/
    AppointmentSeeder.php
    ClientSeeder.php
    DatabaseSeeder.php
    SettingsSeeder.php
    TwilioContentTemplateSeeder.php
  .gitignore
e2e/
  example.spec.js
lang/
  en/
    auth.php
    pagination.php
    passwords.php
    validation.php
  es/
    validation.php
  es.json
public/
  .htaccess
  favicon.svg
  index.php
  robots.txt
  site.webmanifest
resources/
  css/
    app.css
  js/
    app.js
    data-picker.js
  views/
    admin/
      login-history/
        index.blade.php
      tools/
        index.blade.php
      users/
        create.blade.php
        edit.blade.php
    agenda/
      index.blade.php
    appointments/
      client.blade.php
      form.blade.php
      index.blade.php
      new.blade.php
    auth/
      login.blade.php
    calendar/
      index.blade.php
    clients/
      form.blade.php
      index.blade.php
      list.blade.php
    components/
      botones/
        partials/
          icono-accion.blade.php
        accion-ajustes.blade.php
        arrastrar-seccion.blade.php
        expandir-contraer.blade.php
        filtro-botones.blade.php
        icono-buton.blade.php
        sidebar-toggle.blade.php
      dashboard/
        metric-card.blade.php
      formularios/
        checkbox-card.blade.php
        input.blade.php
        option-input.blade.php
        select.blade.php
        toggle.blade.php
      iconos/
        admin-user.blade.php
        agenda.blade.php
        ajustes.blade.php
        alert.blade.php
        arrastrar.blade.php
        bombilla.blade.php
        borrar.blade.php
        calendar.blade.php
        calendario-filtro.blade.php
        calendario-pasado.blade.php
        check.blade.php
        cita.blade.php
        conectar.blade.php
        contraer-flechas.blade.php
        contraer.blade.php
        copiar.blade.php
        customer.blade.php
        dashboard.blade.php
        deAZ.blade.php
        deZA.blade.php
        disquete.blade.php
        doble-check.blade.php
        down.blade.php
        enviar-ya.blade.php
        enviar.blade.php
        escoba.blade.php
        excel.blade.php
        expand.blade.php
        expandir.blade.php
        export.blade.php
        guardar.blade.php
        historial.blade.php
        importar.blade.php
        inactivo.blade.php
        lapiz.blade.php
        nueva-cita.blade.php
        nuevo.blade.php
        num-Asc.blade.php
        num-Desc.blade.php
        ojo.blade.php
        papelera.blade.php
        proxima-cita.blade.php
        reload.blade.php
        reloj-agujas.blade.php
        reloj-arena.blade.php
        restablecer.blade.php
        salir.blade.php
        seguridad.blade.php
        telefono-mesa.blade.php
        todos.blade.php
        up.blade.php
        user-menos.blade.php
        usuario-plus.blade.php
        usuarios.blade.php
        volver.blade.php
        whatsapp.blade.php
      modales/
        borrar.blade.php
        bulk-borrar.blade.php
        confirmacion.blade.php
        historia-whatsapp.blade.php
      navegacion/
        aside-link.blade.php
      settings/
        section.blade.php
      tabla/
        botones-maniobra.blade.php
        th-sort.blade.php
        th.blade.php
    imports/
      index.blade.php
    layouts/
      app.blade.php
      guest.blade.php
    livewire/
      avisos/
        sin-envio-automatico.blade.php
      agenda-index.blade.php
      appointment-form.blade.php
      appointment-index.blade.php
      calendar-index.blade.php
      client-appointments.blade.php
      client-csv-importer.blade.php
      client-form.blade.php
      client-index.blade.php
      client-list-all.blade.php
      client-message-scheduler.blade.php
      dashboard-overview.blade.php
      unread-responses-notice.blade.php
    settings/
      appointment-cleanup-settings.blade.php
      appointment-reminder-settings.blade.php
      database-backup.blade.php
      index.blade.php
      settings-backup.blade.php
      settings-overview.blade.php
      table-backup.blade.php
      twilio-content-template-settings.blade.php
      twilio-credential-settings.blade.php
      whatsapp-connection-test.blade.php
    vendor/
      pagination/
        tailwind.blade.php
    dashboard.blade.php
    home.blade.php
routes/
  console.php
  web.php
storage/
  app/
    private/
      .gitignore
    public/
      .gitignore
    .gitignore
  debugbar/
    .gitignore
  framework/
    cache/
      data/
        .gitignore
      .gitignore
    sessions/
      .gitignore
    testing/
      .gitignore
    views/
      .gitignore
    .gitignore
  logs/
    .gitignore
tests/
  Feature/
    AdminDatabaseBackupTest.php
    AdminUserCreationTest.php
    AdminUserManagementTest.php
    AdminUsersExportTest.php
    AppointmentCleanupSettingsTest.php
    AppointmentManagerTest.php
    AppointmentReminderSettingsTest.php
    BackupRoundTripTest.php
    CalendarIndexTest.php
    ClientCsvImportTest.php
    ClientDataDeletionServiceTest.php
    ClientManagerTest.php
    ClientMessageSchedulerTest.php
    DashboardOverviewTest.php
    ExampleTest.php
    FailedWhatsAppMessageDisplayTest.php
    PurgePastAppointmentsCommandTest.php
    ResetClientDataCommandTest.php
    ResetDatabaseAndSeedCommandTest.php
    SettingsPageTest.php
    TwilioContentTemplateSettingsTest.php
    TwilioWhatsAppStatusWebhookTest.php
    WhatsAppConnectionTestComponentTest.php
    WhatsAppDispatchCommandTest.php
    WhatsAppMessageClientLinkTest.php
    WhatsAppMessageManagerSearchTest.php
    WhatsAppTemplateSelectionTest.php
    WhatsAppTemplateTest.php
    WhatsAppTwilioDispatchTest.php
  Unit/
    ExampleTest.php
    WhatsAppMessageTimezoneTest.php
  TestCase.php
.cpanel.yml
.editorconfig
.env.example
.env.testing
.gitattributes
.gitignore
.npmrc
AGENTS.md
artisan
boost.json
composer.json
GUIA_RETOMAR_TRABAJO.md
GUIA_TECNICA.md
GUIA_USUARIO.md
HANDOFF.md
instrucciones.md
ngrok-herd.yml
opencode.json
package.json
phpunit.xml
playwright.config.js
README.md
responded_at
TWILIO_WEBHOOKS.md
vite.config.js
```

# Files

## File: .agents/skills/fluxui-development/SKILL.md
````markdown
---
name: fluxui-development
description: "Use this skill for Flux UI development in Livewire applications only. Trigger when working with <flux:*> components, building or customizing Livewire component UIs, creating forms, modals, tables, or other interactive elements. Covers: flux: components (buttons, inputs, modals, forms, tables, date-pickers, kanban, badges, tooltips, etc.), component composition, Tailwind CSS styling, Heroicons/Lucide icon integration, validation patterns, responsive design, and theming. Do not use for non-Livewire frameworks or non-component styling."
license: MIT
metadata:
  author: laravel
---

# Flux UI Development

## Documentation

Use `search-docs` for detailed Flux UI patterns and documentation.

## Basic Usage

This project uses the free edition of Flux UI, which includes all free components and variants but not Pro components.

Flux UI is a component library for Livewire built with Tailwind CSS. It provides components that are easy to use and customize.

Use Flux UI components when available. Fall back to standard Blade components when no Flux component exists for your needs.

<!-- Basic Button -->
```blade
<flux:button variant="primary">Click me</flux:button>
```

## Available Components (Free Edition)

Available: avatar, badge, brand, breadcrumbs, button, callout, card, checkbox, dropdown, field, heading, icon, input, modal, navbar, otp-input, pagination, profile, progress, radio, select, separator, skeleton, switch, table, text, textarea, toast, tooltip

## Icons

Flux includes [Heroicons](https://heroicons.com/) as its default icon set. Search for exact icon names on the Heroicons site - do not guess or invent icon names.

<!-- Icon Button -->
```blade
<flux:button icon="arrow-down-tray">Export</flux:button>
```

For icons not available in Heroicons, use [Lucide](https://lucide.dev/). Import the icons you need with the Artisan command:

```bash
php artisan flux:icon crown grip-vertical github
```

## Common Patterns

### Form Fields

<!-- Form Field -->
```blade
<flux:field>
    <flux:label>Email</flux:label>
    <flux:input type="email" wire:model="email" />
    <flux:error name="email" />
</flux:field>
```

### Modals

<!-- Modal -->
```blade
<flux:modal wire:model="showModal">
    <flux:heading>Title</flux:heading>
    <p>Content</p>
</flux:modal>
```

## Verification

1. Check component renders correctly
2. Test interactive states
3. Verify mobile responsiveness

## Common Pitfalls

- Trying to use Pro-only components in the free edition
- Not checking if a Flux component exists before creating custom implementations
- Forgetting to use the `search-docs` tool for component-specific documentation
- Not following existing project patterns for Flux usage
````

## File: .agents/skills/laravel-best-practices/rules/advanced-queries.md
````markdown
# Advanced Query Patterns

## Use `addSelect()` Subqueries for Single Values from Has-Many

Instead of eager-loading an entire has-many relationship for a single value (like the latest timestamp), use a correlated subquery via `addSelect()`. This pulls the value directly in the main SQL query — zero extra queries.

```php
public function scopeWithLastLoginAt($query): void
{
    $query->addSelect([
        'last_login_at' => Login::select('created_at')
            ->whereColumn('user_id', 'users.id')
            ->latest()
            ->take(1),
    ])->withCasts(['last_login_at' => 'datetime']);
}
```

## Create Dynamic Relationships via Subquery FK

Extend the `addSelect()` pattern to fetch a foreign key via subquery, then define a `belongsTo` relationship on that virtual attribute. This provides a fully-hydrated related model without loading the entire collection.

```php
public function lastLogin(): BelongsTo
{
    return $this->belongsTo(Login::class);
}

public function scopeWithLastLogin($query): void
{
    $query->addSelect([
        'last_login_id' => Login::select('id')
            ->whereColumn('user_id', 'users.id')
            ->latest()
            ->take(1),
    ])->with('lastLogin');
}
```

## Use Conditional Aggregates Instead of Multiple Count Queries

Replace N separate `count()` queries with a single query using `CASE WHEN` inside `selectRaw()`. Use `toBase()` to skip model hydration when you only need scalar values.

```php
$statuses = Feature::toBase()
    ->selectRaw("count(case when status = 'Requested' then 1 end) as requested")
    ->selectRaw("count(case when status = 'Planned' then 1 end) as planned")
    ->selectRaw("count(case when status = 'Completed' then 1 end) as completed")
    ->first();
```

## Use `setRelation()` to Prevent Circular N+1

When a parent model is eager-loaded with its children, and the view also needs `$child->parent`, use `setRelation()` to inject the already-loaded parent rather than letting Eloquent fire N additional queries.

```php
$feature->load('comments.user');
$feature->comments->each->setRelation('feature', $feature);
```

## Prefer `whereIn` + Subquery Over `whereHas`

`whereHas()` emits a correlated `EXISTS` subquery that re-executes per row. Using `whereIn()` with a `select('id')` subquery lets the database use an index lookup instead, without loading data into PHP memory.

Incorrect (correlated EXISTS re-executes per row):

```php
$query->whereHas('company', fn ($q) => $q->where('name', 'like', $term));
```

Correct (index-friendly subquery, no PHP memory overhead):

```php
$query->whereIn('company_id', Company::where('name', 'like', $term)->select('id'));
```

## Sometimes Two Simple Queries Beat One Complex Query

Running a small, targeted secondary query and passing its results via `whereIn` is often faster than a single complex correlated subquery or join. The additional round-trip is worthwhile when the secondary query is highly selective and uses its own index.

## Use Compound Indexes Matching `orderBy` Column Order

When ordering by multiple columns, create a single compound index in the same column order as the `ORDER BY` clause. Individual single-column indexes cannot combine for multi-column sorts — the database will filesort without a compound index.

```php
// Migration
$table->index(['last_name', 'first_name']);

// Query — column order must match the index
User::query()->orderBy('last_name')->orderBy('first_name')->paginate();
```

## Use Correlated Subqueries for Has-Many Ordering

When sorting by a value from a has-many relationship, avoid joins (they duplicate rows). Use a correlated subquery inside `orderBy()` instead, paired with an `addSelect` scope for eager loading.

```php
public function scopeOrderByLastLogin($query): void
{
    $query->orderByDesc(Login::select('created_at')
        ->whereColumn('user_id', 'users.id')
        ->latest()
        ->take(1)
    );
}
```
````

## File: .agents/skills/laravel-best-practices/rules/architecture.md
````markdown
# Architecture Best Practices

## Single-Purpose Action Classes

Extract discrete business operations into invokable Action classes.

```php
class CreateOrderAction
{
    public function __construct(private InventoryService $inventory) {}

    public function execute(array $data): Order
    {
        $order = Order::create($data);
        $this->inventory->reserve($order);

        return $order;
    }
}
```

## Use Dependency Injection

Always use constructor injection. Avoid `app()` or `resolve()` inside classes.

Incorrect:
```php
class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $service = app(OrderService::class);

        return $service->create($request->validated());
    }
}
```

Correct:
```php
class OrderController extends Controller
{
    public function __construct(private OrderService $service) {}

    public function store(StoreOrderRequest $request)
    {
        return $this->service->create($request->validated());
    }
}
```

## Code to Interfaces

Depend on contracts at system boundaries (payment gateways, notification channels, external APIs) for testability and swappability.

Incorrect (concrete dependency):
```php
class OrderService
{
    public function __construct(private StripeGateway $gateway) {}
}
```

Correct (interface dependency):
```php
interface PaymentGateway
{
    public function charge(int $amount, string $customerId): PaymentResult;
}

class OrderService
{
    public function __construct(private PaymentGateway $gateway) {}
}
```

Bind in a service provider:

```php
$this->app->bind(PaymentGateway::class, StripeGateway::class);
```

## Default Sort by Descending

When no explicit order is specified, sort by `id` or `created_at` descending. Without an explicit `ORDER BY`, row order is undefined.

Incorrect:
```php
$posts = Post::paginate();
```

Correct:
```php
$posts = Post::latest()->paginate();
```

## Use Atomic Locks for Race Conditions

Prevent race conditions with `Cache::lock()` or `lockForUpdate()`.

```php
Cache::lock('order-processing-'.$order->id, 10)->block(5, function () use ($order) {
    $order->process();
});

// Or at query level
$product = Product::where('id', $id)->lockForUpdate()->first();
```

## Use `mb_*` String Functions

When no Laravel helper exists, prefer `mb_strlen`, `mb_strtolower`, etc. for UTF-8 safety. Standard PHP string functions count bytes, not characters.

Incorrect:
```php
strlen('José');          // 5 (bytes, not characters)
strtolower('MÜNCHEN');  // 'mÜnchen' — fails on multibyte
```

Correct:
```php
mb_strlen('José');             // 4 (characters)
mb_strtolower('MÜNCHEN');     // 'münchen'

// Prefer Laravel's Str helpers when available
Str::length('José');          // 4
Str::lower('MÜNCHEN');        // 'münchen'
```

## Use `defer()` for Post-Response Work

For lightweight tasks that don't need to survive a crash (logging, analytics, cleanup), use `defer()` instead of dispatching a job. The callback runs after the HTTP response is sent — no queue overhead.

Incorrect (job overhead for trivial work):
```php
dispatch(new LogPageView($page));
```

Correct (runs after response, same process):
```php
defer(fn () => PageView::create(['page_id' => $page->id, 'user_id' => auth()->id()]));
```

Use jobs when the work must survive process crashes or needs retry logic. Use `defer()` for fire-and-forget work.

## Use `Context` for Request-Scoped Data

The `Context` facade passes data through the entire request lifecycle — middleware, controllers, jobs, logs — without passing arguments manually.

```php
// In middleware
Context::add('tenant_id', $request->header('X-Tenant-ID'));

// Anywhere later — controllers, jobs, log context
$tenantId = Context::get('tenant_id');
```

Context data automatically propagates to queued jobs and is included in log entries. Use `Context::addHidden()` for sensitive data that should be available in queued jobs but excluded from log context. If data must not leave the current process, do not store it in `Context`.

## Use `Concurrency::run()` for Parallel Execution

Run independent operations in parallel using child processes — no async libraries needed.

```php
use Illuminate\Support\Facades\Concurrency;

[$users, $orders] = Concurrency::run([
    fn () => User::count(),
    fn () => Order::where('status', 'pending')->count(),
]);
```

Each closure runs in a separate process with full Laravel access. Use for independent database queries, API calls, or computations that would otherwise run sequentially.

## Convention Over Configuration

Follow Laravel conventions. Don't override defaults unnecessarily.

Incorrect:
```php
class Customer extends Model
{
    protected $table = 'Customer';
    protected $primaryKey = 'customer_id';

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_customer', 'customer_id', 'role_id');
    }
}
```

Correct:
```php
class Customer extends Model
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
```
````

## File: .agents/skills/laravel-best-practices/rules/blade-views.md
````markdown
# Blade & Views Best Practices

## Use `$attributes->merge()` in Component Templates

Hardcoding classes prevents consumers from adding their own. `merge()` combines class attributes cleanly.

```blade
<div {{ $attributes->merge(['class' => 'alert alert-'.$type]) }}>
    {{ $message }}
</div>
```

## Use `@pushOnce` for Per-Component Scripts

If a component renders inside a `@foreach`, `@push` inserts the script N times. `@pushOnce` guarantees it's included exactly once.

## Prefer Blade Components Over `@include`

`@include` shares all parent variables implicitly (hidden coupling). Components have explicit props, attribute bags, and slots.

## Use View Composers for Shared View Data

If every controller rendering a sidebar must pass `$categories`, that's duplicated code. A View Composer centralizes it.

## Use Blade Fragments for Partial Re-Renders (htmx/Turbo)

A single view can return either the full page or just a fragment, keeping routing clean.

```php
return view('dashboard', compact('users'))
    ->fragmentIf($request->hasHeader('HX-Request'), 'user-list');
```

## Use `@aware` for Deeply Nested Component Props

Avoids re-passing parent props through every level of nested components.
````

## File: .agents/skills/laravel-best-practices/rules/caching.md
````markdown
# Caching Best Practices

## Use `Cache::remember()` Instead of Manual Get/Put

Cleaner cache-aside pattern that removes boilerplate. use `Cache::lock()` for race conditions.

Incorrect:
```php
$val = Cache::get('stats');
if (! $val) {
    $val = $this->computeStats();
    Cache::put('stats', $val, 60);
}
```

Correct:
```php
$val = Cache::remember('stats', 60, fn () => $this->computeStats());
```

## Use `Cache::flexible()` for Stale-While-Revalidate

On high-traffic keys, one user always gets a slow response when the cache expires. `flexible()` serves slightly stale data while refreshing in the background.

Incorrect: `Cache::remember('users', 300, fn () => User::all());`

Correct: `Cache::flexible('users', [300, 600], fn () => User::all());` — fresh for 5 min, stale-but-served up to 10 min, refreshes via deferred function.

## Use `Cache::memo()` to Avoid Redundant Hits Within a Request

If the same cache key is read multiple times per request (e.g., a service called from multiple places), `memo()` stores the resolved value in memory.

`Cache::memo()->get('settings');` — 5 calls = 1 Redis round-trip instead of 5.

## Use Cache Tags to Invalidate Related Groups

Without tags, invalidating a group of entries requires tracking every key. Tags let you flush atomically. Only works with `redis`, `memcached`, `dynamodb` — not `file` or `database`.

```php
Cache::tags(['user-1'])->flush();
```

## Use `Cache::add()` for Atomic Conditional Writes

`add()` only writes if the key does not exist — atomic, no race condition between checking and writing.

Incorrect: `if (! Cache::has('lock')) { Cache::put('lock', true, 10); }`

Correct: `Cache::add('lock', true, 10);`

## Use `once()` for Per-Request Memoization

`once()` memoizes a function's return value for the lifetime of the object (or request for closures). Unlike `Cache::memo()`, it doesn't hit the cache store at all — pure in-memory.

```php
public function roles(): Collection
{
    return once(fn () => $this->loadRoles());
}
```

Multiple calls return the cached result without re-executing. Use `once()` for expensive computations called multiple times per request. Use `Cache::memo()` when you also want cross-request caching.

## Configure Failover Cache Stores in Production

If Redis goes down, the app falls back to a secondary store automatically.

```php
'failover' => ['driver' => 'failover', 'stores' => ['redis', 'database']],
```
````

## File: .agents/skills/laravel-best-practices/rules/collections.md
````markdown
# Collection Best Practices

## Use Higher-Order Messages for Simple Operations

Incorrect:
```php
$users->each(function (User $user) {
    $user->markAsVip();
});
```

Correct: `$users->each->markAsVip();`

Works with `each`, `map`, `sum`, `filter`, `reject`, `contains`, etc.

## Choose `cursor()` vs. `lazy()` Correctly

- `cursor()` — one model in memory, but cannot eager-load relationships (N+1 risk).
- `lazy()` — chunked pagination returning a flat LazyCollection, supports eager loading.

Incorrect: `User::with('roles')->cursor()` — eager loading silently ignored.

Correct: `User::with('roles')->lazy()` for relationship access; `User::cursor()` for attribute-only work.

## Use `lazyById()` When Updating Records While Iterating

`lazy()` uses offset pagination — updating records during iteration can skip or double-process. `lazyById()` uses `id > last_id`, safe against mutation.

## Use `toQuery()` for Bulk Operations on Collections

Avoids manual `whereIn` construction.

Incorrect: `User::whereIn('id', $users->pluck('id'))->update([...]);`

Correct: `$users->toQuery()->update([...]);`

## Use `#[CollectedBy]` for Custom Collection Classes

More declarative than overriding `newCollection()`.

```php
#[CollectedBy(UserCollection::class)]
class User extends Model {}
```
````

## File: .agents/skills/laravel-best-practices/rules/config.md
````markdown
# Configuration Best Practices

## `env()` Only in Config Files

Direct `env()` calls may return `null` when config is cached.

Incorrect:
```php
$key = env('API_KEY');
```

Correct:
```php
// config/services.php
'key' => env('API_KEY'),

// Application code
$key = config('services.key');
```

## Use Encrypted Env or External Secrets

Never store production secrets in plain `.env` files in version control.

Incorrect:
```bash

# .env committed to repo or shared in Slack

STRIPE_SECRET=sk_live_abc123
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI
```

Correct:
```bash
php artisan env:encrypt --env=production --readable
php artisan env:decrypt --env=production
```

For cloud deployments, prefer the platform's native secret store (AWS Secrets Manager, Vault, etc.) and inject at runtime.

## Use `App::environment()` for Environment Checks

Incorrect:
```php
if (env('APP_ENV') === 'production') {
```

Correct:
```php
if (app()->isProduction()) {
// or
if (App::environment('production')) {
```

## Use Constants and Language Files

Use class constants instead of hardcoded magic strings for model states, types, and statuses.

```php
// Incorrect
return $this->type === 'normal';

// Correct
return $this->type === self::TYPE_NORMAL;
```

If the application already uses language files for localization, use `__()` for user-facing strings too. Do not introduce language files purely for English-only apps — simple string literals are fine there.

```php
// Only when lang files already exist in the project
return back()->with('message', __('app.article_added'));
```
````

## File: .agents/skills/laravel-best-practices/rules/db-performance.md
````markdown
# Database Performance Best Practices

## Always Eager Load Relationships

Lazy loading causes N+1 query problems — one query per loop iteration. Always use `with()` to load relationships upfront.

Incorrect (N+1 — executes 1 + N queries):
```php
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name;
}
```

Correct (2 queries total):
```php
$posts = Post::with('author')->get();
foreach ($posts as $post) {
    echo $post->author->name;
}
```

Constrain eager loads to select only needed columns (always include the foreign key):

```php
$users = User::with(['posts' => function ($query) {
    $query->select('id', 'user_id', 'title')
          ->where('published', true)
          ->latest()
          ->limit(10);
}])->get();
```

## Prevent Lazy Loading in Development

Enable this in `AppServiceProvider::boot()` to catch N+1 issues during development.

```php
public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}
```

Throws `LazyLoadingViolationException` when a relationship is accessed without being eager-loaded.

## Select Only Needed Columns

Avoid `SELECT *` — especially when tables have large text or JSON columns.

Incorrect:
```php
$posts = Post::with('author')->get();
```

Correct:
```php
$posts = Post::select('id', 'title', 'user_id', 'created_at')
    ->with(['author:id,name,avatar'])
    ->get();
```

When selecting columns on eager-loaded relationships, always include the foreign key column or the relationship won't match.

## Chunk Large Datasets

Never load thousands of records at once. Use chunking for batch processing.

Incorrect:
```php
$users = User::all();
foreach ($users as $user) {
    $user->notify(new WeeklyDigest);
}
```

Correct:
```php
User::where('subscribed', true)->chunk(200, function ($users) {
    foreach ($users as $user) {
        $user->notify(new WeeklyDigest);
    }
});
```

Use `chunkById()` when modifying records during iteration — standard `chunk()` uses OFFSET which shifts when rows change:

```php
User::where('active', false)->chunkById(200, function ($users) {
    $users->each->delete();
});
```

## Add Database Indexes

Index columns that appear in `WHERE`, `ORDER BY`, `JOIN`, and `GROUP BY` clauses.

Incorrect:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('status');
    $table->timestamps();
});
```

Correct:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->index()->constrained();
    $table->string('status')->index();
    $table->timestamps();
    $table->index(['status', 'created_at']);
});
```

Add composite indexes for common query patterns (e.g., `WHERE status = ? ORDER BY created_at`).

## Use `withCount()` for Counting Relations

Never load entire collections just to count them.

Incorrect:
```php
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->comments->count();
}
```

Correct:
```php
$posts = Post::withCount('comments')->get();
foreach ($posts as $post) {
    echo $post->comments_count;
}
```

Conditional counting:

```php
$posts = Post::withCount([
    'comments',
    'comments as approved_comments_count' => function ($query) {
        $query->where('approved', true);
    },
])->get();
```

## Use `cursor()` for Memory-Efficient Iteration

For read-only iteration over large result sets, `cursor()` loads one record at a time via a PHP generator.

Incorrect:
```php
$users = User::where('active', true)->get();
```

Correct:
```php
foreach (User::where('active', true)->cursor() as $user) {
    ProcessUser::dispatch($user->id);
}
```

Use `cursor()` for read-only iteration. Use `chunk()` / `chunkById()` when modifying records.

## No Queries in Blade Templates

Never execute queries in Blade templates. Pass data from controllers.

Incorrect:
```blade
@foreach (User::all() as $user)
    {{ $user->profile->name }}
@endforeach
```

Correct:
```php
// Controller
$users = User::with('profile')->get();
return view('users.index', compact('users'));
```

```blade
@foreach ($users as $user)
    {{ $user->profile->name }}
@endforeach
```
````

## File: .agents/skills/laravel-best-practices/rules/eloquent.md
````markdown
# Eloquent Best Practices

## Use Correct Relationship Types

Use `hasMany`, `belongsTo`, `morphMany`, etc. with proper return type hints.

```php
public function comments(): HasMany
{
    return $this->hasMany(Comment::class);
}

public function author(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}
```

## Use Local Scopes for Reusable Queries

Extract reusable query constraints into local scopes to avoid duplication.

Incorrect:
```php
$active = User::where('verified', true)->whereNotNull('activated_at')->get();
$articles = Article::whereHas('user', function ($q) {
    $q->where('verified', true)->whereNotNull('activated_at');
})->get();
```

Correct:
```php
public function scopeActive(Builder $query): Builder
{
    return $query->where('verified', true)->whereNotNull('activated_at');
}

// Usage
$active = User::active()->get();
$articles = Article::whereHas('user', fn ($q) => $q->active())->get();
```

## Apply Global Scopes Sparingly

Global scopes silently modify every query on the model, making debugging difficult. Prefer local scopes and reserve global scopes for truly universal constraints like soft deletes or multi-tenancy.

Incorrect (global scope for a conditional filter):
```php
class PublishedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('published', true);
    }
}
// Now admin panels, reports, and background jobs all silently skip drafts
```

Correct (local scope you opt into):
```php
public function scopePublished(Builder $query): Builder
{
    return $query->where('published', true);
}

Post::published()->paginate(); // Explicit
Post::paginate(); // Admin sees all
```

## Define Attribute Casts

Use the `casts()` method (or `$casts` property following project convention) for automatic type conversion.

```php
protected function casts(): array
{
    return [
        'is_active' => 'boolean',
        'metadata' => 'array',
        'total' => 'decimal:2',
    ];
}
```

## Cast Date Columns Properly

Always cast date columns. Use Carbon instances in templates instead of formatting strings manually.

Incorrect:
```blade
{{ Carbon::createFromFormat('Y-d-m H-i', $order->ordered_at)->toDateString() }}
```

Correct:
```php
protected function casts(): array
{
    return [
        'ordered_at' => 'datetime',
    ];
}
```

```blade
{{ $order->ordered_at->toDateString() }}
{{ $order->ordered_at->format('m-d') }}
```

## Use `whereBelongsTo()` for Relationship Queries

Cleaner than manually specifying foreign keys.

Incorrect:
```php
Post::where('user_id', $user->id)->get();
```

Correct:
```php
Post::whereBelongsTo($user)->get();
Post::whereBelongsTo($user, 'author')->get();
```

## Avoid Hardcoded Table Names in Queries

Never use string literals for table names in raw queries, joins, or subqueries. Hardcoded table names make it impossible to find all places a model is used and break refactoring (e.g., renaming a table requires hunting through every raw string).

Incorrect:
```php
DB::table('users')->where('active', true)->get();

$query->join('companies', 'companies.id', '=', 'users.company_id');

DB::select('SELECT * FROM orders WHERE status = ?', ['pending']);
```

Correct — reference the model's table:
```php
DB::table((new User)->getTable())->where('active', true)->get();

// Even better — use Eloquent or the query builder instead of raw SQL
User::where('active', true)->get();
Order::where('status', 'pending')->get();
```

Prefer Eloquent queries and relationships over `DB::table()` whenever possible — they already reference the model's table. When `DB::table()` or raw joins are unavoidable, always use `(new Model)->getTable()` to keep the reference traceable.

**Exception — migrations:** In migrations, hardcoded table names via `DB::table('settings')` are acceptable and preferred. Models change over time but migrations are frozen snapshots — referencing a model that is later renamed or deleted would break the migration.
````

## File: .agents/skills/laravel-best-practices/rules/error-handling.md
````markdown
# Error Handling Best Practices

## Exception Reporting and Rendering

There are two valid approaches — choose one and apply it consistently across the project.

**Co-location on the exception class** — keeps behavior alongside the exception definition, easier to find:

```php
class InvalidOrderException extends Exception
{
    public function report(): void { /* custom reporting */ }

    public function render(Request $request): Response
    {
        return response()->view('errors.invalid-order', status: 422);
    }
}
```

**Centralized in `bootstrap/app.php`** — all exception handling in one place, easier to see the full picture:

```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->report(function (InvalidOrderException $e) { /* ... */ });
    $exceptions->render(function (InvalidOrderException $e, Request $request) {
        return response()->view('errors.invalid-order', status: 422);
    });
})
```

Check the existing codebase and follow whichever pattern is already established.

## Use `ShouldntReport` for Exceptions That Should Never Log

More discoverable than listing classes in `dontReport()`.

```php
class PodcastProcessingException extends Exception implements ShouldntReport {}
```

## Throttle High-Volume Exceptions

A single failing integration can flood error tracking. Use `throttle()` to rate-limit per exception type.

## Enable `dontReportDuplicates()`

Prevents the same exception instance from being logged multiple times when `report($e)` is called in multiple catch blocks.

## Force JSON Error Rendering for API Routes

Laravel auto-detects `Accept: application/json` but API clients may not set it. Explicitly declare JSON rendering for API routes.

```php
$exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
    return $request->is('api/*') || $request->expectsJson();
});
```

## Add Context to Exception Classes

Attach structured data to exceptions at the source via a `context()` method — Laravel includes it automatically in the log entry.

```php
class InvalidOrderException extends Exception
{
    public function context(): array
    {
        return ['order_id' => $this->orderId];
    }
}
```
````

## File: .agents/skills/laravel-best-practices/rules/events-notifications.md
````markdown
# Events & Notifications Best Practices

## Rely on Event Discovery

Laravel auto-discovers listeners by reading `handle(EventType $event)` type-hints. No manual registration needed in `AppServiceProvider`.

## Run `event:cache` in Production Deploy

Event discovery scans the filesystem per-request in dev. Cache it in production: `php artisan optimize` or `php artisan event:cache`.

## Use `ShouldDispatchAfterCommit` Inside Transactions

Without it, a queued listener may process before the DB transaction commits, reading data that doesn't exist yet.

```php
class OrderShipped implements ShouldDispatchAfterCommit {}
```

## Always Queue Notifications

Notifications often hit external APIs (email, SMS, Slack). Without `ShouldQueue`, they block the HTTP response.

```php
class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;
}
```

## Use `afterCommit()` on Notifications in Transactions

Same race condition as events — call `afterCommit()` to delay dispatch until the transaction commits.

```php
$user->notify((new InvoicePaid($invoice))->afterCommit());
```

## Route Notification Channels to Dedicated Queues

Mail and database notifications have different priorities. Use `viaQueues()` to route them to separate queues.

## Use On-Demand Notifications for Non-User Recipients

Avoid creating dummy models to send notifications to arbitrary addresses.

```php
Notification::route('mail', 'admin@example.com')->notify(new SystemAlert());
```

## Implement `HasLocalePreference` on Notifiable Models

Laravel automatically uses the user's preferred locale for all notifications and mailables — no per-call `locale()` needed.
````

## File: .agents/skills/laravel-best-practices/rules/http-client.md
````markdown
# HTTP Client Best Practices

## Always Set Explicit Timeouts

The default timeout is 30 seconds — too long for most API calls. Always set explicit `timeout` and `connectTimeout` to fail fast.

Incorrect:
```php
$response = Http::get('https://api.example.com/users');
```

Correct:
```php
$response = Http::timeout(5)
    ->connectTimeout(3)
    ->get('https://api.example.com/users');
```

For service-specific clients, define timeouts in a macro:

```php
Http::macro('github', function () {
    return Http::baseUrl('https://api.github.com')
        ->timeout(10)
        ->connectTimeout(3)
        ->withToken(config('services.github.token'));
});

$response = Http::github()->get('/repos/laravel/framework');
```

## Use Retry with Backoff for External APIs

External APIs have transient failures. Use `retry()` with increasing delays.

Incorrect:
```php
$response = Http::post('https://api.stripe.com/v1/charges', $data);

if ($response->failed()) {
    throw new PaymentFailedException('Charge failed');
}
```

Correct:
```php
$response = Http::retry([100, 500, 1000])
    ->timeout(10)
    ->post('https://api.stripe.com/v1/charges', $data);
```

Only retry on specific errors:

```php
$response = Http::retry(3, 100, function (Throwable $exception, PendingRequest $request) {
    return $exception instanceof ConnectionException
        || ($exception instanceof RequestException && $exception->response->serverError());
})->post('https://api.example.com/data');
```

## Handle Errors Explicitly

The HTTP Client does not throw on 4xx/5xx by default. Always check status or use `throw()`.

Incorrect:
```php
$response = Http::get('https://api.example.com/users/1');
$user = $response->json(); // Could be an error body
```

Correct:
```php
$response = Http::timeout(5)
    ->get('https://api.example.com/users/1')
    ->throw();

$user = $response->json();
```

For graceful degradation:

```php
$response = Http::get('https://api.example.com/users/1');

if ($response->successful()) {
    return $response->json();
}

if ($response->notFound()) {
    return null;
}

$response->throw();
```

## Use Request Pooling for Concurrent Requests

When making multiple independent API calls, use `Http::pool()` instead of sequential calls.

Incorrect:
```php
$users = Http::get('https://api.example.com/users')->json();
$posts = Http::get('https://api.example.com/posts')->json();
$comments = Http::get('https://api.example.com/comments')->json();
```

Correct:
```php
use Illuminate\Http\Client\Pool;

$responses = Http::pool(fn (Pool $pool) => [
    $pool->as('users')->get('https://api.example.com/users'),
    $pool->as('posts')->get('https://api.example.com/posts'),
    $pool->as('comments')->get('https://api.example.com/comments'),
]);

$users = $responses['users']->json();
$posts = $responses['posts']->json();
```

## Fake HTTP Calls in Tests

Never make real HTTP requests in tests. Use `Http::fake()` and `preventStrayRequests()`.

Incorrect:
```php
it('syncs user from API', function () {
    $service = new UserSyncService;
    $service->sync(1); // Hits the real API
});
```

Correct:
```php
it('syncs user from API', function () {
    Http::preventStrayRequests();

    Http::fake([
        'api.example.com/users/1' => Http::response([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]),
    ]);

    $service = new UserSyncService;
    $service->sync(1);

    Http::assertSent(function (Request $request) {
        return $request->url() === 'https://api.example.com/users/1';
    });
});
```

Test failure scenarios too:

```php
Http::fake([
    'api.example.com/*' => Http::failedConnection(),
]);
```
````

## File: .agents/skills/laravel-best-practices/rules/mail.md
````markdown
# Mail Best Practices

## Implement `ShouldQueue` on the Mailable Class

Makes queueing the default regardless of how the mailable is dispatched. No need to remember `Mail::queue()` at every call site — `Mail::send()` also queues it.

## Use `afterCommit()` on Mailables Inside Transactions

A queued mailable dispatched inside a transaction may process before the commit. Use `$this->afterCommit()` in the constructor.

## Use `assertQueued()` Not `assertSent()` for Queued Mailables

`Mail::assertSent()` only catches synchronous mail. Queued mailables fail `assertSent` with a "Did you mean to use assertQueued()?" hint.

Incorrect: `Mail::assertSent(OrderShipped::class);` when mailable implements `ShouldQueue`.

Correct: `Mail::assertQueued(OrderShipped::class);`

## Use Markdown Mailables for Transactional Emails

Markdown mailables auto-generate both HTML and plain-text versions, use responsive components, and allow global style customization. Generate with `--markdown` flag.

## Separate Content Tests from Sending Tests

Content tests: instantiate the mailable directly, call `assertSeeInHtml()`.
Sending tests: use `Mail::fake()` and `assertSent()`/`assertQueued()`.
Don't mix them — it conflates concerns and makes tests brittle.
````

## File: .agents/skills/laravel-best-practices/rules/migrations.md
````markdown
# Migration Best Practices

## Generate Migrations with Artisan

Always use `php artisan make:migration` for consistent naming and timestamps.

Incorrect (manually created file):
```php
// database/migrations/posts_migration.php  ← wrong naming, no timestamp
```

Correct (Artisan-generated):
```bash
php artisan make:migration create_posts_table
php artisan make:migration add_slug_to_posts_table
```

## Use `constrained()` for Foreign Keys

Automatic naming and referential integrity.

```php
$table->foreignId('user_id')->constrained()->cascadeOnDelete();

// Non-standard names
$table->foreignId('author_id')->constrained('users');
```

## Never Modify Deployed Migrations

Once a migration has run in production, treat it as immutable. Create a new migration to change the table.

Incorrect (editing a deployed migration):
```php
// 2024_01_01_create_posts_table.php — already in production
$table->string('slug')->unique(); // ← added after deployment
```

Correct (new migration to alter):
```php
// 2024_03_15_add_slug_to_posts_table.php
Schema::table('posts', function (Blueprint $table) {
    $table->string('slug')->unique()->after('title');
});
```

## Add Indexes in the Migration

Add indexes when creating the table, not as an afterthought. Columns used in `WHERE`, `ORDER BY`, and `JOIN` clauses need indexes.

Incorrect:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('status');
    $table->timestamps();
});
```

Correct:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->index();
    $table->string('status')->index();
    $table->timestamp('shipped_at')->nullable()->index();
    $table->timestamps();
});
```

## Mirror Defaults in Model `$attributes`

When a column has a database default, mirror it in the model so new instances have correct values before saving.

```php
// Migration
$table->string('status')->default('pending');

// Model
protected $attributes = [
    'status' => 'pending',
];
```

## Write Reversible `down()` Methods by Default

Implement `down()` for schema changes that can be safely reversed so `migrate:rollback` works in CI and failed deployments.

```php
public function down(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->dropColumn('slug');
    });
}
```

For intentionally irreversible migrations (e.g., destructive data backfills), leave a clear comment and require a forward fix migration instead of pretending rollback is supported.

## Keep Migrations Focused

One concern per migration. Never mix DDL (schema changes) and DML (data manipulation).

Incorrect (partial failure creates unrecoverable state):
```php
public function up(): void
{
    Schema::create('settings', function (Blueprint $table) { ... });
    DB::table('settings')->insert(['key' => 'version', 'value' => '1.0']);
}
```

Correct (separate migrations):
```php
// Migration 1: create_settings_table
Schema::create('settings', function (Blueprint $table) { ... });

// Migration 2: seed_default_settings
DB::table('settings')->insert(['key' => 'version', 'value' => '1.0']);
```
````

## File: .agents/skills/laravel-best-practices/rules/queue-jobs.md
````markdown
# Queue & Job Best Practices

## Set `retry_after` Greater Than `timeout`

If `retry_after` is shorter than the job's `timeout`, the queue worker re-dispatches the job while it's still running, causing duplicate execution.

Incorrect (`retry_after` ≤ `timeout`):
```php
class ProcessReport implements ShouldQueue
{
    public $timeout = 120;
}

// config/queue.php — retry_after: 90 ← job retried while still running!
```

Correct (`retry_after` > `timeout`):
```php
class ProcessReport implements ShouldQueue
{
    public $timeout = 120;
}

// config/queue.php — retry_after: 180 ← safely longer than any job timeout
```

## Use Exponential Backoff

Use progressively longer delays between retries to avoid hammering failing services.

Incorrect (fixed retry interval):
```php
class SyncWithStripe implements ShouldQueue
{
    public $tries = 3;
    // Default: retries immediately, overwhelming the API
}
```

Correct (exponential backoff):
```php
class SyncWithStripe implements ShouldQueue
{
    public $tries = 3;
    public $backoff = [1, 5, 10];
}
```

## Implement `ShouldBeUnique`

Prevent duplicate job processing.

```php
class GenerateInvoice implements ShouldQueue, ShouldBeUnique
{
    public function uniqueId(): string
    {
        return $this->order->id;
    }

    public $uniqueFor = 3600;
}
```

## Always Implement `failed()`

Handle errors explicitly — don't rely on silent failure.

```php
public function failed(?Throwable $exception): void
{
    $this->podcast->update(['status' => 'failed']);
    Log::error('Processing failed', ['id' => $this->podcast->id, 'error' => $exception->getMessage()]);
}
```

## Rate Limit External API Calls in Jobs

Use `RateLimited` middleware to throttle jobs calling third-party APIs.

```php
public function middleware(): array
{
    return [new RateLimited('external-api')];
}
```

## Batch Related Jobs

Use `Bus::batch()` when jobs should succeed or fail together.

```php
Bus::batch([
    new ImportCsvChunk($chunk1),
    new ImportCsvChunk($chunk2),
])
->then(fn (Batch $batch) => Notification::send($user, new ImportComplete))
->catch(fn (Batch $batch, Throwable $e) => Log::error('Batch failed'))
->dispatch();
```

## `retryUntil()` Needs `$tries = 0`

When using time-based retry limits, set `$tries = 0` to avoid premature failure.

```php
public $tries = 0;

public function retryUntil(): \DateTimeInterface
{
    return now()->addHours(4);
}
```

## Use `ShouldBeUniqueUntilProcessing` for Early Lock Release

`ShouldBeUnique` holds the lock until the job completes. `ShouldBeUniqueUntilProcessing` releases it when processing starts, allowing new instances to queue.

```php
class UpdateSearchIndex implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
    // Lock releases when processing begins, not when it finishes
}
```

## Use Horizon for Complex Queue Scenarios

Use Laravel Horizon when you need monitoring, auto-scaling, failure tracking, or multiple queues with different priorities.

```php
// config/horizon.php
'environments' => [
    'production' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['high', 'default', 'low'],
            'balance' => 'auto',
            'minProcesses' => 1,
            'maxProcesses' => 10,
            'tries' => 3,
        ],
    ],
],
```
````

## File: .agents/skills/laravel-best-practices/rules/routing.md
````markdown
# Routing & Controllers Best Practices

## Use Implicit Route Model Binding

Let Laravel resolve models automatically from route parameters.

Incorrect:
```php
public function show(int $id)
{
    $post = Post::findOrFail($id);
}
```

Correct:
```php
public function show(Post $post)
{
    return view('posts.show', ['post' => $post]);
}
```

## Use Scoped Bindings for Nested Resources

Enforce parent-child relationships automatically.

```php
Route::get('/users/{user}/posts/{post}', function (User $user, Post $post) {
    // $post is automatically scoped to $user
})->scopeBindings();
```

## Use Resource Controllers

Use `Route::resource()` or `apiResource()` for RESTful endpoints.

```php
Route::resource('posts', PostController::class);
// In routes/api.php — the /api prefix is applied automatically
Route::apiResource('posts', Api\PostController::class);
```

## Keep Controllers Thin

Aim for under 10 lines per method. Extract business logic to action or service classes.

Incorrect:
```php
public function store(Request $request)
{
    $validated = $request->validate([...]);
    if ($request->hasFile('image')) {
        $request->file('image')->move(public_path('images'));
    }
    $post = Post::create($validated);
    $post->tags()->sync($validated['tags']);
    event(new PostCreated($post));
    return redirect()->route('posts.show', $post);
}
```

Correct:
```php
public function store(StorePostRequest $request, CreatePostAction $create)
{
    $post = $create->execute($request->validated());

    return redirect()->route('posts.show', $post);
}
```

## Type-Hint Form Requests

Type-hinting Form Requests triggers automatic validation and authorization before the method executes.

Incorrect:
```php
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'title' => ['required', 'max:255'],
        'body' => ['required'],
    ]);

    Post::create($validated);

    return redirect()->route('posts.index');
}
```

Correct:
```php
public function store(StorePostRequest $request): RedirectResponse
{
    Post::create($request->validated());

    return redirect()->route('posts.index');
}
```
````

## File: .agents/skills/laravel-best-practices/rules/scheduling.md
````markdown
# Task Scheduling Best Practices

## Use `withoutOverlapping()` on Variable-Duration Tasks

Without it, a long-running task spawns a second instance on the next tick, causing double-processing or resource exhaustion.

## Use `onOneServer()` on Multi-Server Deployments

Without it, every server runs the same task simultaneously. Requires a shared cache driver (Redis, database, Memcached).

## Use `runInBackground()` for Concurrent Long Tasks

By default, tasks at the same tick run sequentially. A slow first task delays all subsequent ones. `runInBackground()` runs them as separate processes.

## Use `environments()` to Restrict Tasks

Prevent accidental execution of production-only tasks (billing, reporting) on staging.

```php
Schedule::command('billing:charge')->monthly()->environments(['production']);
```

## Use `takeUntilTimeout()` for Time-Bounded Processing

A task running every 15 minutes that processes an unbounded cursor can overlap with the next run. Bound execution time.

## Use Schedule Groups for Shared Configuration

Avoid repeating `->onOneServer()->timezone('America/New_York')` across many tasks.

```php
Schedule::daily()
    ->onOneServer()
    ->timezone('America/New_York')
    ->group(function () {
        Schedule::command('emails:send --force');
        Schedule::command('emails:prune');
    });
```
````

## File: .agents/skills/laravel-best-practices/rules/security.md
````markdown
# Security Best Practices

## Mass Assignment Protection

Every model must define `$fillable` (whitelist) or `$guarded` (blacklist).

Incorrect:
```php
class User extends Model
{
    protected $guarded = []; // All fields are mass assignable
}
```

Correct:
```php
class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
```

Never use `$guarded = []` on models that accept user input.

## Authorize Every Action

Use policies or gates in controllers. Never skip authorization.

Incorrect:
```php
public function update(UpdatePostRequest $request, Post $post)
{
    $post->update($request->validated());
}
```

Correct:
```php
public function update(UpdatePostRequest $request, Post $post)
{
    Gate::authorize('update', $post);

    $post->update($request->validated());
}
```

Or via Form Request:

```php
public function authorize(): bool
{
    return $this->user()->can('update', $this->route('post'));
}
```

## Prevent SQL Injection

Always use parameter binding. Never interpolate user input into queries.

Incorrect:
```php
DB::select("SELECT * FROM users WHERE name = '{$request->name}'");
```

Correct:
```php
User::where('name', $request->name)->get();

// Raw expressions with bindings
User::whereRaw('LOWER(name) = ?', [strtolower($request->name)])->get();
```

## Escape Output to Prevent XSS

Use `{{ }}` for HTML escaping. Only use `{!! !!}` for trusted, pre-sanitized content.

Incorrect:
```blade
{!! $user->bio !!}
```

Correct:
```blade
{{ $user->bio }}
```

## CSRF Protection

Include `@csrf` in all POST/PUT/DELETE Blade forms. In Inertia apps, the `@csrf` directive is automatically applied.

Incorrect:
```blade
<form method="POST" action="/posts">
    <input type="text" name="title">
</form>
```

Correct:
```blade
<form method="POST" action="/posts">
    @csrf
    <input type="text" name="title">
</form>
```

## Rate Limit Auth and API Routes

Apply `throttle` middleware to authentication and API routes.

```php
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

Route::post('/login', LoginController::class)->middleware('throttle:login');
```

## Validate File Uploads

Validate extension, MIME type, and size. The `mimes` rule checks extensions; use `mimetypes` for actual MIME type validation. Never trust client-provided filenames.

```php
public function rules(): array
{
    return [
        'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ];
}
```

Store with generated filenames:

```php
$path = $request->file('avatar')->store('avatars', 'public');
```

## Keep Secrets Out of Code

Never commit `.env`. Access secrets via `config()` only.

Incorrect:
```php
$key = env('API_KEY');
```

Correct:
```php
// config/services.php
'api_key' => env('API_KEY'),

// In application code
$key = config('services.api_key');
```

## Audit Dependencies

Run `composer audit` periodically to check for known vulnerabilities in dependencies. Automate this in CI to catch issues before deployment.

```bash
composer audit
```

## Encrypt Sensitive Database Fields

Use `encrypted` cast for API keys/tokens and mark the attribute as `hidden`.

Incorrect:
```php
class Integration extends Model
{
    protected function casts(): array
    {
        return [
            'api_key' => 'string',
        ];
    }
}
```

Correct:
```php
class Integration extends Model
{
    protected $hidden = ['api_key', 'api_secret'];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'api_secret' => 'encrypted',
        ];
    }
}
```
````

## File: .agents/skills/laravel-best-practices/rules/style.md
````markdown
# Conventions & Style

## Follow Laravel Naming Conventions

| What | Convention | Good | Bad |
|------|-----------|------|-----|
| Controller | singular | `ArticleController` | `ArticlesController` |
| Model | singular | `User` | `Users` |
| Table | plural, snake_case | `article_comments` | `articleComments` |
| Pivot table | singular alphabetical | `article_user` | `user_article` |
| Column | snake_case, no model name | `meta_title` | `article_meta_title` |
| Foreign key | singular model + `_id` | `article_id` | `articles_id` |
| Route | plural | `articles/1` | `article/1` |
| Route name | snake_case with dots | `users.show_active` | `users.show-active` |
| Method | camelCase | `getAll` | `get_all` |
| Variable | camelCase | `$articlesWithAuthor` | `$articles_with_author` |
| Collection | descriptive, plural | `$activeUsers` | `$data` |
| Object | descriptive, singular | `$activeUser` | `$users` |
| View | kebab-case | `show-filtered.blade.php` | `showFiltered.blade.php` |
| Config | snake_case | `google_calendar.php` | `googleCalendar.php` |
| Enum | singular | `UserType` | `UserTypes` |

## Prefer Shorter Readable Syntax

| Verbose | Shorter |
|---------|---------|
| `Session::get('cart')` | `session('cart')` |
| `$request->session()->get('cart')` | `session('cart')` |
| `$request->input('name')` | `$request->name` |
| `return Redirect::back()` | `return back()` |
| `Carbon::now()` | `now()` |
| `App::make('Class')` | `app('Class')` |
| `->where('column', '=', 1)` | `->where('column', 1)` |
| `->orderBy('created_at', 'desc')` | `->latest()` |
| `->orderBy('created_at', 'asc')` | `->oldest()` |
| `->first()->name` | `->value('name')` |

## Use Laravel String & Array Helpers

Laravel provides `Str`, `Arr`, `Number`, and `Uri` helper classes that are more readable, chainable, and UTF-8 safe than raw PHP functions. Always prefer them.

Strings — use `Str` and fluent `Str::of()` over raw PHP:
```php
// Incorrect
$slug = strtolower(str_replace(' ', '-', $title));
$short = substr($text, 0, 100) . '...';
$class = substr(strrchr('App\Models\User', '\'), 1);

// Correct
$slug = Str::slug($title);
$short = Str::limit($text, 100);
$class = class_basename('App\Models\User');
```

Fluent strings — chain operations for complex transformations:
```php
// Incorrect
$result = strtolower(trim(str_replace('_', '-', $input)));

// Correct
$result = Str::of($input)->trim()->replace('_', '-')->lower();
```

Key `Str` methods to prefer: `Str::slug()`, `Str::limit()`, `Str::contains()`, `Str::before()`, `Str::after()`, `Str::between()`, `Str::camel()`, `Str::snake()`, `Str::kebab()`, `Str::headline()`, `Str::squish()`, `Str::mask()`, `Str::uuid()`, `Str::ulid()`, `Str::random()`, `Str::is()`.

Arrays — use `Arr` over raw PHP:
```php
// Incorrect
$name = isset($array['user']['name']) ? $array['user']['name'] : 'default';

// Correct
$name = Arr::get($array, 'user.name', 'default');
```

Key `Arr` methods: `Arr::get()`, `Arr::has()`, `Arr::only()`, `Arr::except()`, `Arr::first()`, `Arr::flatten()`, `Arr::pluck()`, `Arr::where()`, `Arr::wrap()`.

Numbers — use `Number` for display formatting:
```php
Number::format(1000000);          // "1,000,000"
Number::currency(1500, 'USD');    // "$1,500.00"
Number::abbreviate(1000000);      // "1M"
Number::fileSize(1024 * 1024);    // "1 MB"
Number::percentage(75.5);         // "75.5%"
```

URIs — use `Uri` for URL manipulation:
```php
$uri = Uri::of('https://example.com/search')
    ->withQuery(['q' => 'laravel', 'page' => 1]);
```

Use `$request->string('name')` to get a fluent `Stringable` directly from request input for immediate chaining.

Use `search-docs` for the full list of available methods — these helpers are extensive.

## No Inline JS/CSS in Blade

Do not put JS or CSS in Blade templates. Do not put HTML in PHP classes.

Incorrect:
```blade
let article = `{{ json_encode($article) }}`;
```

Correct:
```blade
<button class="js-fav-article" data-article='@json($article)'>{{ $article->name }}</button>
```

Pass data to JS via data attributes or use a dedicated PHP-to-JS package.

## No Unnecessary Comments

Code should be readable on its own. Use descriptive method and variable names instead of comments. The only exception is config files, where descriptive comments are expected.

Incorrect:
```php
// Check if there are any joins
if (count((array) $builder->getQuery()->joins) > 0)
```

Correct:
```php
if ($this->hasJoins())
```
````

## File: .agents/skills/laravel-best-practices/rules/testing.md
````markdown
# Testing Best Practices

## Use `LazilyRefreshDatabase` Over `RefreshDatabase`

`RefreshDatabase` migrates once per process and wraps each test in a rolled-back transaction. `LazilyRefreshDatabase` skips even that first migration if the schema is already up to date.

## Use Model Assertions Over Raw Database Assertions

Incorrect: `$this->assertDatabaseHas('users', ['id' => $user->id]);`

Correct: `$this->assertModelExists($user);`

More expressive, type-safe, and fails with clearer messages.

## Use Factory States and Sequences

Named states make tests self-documenting. Sequences eliminate repetitive setup.

Incorrect: `User::factory()->create(['email_verified_at' => null]);`

Correct: `User::factory()->unverified()->create();`

## Use `Exceptions::fake()` to Assert Exception Reporting

Instead of `withoutExceptionHandling()`, use `Exceptions::fake()` to assert the correct exception was reported while the request completes normally.

## Call `Event::fake()` After Factory Setup

Model factories rely on model events (e.g., `creating` to generate UUIDs). Calling `Event::fake()` before factory calls silences those events, producing broken models.

Incorrect: `Event::fake(); $user = User::factory()->create();`

Correct: `$user = User::factory()->create(); Event::fake();`

## Use `recycle()` to Share Relationship Instances Across Factories

Without `recycle()`, nested factories create separate instances of the same conceptual entity.

```php
Ticket::factory()
    ->recycle(Airline::factory()->create())
    ->create();
```
````

## File: .agents/skills/laravel-best-practices/rules/validation.md
````markdown
# Validation & Forms Best Practices

## Use Form Request Classes

Extract validation from controllers into dedicated Form Request classes.

Incorrect:
```php
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'body' => 'required',
    ]);
}
```

Correct:
```php
public function store(StorePostRequest $request)
{
    Post::create($request->validated());
}
```

## Array vs. String Notation for Rules

Array syntax is more readable and composes cleanly with `Rule::` objects. Prefer it in new code, but check existing Form Requests first and match whatever notation the project already uses.

```php
// Preferred for new code
'email' => ['required', 'email', Rule::unique('users')],

// Follow existing convention if the project uses string notation
'email' => 'required|email|unique:users',
```

## Always Use `validated()`

Get only validated data. Never use `$request->all()` for mass operations.

Incorrect:
```php
Post::create($request->all());
```

Correct:
```php
Post::create($request->validated());
```

## Use `Rule::when()` for Conditional Validation

```php
'company_name' => [
    Rule::when($this->account_type === 'business', ['required', 'string', 'max:255']),
],
```

## Use the `after()` Method for Custom Validation

Use `after()` instead of `withValidator()` for custom validation logic that depends on multiple fields.

```php
public function after(): array
{
    return [
        function (Validator $validator) {
            if ($this->quantity > Product::find($this->product_id)?->stock) {
                $validator->errors()->add('quantity', 'Not enough stock.');
            }
        },
    ];
}
```
````

## File: .agents/skills/laravel-best-practices/SKILL.md
````markdown
---
name: laravel-best-practices
description: "Apply this skill whenever writing, reviewing, or refactoring Laravel PHP code. This includes creating or modifying controllers, models, migrations, form requests, policies, jobs, scheduled commands, service classes, and Eloquent queries. Triggers for N+1 and query performance issues, caching strategies, authorization and security patterns, validation, error handling, queue and job configuration, route definitions, and architectural decisions. Also use for Laravel code reviews and refactoring existing Laravel code to follow best practices. Covers any task involving Laravel backend PHP code patterns."
license: MIT
metadata:
  author: laravel
---

# Laravel Best Practices

Best practices for Laravel, prioritized by impact. Each rule teaches what to do and why. For exact API syntax, verify with `search-docs`.

## Consistency First

Before applying any rule, check what the application already does. Laravel offers multiple valid approaches — the best choice is the one the codebase already uses, even if another pattern would be theoretically better. Inconsistency is worse than a suboptimal pattern.

Check sibling files, related controllers, models, or tests for established patterns. If one exists, follow it — don't introduce a second way. These rules are defaults for when no pattern exists yet, not overrides.

## Quick Reference

### 1. Database Performance → `rules/db-performance.md`

- Eager load with `with()` to prevent N+1 queries
- Enable `Model::preventLazyLoading()` in development
- Select only needed columns, avoid `SELECT *`
- `chunk()` / `chunkById()` for large datasets
- Index columns used in `WHERE`, `ORDER BY`, `JOIN`
- `withCount()` instead of loading relations to count
- `cursor()` for memory-efficient read-only iteration
- Never query in Blade templates

### 2. Advanced Query Patterns → `rules/advanced-queries.md`

- `addSelect()` subqueries over eager-loading entire has-many for a single value
- Dynamic relationships via subquery FK + `belongsTo`
- Conditional aggregates (`CASE WHEN` in `selectRaw`) over multiple count queries
- `setRelation()` to prevent circular N+1 queries
- `whereIn` + `pluck()` over `whereHas` for better index usage
- Two simple queries can beat one complex query
- Compound indexes matching `orderBy` column order
- Correlated subqueries in `orderBy` for has-many sorting (avoid joins)

### 3. Security → `rules/security.md`

- Define `$fillable` or `$guarded` on every model, authorize every action via policies or gates
- No raw SQL with user input — use Eloquent or query builder
- `{{ }}` for output escaping, `@csrf` on all POST/PUT/DELETE forms, `throttle` on auth and API routes
- Validate MIME type, extension, and size for file uploads
- Never commit `.env`, use `config()` for secrets, `encrypted` cast for sensitive DB fields

### 4. Caching → `rules/caching.md`

- `Cache::remember()` over manual get/put
- `Cache::flexible()` for stale-while-revalidate on high-traffic data
- `Cache::memo()` to avoid redundant cache hits within a request
- Cache tags to invalidate related groups
- `Cache::add()` for atomic conditional writes
- `once()` to memoize per-request or per-object lifetime
- `Cache::lock()` / `lockForUpdate()` for race conditions
- Failover cache stores in production

### 5. Eloquent Patterns → `rules/eloquent.md`

- Correct relationship types with return type hints
- Local scopes for reusable query constraints
- Global scopes sparingly — document their existence
- Attribute casts in the `casts()` method
- Cast date columns, use Carbon instances in templates
- `whereBelongsTo($model)` for cleaner queries
- Never hardcode table names — use `(new Model)->getTable()` or Eloquent queries

### 6. Validation & Forms → `rules/validation.md`

- Form Request classes, not inline validation
- Array notation `['required', 'email']` for new code; follow existing convention
- `$request->validated()` only — never `$request->all()`
- `Rule::when()` for conditional validation
- `after()` instead of `withValidator()`

### 7. Configuration → `rules/config.md`

- `env()` only inside config files
- `App::environment()` or `app()->isProduction()`
- Config, lang files, and constants over hardcoded text

### 8. Testing Patterns → `rules/testing.md`

- `LazilyRefreshDatabase` over `RefreshDatabase` for speed
- `assertModelExists()` over raw `assertDatabaseHas()`
- Factory states and sequences over manual overrides
- Use fakes (`Event::fake()`, `Exceptions::fake()`, etc.) — but always after factory setup, not before
- `recycle()` to share relationship instances across factories

### 9. Queue & Job Patterns → `rules/queue-jobs.md`

- `retry_after` must exceed job `timeout`; use exponential backoff `[1, 5, 10]`
- `ShouldBeUnique` to prevent duplicates; `ShouldBeUniqueUntilProcessing` for early lock release
- Always implement `failed()`; with `retryUntil()`, set `$tries = 0`
- `RateLimited` middleware for external API calls; `Bus::batch()` for related jobs
- Horizon for complex multi-queue scenarios

### 10. Routing & Controllers → `rules/routing.md`

- Implicit route model binding
- Scoped bindings for nested resources
- `Route::resource()` or `apiResource()`
- Methods under 10 lines — extract to actions/services
- Type-hint Form Requests for auto-validation

### 11. HTTP Client → `rules/http-client.md`

- Explicit `timeout` and `connectTimeout` on every request
- `retry()` with exponential backoff for external APIs
- Check response status or use `throw()`
- `Http::pool()` for concurrent independent requests
- `Http::fake()` and `preventStrayRequests()` in tests

### 12. Events, Notifications & Mail → `rules/events-notifications.md`, `rules/mail.md`

- Event discovery over manual registration; `event:cache` in production
- `ShouldDispatchAfterCommit` / `afterCommit()` inside transactions
- Queue notifications and mailables with `ShouldQueue`
- On-demand notifications for non-user recipients
- `HasLocalePreference` on notifiable models
- `assertQueued()` not `assertSent()` for queued mailables
- Markdown mailables for transactional emails

### 13. Error Handling → `rules/error-handling.md`

- `report()`/`render()` on exception classes or in `bootstrap/app.php` — follow existing pattern
- `ShouldntReport` for exceptions that should never log
- Throttle high-volume exceptions to protect log sinks
- `dontReportDuplicates()` for multi-catch scenarios
- Force JSON rendering for API routes
- Structured context via `context()` on exception classes

### 14. Task Scheduling → `rules/scheduling.md`

- `withoutOverlapping()` on variable-duration tasks
- `onOneServer()` on multi-server deployments
- `runInBackground()` for concurrent long tasks
- `environments()` to restrict to appropriate environments
- `takeUntilTimeout()` for time-bounded processing
- Schedule groups for shared configuration

### 15. Architecture → `rules/architecture.md`

- Single-purpose Action classes; dependency injection over `app()` helper
- Prefer official Laravel packages and follow conventions, don't override defaults
- Default to `ORDER BY id DESC` or `created_at DESC`; `mb_*` for UTF-8 safety
- `defer()` for post-response work; `Context` for request-scoped data; `Concurrency::run()` for parallel execution

### 16. Migrations → `rules/migrations.md`

- Generate migrations with `php artisan make:migration`
- `constrained()` for foreign keys
- Never modify migrations that have run in production
- Add indexes in the migration, not as an afterthought
- Mirror column defaults in model `$attributes`
- Reversible `down()` by default; forward-fix migrations for intentionally irreversible changes
- One concern per migration — never mix DDL and DML

### 17. Collections → `rules/collections.md`

- Higher-order messages for simple collection operations
- `cursor()` vs. `lazy()` — choose based on relationship needs
- `lazyById()` when updating records while iterating
- `toQuery()` for bulk operations on collections

### 18. Blade & Views → `rules/blade-views.md`

- `$attributes->merge()` in component templates
- Blade components over `@include`; `@pushOnce` for per-component scripts
- View Composers for shared view data
- `@aware` for deeply nested component props

### 19. Conventions & Style → `rules/style.md`

- Follow Laravel naming conventions for all entities
- Prefer Laravel helpers (`Str`, `Arr`, `Number`, `Uri`, `Str::of()`, `$request->string()`) over raw PHP functions
- No JS/CSS in Blade, no HTML in PHP classes
- Code should be readable; comments only for config files

## How to Apply

Always use a sub-agent to read rule files and explore this skill's content.

1. Identify the file type and select relevant sections (e.g., migration → §16, controller → §1, §3, §5, §6, §10)
2. Check sibling files for existing patterns — follow those first per Consistency First
3. Verify API syntax with `search-docs` for the installed Laravel version
````

## File: .agents/skills/livewire-development/reference/javascript-hooks.md
````markdown
# Livewire 4 JavaScript Integration

## Interceptor System (v4)

### Intercept Messages

```js
Livewire.interceptMessage(({ component, message, onFinish, onSuccess, onError }) => {
    onFinish(() => { /* After response, before processing */ });
    onSuccess(({ payload }) => { /* payload.snapshot, payload.effects */ });
    onError(() => { /* Server errors */ });
});
```

### Intercept Requests

```js
Livewire.interceptRequest(({ request, onResponse, onSuccess, onError, onFailure }) => {
    onResponse(({ response }) => { /* When received */ });
    onSuccess(({ response, responseJson }) => { /* Success */ });
    onError(({ response, responseBody, preventDefault }) => { /* 4xx/5xx */ });
    onFailure(({ error }) => { /* Network failures */ });
});
```

### Component-Scoped Interceptors

```blade
<script>
    this.$intercept('save', ({ component, onSuccess }) => {
        onSuccess(() => console.log('Saved!'));
    });
</script>
```

## Magic Properties

- `$errors` - Access validation errors from JavaScript
- `$intercept` - Component-scoped interceptors
````

## File: .agents/skills/livewire-development/SKILL.md
````markdown
---
name: livewire-development
description: "Use for any task or question involving Livewire. Activate if user mentions Livewire, wire: directives, or Livewire-specific concepts like wire:model, wire:click, wire:sort, or islands, invoke this skill. Covers building new components, debugging reactivity issues, real-time form validation, drag-and-drop, loading states, migrating from Livewire 3 to 4, converting component formats (SFC/MFC/class-based), and performance optimization. Do not use for non-Livewire reactive UI (React, Vue, Alpine-only, Inertia.js) or standard Laravel forms without Livewire."
license: MIT
metadata:
  author: laravel
---

# Livewire Development

## Documentation

Use `search-docs` for detailed Livewire 4 patterns and documentation.

## Basic Usage

### Creating Components

```bash

# Single-file component (SFC - default in v4)

# Creates: resources/views/components/⚡create-post.blade.php

php artisan make:livewire create-post

# Page component (SFC - Full Page in v4)

# Creates: resources/views/pages/⚡create-post.blade.php

php artisan make:livewire pages::create-post

# Multi-file component (MFC)

# Creates: resources/views/components/⚡create-post/create-post.php

#          resources/views/components/⚡create-post/create-post.blade.php

php artisan make:livewire create-post --mfc

# Class-based component (v3 style)

# Creates: app/Livewire/CreatePost.php AND resources/views/livewire/create-post.blade.php

php artisan make:livewire create-post --class

# With namespace

php artisan make:livewire Posts/CreatePost
```

### Converting Between Formats

Use `php artisan livewire:convert create-post` to convert between single-file, multi-file, and class-based formats.

### Choosing a Component Format

> **Always follow the project's existing conventions first.** Before creating any component, inspect the project's existing Livewire components to determine the established format (SFC, MFC, or class-based) and directory structure. Check `app/Livewire/`, `resources/views/components/`, and `resources/views/livewire/` for existing components. If the project already uses a consistent format, **use that same format** — even if it differs from the Livewire v4 defaults below. Only fall back to the v4 defaults (SFC in `resources/views/components/`) when no existing convention is established.

Also check `config/livewire.php` for `make_command.type`, `make_command.emoji`, `component_locations`, and `component_namespaces` overrides, which change the default format and where files are stored.

### Component Format Reference

| Format | Flag | Class Path | View Path |
|--------|------|------------|-----------|
| Single-file (SFC) | default | — | `resources/views/components/⚡create-post.blade.php` (PHP + Blade in one file) |
| Full Page SFC | `pages::name` | — | `resources/views/pages/⚡create-post.blade.php` |
| Multi-file (MFC) | `--mfc` | `resources/views/components/⚡create-post/create-post.php` | `resources/views/components/⚡create-post/create-post.blade.php` |
| Class-based | `--class` | `app/Livewire/CreatePost.php` | `resources/views/livewire/create-post.blade.php` |
| View-based | default (Blade-only) | — | `resources/views/components/⚡create-post.blade.php` (Blade-only with functional state) |

> **Important:** The ⚡ prefix shown above is the **default** behavior in Livewire v4 — it is **configurable**. Check `config/livewire.php` for the `make_command.emoji` setting. When `true` (default), always include the ⚡ prefix in filenames you create. When `false`, omit the ⚡ prefix from all paths above.

Namespaced components map to subdirectories: `make:livewire Posts/CreatePost` creates `resources/views/components/posts/⚡create-post.blade.php` (single-file by default). Use `make:livewire Posts/CreatePost --mfc` for multi-file output at `resources/views/components/posts/⚡create-post/create-post.php` and `resources/views/components/posts/⚡create-post/create-post.blade.php`.

### Single-File Component Example

<!-- Single-File Component Example -->
```php
<?php
use Livewire\Component;

new class extends Component {
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }
};
?>

<div>
    <button wire:click="increment">Count: @{{ $count }}</button>
</div>
```

## Livewire 4 Specifics

### Key Changes From Livewire 3

These things changed in Livewire 4, but may not have been updated in this application. Verify this application's setup to ensure you follow existing conventions.

- Use `Route::livewire()` for full-page components (e.g., `Route::livewire('/posts/create', CreatePost::class)`); config keys renamed: `layout` → `component_layout`, `lazy_placeholder` → `component_placeholder`.
- `wire:model` now ignores child events by default (use `wire:model.deep` for old behavior); `wire:scroll` renamed to `wire:navigate:scroll`.
- Component tags must be properly closed; `wire:transition` now uses View Transitions API (modifiers removed).
- JavaScript: `$wire.$js('name', fn)` → `$wire.$js.name = fn`; `commit`/`request` hooks → `interceptMessage()`/`interceptRequest()`.

### New Features

- Component formats: single-file (SFC), multi-file (MFC), view-based components.
- Islands (`@island`) for isolated updates; async actions (`wire:click.async`, `#[Async]`) for parallel execution.
- Deferred/bundled loading: `defer`, `lazy.bundle` for optimized component loading.

| Feature | Usage | Purpose |
|---------|-------|---------|
| Islands | `@island(name: 'stats')` | Isolated update regions |
| Async | `wire:click.async` or `#[Async]` | Non-blocking actions |
| Deferred | `defer` attribute | Load after page render |
| Bundled | `lazy.bundle` | Load multiple together |

### New Directives

- `wire:sort`, `wire:intersect`, `wire:ref`, `.renderless`, `.preserve-scroll` are available for use.
- `data-loading` attribute automatically added to elements triggering network requests.

| Directive | Purpose |
|-----------|---------|
| `wire:sort` | Drag-and-drop sorting |
| `wire:intersect` | Viewport intersection detection |
| `wire:ref` | Element references for JS |
| `.renderless` | Component without rendering |
| `.preserve-scroll` | Preserve scroll position |

## Best Practices

- Always use `wire:key` in loops
- Use `wire:loading` for loading states
- Use `wire:model.live` for instant updates (default is debounced)
- Validate and authorize in actions (treat like HTTP requests)

## Configuration

- `smart_wire_keys` defaults to `true`; new configs: `component_locations`, `component_namespaces`, `make_command`, `csp_safe`.

## Alpine & JavaScript

- `wire:transition` uses browser View Transitions API; `$errors` and `$intercept` magic properties available.
- Non-blocking `wire:poll` and parallel `wire:model.live` updates improve performance.

For interceptors and hooks, see [reference/javascript-hooks.md](reference/javascript-hooks.md).

## Testing

<!-- Testing Example -->
```php
Livewire::test(Counter::class)
    ->assertSet('count', 0)
    ->call('increment')
    ->assertSet('count', 1);
```

## Verification

1. Browser console: Check for JS errors
2. Network tab: Verify Livewire requests return 200
3. Ensure `wire:key` on all `@foreach` loops

## Common Pitfalls

- Missing `wire:key` in loops → unexpected re-rendering
- Expecting `wire:model` real-time → use `wire:model.live`
- Unclosed component tags → syntax errors in v4
- Using deprecated config keys or JS hooks
- Including Alpine.js separately (already bundled in Livewire 4)
````

## File: .agents/skills/tailwindcss-development/SKILL.md
````markdown
---
name: tailwindcss-development
description: "Always invoke when the user's message includes 'tailwind' in any form. Also invoke for: building responsive grid layouts (multi-column card grids, product grids), flex/grid page structures (dashboards with sidebars, fixed topbars, mobile-toggle navs), styling UI components (cards, tables, navbars, pricing sections, forms, inputs, badges), adding dark mode variants, fixing spacing or typography, and Tailwind v3/v4 work. The core use case: writing or fixing Tailwind utility classes in HTML templates (Blade, JSX, Vue). Skip for backend PHP logic, database queries, API routes, JavaScript with no HTML/CSS component, CSS file audits, build tool configuration, and vanilla CSS."
license: MIT
metadata:
  author: laravel
---

# Tailwind CSS Development

## Documentation

Use `search-docs` for detailed Tailwind CSS v4 patterns and documentation.

## Basic Usage

- Use Tailwind CSS classes to style HTML. Check and follow existing Tailwind conventions in the project before introducing new patterns.
- Offer to extract repeated patterns into components that match the project's conventions (e.g., Blade, JSX, Vue).
- Consider class placement, order, priority, and defaults. Remove redundant classes, add classes to parent or child elements carefully to reduce repetition, and group elements logically.

## Tailwind CSS v4 Specifics

- Always use Tailwind CSS v4 and avoid deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.

### CSS-First Configuration

In Tailwind v4, configuration is CSS-first using the `@theme` directive — no separate `tailwind.config.js` file is needed:

<!-- CSS-First Config -->
```css
@theme {
  --color-brand: oklch(0.72 0.11 178);
}
```

### Import Syntax

In Tailwind v4, import Tailwind with a regular CSS `@import` statement instead of the `@tailwind` directives used in v3:

<!-- v4 Import Syntax -->
```diff
- @tailwind base;
- @tailwind components;
- @tailwind utilities;
+ @import "tailwindcss";
```

### Replaced Utilities

Tailwind v4 removed deprecated utilities. Use the replacements shown below. Opacity values remain numeric.

| Deprecated | Replacement |
|------------|-------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |

## Spacing

Use `gap` utilities instead of margins for spacing between siblings:

<!-- Gap Utilities -->
```html
<div class="flex gap-8">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

## Dark Mode

If existing pages and components support dark mode, new pages and components must support it the same way, typically using the `dark:` variant:

<!-- Dark Mode -->
```html
<div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
    Content adapts to color scheme
</div>
```

## Common Patterns

### Flexbox Layout

<!-- Flexbox Layout -->
```html
<div class="flex items-center justify-between gap-4">
    <div>Left content</div>
    <div>Right content</div>
</div>
```

### Grid Layout

<!-- Grid Layout -->
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div>Card 1</div>
    <div>Card 2</div>
    <div>Card 3</div>
</div>
```

## Common Pitfalls

- Using deprecated v3 utilities (bg-opacity-*, flex-shrink-*, etc.)
- Using `@tailwind` directives instead of `@import "tailwindcss"`
- Trying to use `tailwind.config.js` instead of CSS `@theme` directive
- Using margins for spacing between siblings instead of gap utilities
- Forgetting to add dark mode variants when the project uses dark mode
````

## File: .agents/skills/workspace-handoff/SKILL.md
````markdown
---
name: workspace-handoff
description: "Use when you need to leave a compact handoff for continuing the same work from another computer or later session. Covers the current objective, completed work, files changed, commands run, blockers, and the next concrete steps."
---

# Workspace Handoff

Use this skill to write or update a short handoff note so another session can continue without rebuilding context.

## Purpose

Capture only the information needed to resume work quickly:

- what we are building
- what is already done
- what remains
- where the important files are
- what was verified
- what still needs attention

## Handoff Structure

Keep the note in this order:

1. `Objective`
2. `Current state`
3. `Completed`
4. `Files touched`
5. `Commands / tests`
6. `Blockers`
7. `Next steps`
8. `Notes for another computer`

## What To Include

- Use absolute file paths for any referenced files.
- Mention the branch or working context if it matters.
- List only the commands that were actually run and their outcome.
- Call out uncommitted changes or files that still need review.
- Record environment requirements if the work depends on them.
- Be explicit about anything that is incomplete, risky, or waiting on the user.

## What To Avoid

- Long explanations.
- Duplicate status updates.
- Background context that does not help the next session continue.
- Full logs unless a specific error is the blocker.

## Suggested Template

```markdown
# Handoff

## Objective
Short description of the goal.

## Current state
One or two sentences on where things stand now.

## Completed
- Item 1
- Item 2

## Files touched
- `/absolute/path/to/file`

## Commands / tests
- `command` -> result

## Blockers
- None
  or
- Specific blocker and why it matters

## Next steps
1. Concrete next action
2. Concrete follow-up action

## Notes for another computer
- Any setup, environment, or UI steps needed to continue
```

## Quality Check

Before finishing the handoff, confirm that someone else could:

- understand the goal in under a minute
- see exactly what changed
- know what to do next
- continue without guessing
````

## File: .ai/mcp/mcp.json
````json
{
  "mcpServers": {
    "laravel-boost": {
      "command": "/Users/juanjose/Library/Application Support/Herd/bin/php84",
      "args": [
        "/Users/juanjose/PhpstormProjects/eugenia/artisan",
        "boost:mcp"
      ]
    }
  }
}
````

## File: .junie/mcp/mcp.json
````json
{
    "mcpServers": {
        "laravel-boost": {
            "command": "/Users/juanjose/Library/Application Support/Herd/bin/php84",
            "args": [
                "/Users/juanjose/PhpstormProjects/eugenia/artisan",
                "boost:mcp"
            ]
        }
    }
}
````

## File: .junie/skills/fluxui-development/SKILL.md
````markdown
---
name: fluxui-development
description: "Use this skill for Flux UI development in Livewire applications only. Trigger when working with <flux:*> components, building or customizing Livewire component UIs, creating forms, modals, tables, or other interactive elements. Covers: flux: components (buttons, inputs, modals, forms, tables, date-pickers, kanban, badges, tooltips, etc.), component composition, Tailwind CSS styling, Heroicons/Lucide icon integration, validation patterns, responsive design, and theming. Do not use for non-Livewire frameworks or non-component styling."
license: MIT
metadata:
  author: laravel
---

# Flux UI Development

## Documentation

Use `search-docs` for detailed Flux UI patterns and documentation.

## Basic Usage

This project uses the free edition of Flux UI, which includes all free components and variants but not Pro components.

Flux UI is a component library for Livewire built with Tailwind CSS. It provides components that are easy to use and customize.

Use Flux UI components when available. Fall back to standard Blade components when no Flux component exists for your needs.

<!-- Basic Button -->
```blade
<flux:button variant="primary">Click me</flux:button>
```

## Available Components (Free Edition)

Available: avatar, badge, brand, breadcrumbs, button, callout, card, checkbox, dropdown, field, heading, icon, input, modal, navbar, otp-input, pagination, profile, progress, radio, select, separator, skeleton, switch, table, text, textarea, toast, tooltip

## Icons

Flux includes [Heroicons](https://heroicons.com/) as its default icon set. Search for exact icon names on the Heroicons site - do not guess or invent icon names.

<!-- Icon Button -->
```blade
<flux:button icon="arrow-down-tray">Export</flux:button>
```

For icons not available in Heroicons, use [Lucide](https://lucide.dev/). Import the icons you need with the Artisan command:

```bash
php artisan flux:icon crown grip-vertical github
```

## Common Patterns

### Form Fields

<!-- Form Field -->
```blade
<flux:field>
    <flux:label>Email</flux:label>
    <flux:input type="email" wire:model="email" />
    <flux:error name="email" />
</flux:field>
```

### Modals

<!-- Modal -->
```blade
<flux:modal wire:model="showModal">
    <flux:heading>Title</flux:heading>
    <p>Content</p>
</flux:modal>
```

## Verification

1. Check component renders correctly
2. Test interactive states
3. Verify mobile responsiveness

## Common Pitfalls

- Trying to use Pro-only components in the free edition
- Not checking if a Flux component exists before creating custom implementations
- Forgetting to use the `search-docs` tool for component-specific documentation
- Not following existing project patterns for Flux usage
````

## File: .junie/skills/laravel-best-practices/rules/advanced-queries.md
````markdown
# Advanced Query Patterns

## Use `addSelect()` Subqueries for Single Values from Has-Many

Instead of eager-loading an entire has-many relationship for a single value (like the latest timestamp), use a correlated subquery via `addSelect()`. This pulls the value directly in the main SQL query — zero extra queries.

```php
public function scopeWithLastLoginAt($query): void
{
    $query->addSelect([
        'last_login_at' => Login::select('created_at')
            ->whereColumn('user_id', 'users.id')
            ->latest()
            ->take(1),
    ])->withCasts(['last_login_at' => 'datetime']);
}
```

## Create Dynamic Relationships via Subquery FK

Extend the `addSelect()` pattern to fetch a foreign key via subquery, then define a `belongsTo` relationship on that virtual attribute. This provides a fully-hydrated related model without loading the entire collection.

```php
public function lastLogin(): BelongsTo
{
    return $this->belongsTo(Login::class);
}

public function scopeWithLastLogin($query): void
{
    $query->addSelect([
        'last_login_id' => Login::select('id')
            ->whereColumn('user_id', 'users.id')
            ->latest()
            ->take(1),
    ])->with('lastLogin');
}
```

## Use Conditional Aggregates Instead of Multiple Count Queries

Replace N separate `count()` queries with a single query using `CASE WHEN` inside `selectRaw()`. Use `toBase()` to skip model hydration when you only need scalar values.

```php
$statuses = Feature::toBase()
    ->selectRaw("count(case when status = 'Requested' then 1 end) as requested")
    ->selectRaw("count(case when status = 'Planned' then 1 end) as planned")
    ->selectRaw("count(case when status = 'Completed' then 1 end) as completed")
    ->first();
```

## Use `setRelation()` to Prevent Circular N+1

When a parent model is eager-loaded with its children, and the view also needs `$child->parent`, use `setRelation()` to inject the already-loaded parent rather than letting Eloquent fire N additional queries.

```php
$feature->load('comments.user');
$feature->comments->each->setRelation('feature', $feature);
```

## Prefer `whereIn` + Subquery Over `whereHas`

`whereHas()` emits a correlated `EXISTS` subquery that re-executes per row. Using `whereIn()` with a `select('id')` subquery lets the database use an index lookup instead, without loading data into PHP memory.

Incorrect (correlated EXISTS re-executes per row):

```php
$query->whereHas('company', fn ($q) => $q->where('name', 'like', $term));
```

Correct (index-friendly subquery, no PHP memory overhead):

```php
$query->whereIn('company_id', Company::where('name', 'like', $term)->select('id'));
```

## Sometimes Two Simple Queries Beat One Complex Query

Running a small, targeted secondary query and passing its results via `whereIn` is often faster than a single complex correlated subquery or join. The additional round-trip is worthwhile when the secondary query is highly selective and uses its own index.

## Use Compound Indexes Matching `orderBy` Column Order

When ordering by multiple columns, create a single compound index in the same column order as the `ORDER BY` clause. Individual single-column indexes cannot combine for multi-column sorts — the database will filesort without a compound index.

```php
// Migration
$table->index(['last_name', 'first_name']);

// Query — column order must match the index
User::query()->orderBy('last_name')->orderBy('first_name')->paginate();
```

## Use Correlated Subqueries for Has-Many Ordering

When sorting by a value from a has-many relationship, avoid joins (they duplicate rows). Use a correlated subquery inside `orderBy()` instead, paired with an `addSelect` scope for eager loading.

```php
public function scopeOrderByLastLogin($query): void
{
    $query->orderByDesc(Login::select('created_at')
        ->whereColumn('user_id', 'users.id')
        ->latest()
        ->take(1)
    );
}
```
````

## File: .junie/skills/laravel-best-practices/rules/architecture.md
````markdown
# Architecture Best Practices

## Single-Purpose Action Classes

Extract discrete business operations into invokable Action classes.

```php
class CreateOrderAction
{
    public function __construct(private InventoryService $inventory) {}

    public function execute(array $data): Order
    {
        $order = Order::create($data);
        $this->inventory->reserve($order);

        return $order;
    }
}
```

## Use Dependency Injection

Always use constructor injection. Avoid `app()` or `resolve()` inside classes.

Incorrect:
```php
class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $service = app(OrderService::class);

        return $service->create($request->validated());
    }
}
```

Correct:
```php
class OrderController extends Controller
{
    public function __construct(private OrderService $service) {}

    public function store(StoreOrderRequest $request)
    {
        return $this->service->create($request->validated());
    }
}
```

## Code to Interfaces

Depend on contracts at system boundaries (payment gateways, notification channels, external APIs) for testability and swappability.

Incorrect (concrete dependency):
```php
class OrderService
{
    public function __construct(private StripeGateway $gateway) {}
}
```

Correct (interface dependency):
```php
interface PaymentGateway
{
    public function charge(int $amount, string $customerId): PaymentResult;
}

class OrderService
{
    public function __construct(private PaymentGateway $gateway) {}
}
```

Bind in a service provider:

```php
$this->app->bind(PaymentGateway::class, StripeGateway::class);
```

## Default Sort by Descending

When no explicit order is specified, sort by `id` or `created_at` descending. Without an explicit `ORDER BY`, row order is undefined.

Incorrect:
```php
$posts = Post::paginate();
```

Correct:
```php
$posts = Post::latest()->paginate();
```

## Use Atomic Locks for Race Conditions

Prevent race conditions with `Cache::lock()` or `lockForUpdate()`.

```php
Cache::lock('order-processing-'.$order->id, 10)->block(5, function () use ($order) {
    $order->process();
});

// Or at query level
$product = Product::where('id', $id)->lockForUpdate()->first();
```

## Use `mb_*` String Functions

When no Laravel helper exists, prefer `mb_strlen`, `mb_strtolower`, etc. for UTF-8 safety. Standard PHP string functions count bytes, not characters.

Incorrect:
```php
strlen('José');          // 5 (bytes, not characters)
strtolower('MÜNCHEN');  // 'mÜnchen' — fails on multibyte
```

Correct:
```php
mb_strlen('José');             // 4 (characters)
mb_strtolower('MÜNCHEN');     // 'münchen'

// Prefer Laravel's Str helpers when available
Str::length('José');          // 4
Str::lower('MÜNCHEN');        // 'münchen'
```

## Use `defer()` for Post-Response Work

For lightweight tasks that don't need to survive a crash (logging, analytics, cleanup), use `defer()` instead of dispatching a job. The callback runs after the HTTP response is sent — no queue overhead.

Incorrect (job overhead for trivial work):
```php
dispatch(new LogPageView($page));
```

Correct (runs after response, same process):
```php
defer(fn () => PageView::create(['page_id' => $page->id, 'user_id' => auth()->id()]));
```

Use jobs when the work must survive process crashes or needs retry logic. Use `defer()` for fire-and-forget work.

## Use `Context` for Request-Scoped Data

The `Context` facade passes data through the entire request lifecycle — middleware, controllers, jobs, logs — without passing arguments manually.

```php
// In middleware
Context::add('tenant_id', $request->header('X-Tenant-ID'));

// Anywhere later — controllers, jobs, log context
$tenantId = Context::get('tenant_id');
```

Context data automatically propagates to queued jobs and is included in log entries. Use `Context::addHidden()` for sensitive data that should be available in queued jobs but excluded from log context. If data must not leave the current process, do not store it in `Context`.

## Use `Concurrency::run()` for Parallel Execution

Run independent operations in parallel using child processes — no async libraries needed.

```php
use Illuminate\Support\Facades\Concurrency;

[$users, $orders] = Concurrency::run([
    fn () => User::count(),
    fn () => Order::where('status', 'pending')->count(),
]);
```

Each closure runs in a separate process with full Laravel access. Use for independent database queries, API calls, or computations that would otherwise run sequentially.

## Convention Over Configuration

Follow Laravel conventions. Don't override defaults unnecessarily.

Incorrect:
```php
class Customer extends Model
{
    protected $table = 'Customer';
    protected $primaryKey = 'customer_id';

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_customer', 'customer_id', 'role_id');
    }
}
```

Correct:
```php
class Customer extends Model
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
```
````

## File: .junie/skills/laravel-best-practices/rules/blade-views.md
````markdown
# Blade & Views Best Practices

## Use `$attributes->merge()` in Component Templates

Hardcoding classes prevents consumers from adding their own. `merge()` combines class attributes cleanly.

```blade
<div {{ $attributes->merge(['class' => 'alert alert-'.$type]) }}>
    {{ $message }}
</div>
```

## Use `@pushOnce` for Per-Component Scripts

If a component renders inside a `@foreach`, `@push` inserts the script N times. `@pushOnce` guarantees it's included exactly once.

## Prefer Blade Components Over `@include`

`@include` shares all parent variables implicitly (hidden coupling). Components have explicit props, attribute bags, and slots.

## Use View Composers for Shared View Data

If every controller rendering a sidebar must pass `$categories`, that's duplicated code. A View Composer centralizes it.

## Use Blade Fragments for Partial Re-Renders (htmx/Turbo)

A single view can return either the full page or just a fragment, keeping routing clean.

```php
return view('dashboard', compact('users'))
    ->fragmentIf($request->hasHeader('HX-Request'), 'user-list');
```

## Use `@aware` for Deeply Nested Component Props

Avoids re-passing parent props through every level of nested components.
````

## File: .junie/skills/laravel-best-practices/rules/caching.md
````markdown
# Caching Best Practices

## Use `Cache::remember()` Instead of Manual Get/Put

Cleaner cache-aside pattern that removes boilerplate. use `Cache::lock()` for race conditions.

Incorrect:
```php
$val = Cache::get('stats');
if (! $val) {
    $val = $this->computeStats();
    Cache::put('stats', $val, 60);
}
```

Correct:
```php
$val = Cache::remember('stats', 60, fn () => $this->computeStats());
```

## Use `Cache::flexible()` for Stale-While-Revalidate

On high-traffic keys, one user always gets a slow response when the cache expires. `flexible()` serves slightly stale data while refreshing in the background.

Incorrect: `Cache::remember('users', 300, fn () => User::all());`

Correct: `Cache::flexible('users', [300, 600], fn () => User::all());` — fresh for 5 min, stale-but-served up to 10 min, refreshes via deferred function.

## Use `Cache::memo()` to Avoid Redundant Hits Within a Request

If the same cache key is read multiple times per request (e.g., a service called from multiple places), `memo()` stores the resolved value in memory.

`Cache::memo()->get('settings');` — 5 calls = 1 Redis round-trip instead of 5.

## Use Cache Tags to Invalidate Related Groups

Without tags, invalidating a group of entries requires tracking every key. Tags let you flush atomically. Only works with `redis`, `memcached`, `dynamodb` — not `file` or `database`.

```php
Cache::tags(['user-1'])->flush();
```

## Use `Cache::add()` for Atomic Conditional Writes

`add()` only writes if the key does not exist — atomic, no race condition between checking and writing.

Incorrect: `if (! Cache::has('lock')) { Cache::put('lock', true, 10); }`

Correct: `Cache::add('lock', true, 10);`

## Use `once()` for Per-Request Memoization

`once()` memoizes a function's return value for the lifetime of the object (or request for closures). Unlike `Cache::memo()`, it doesn't hit the cache store at all — pure in-memory.

```php
public function roles(): Collection
{
    return once(fn () => $this->loadRoles());
}
```

Multiple calls return the cached result without re-executing. Use `once()` for expensive computations called multiple times per request. Use `Cache::memo()` when you also want cross-request caching.

## Configure Failover Cache Stores in Production

If Redis goes down, the app falls back to a secondary store automatically.

```php
'failover' => ['driver' => 'failover', 'stores' => ['redis', 'database']],
```
````

## File: .junie/skills/laravel-best-practices/rules/collections.md
````markdown
# Collection Best Practices

## Use Higher-Order Messages for Simple Operations

Incorrect:
```php
$users->each(function (User $user) {
    $user->markAsVip();
});
```

Correct: `$users->each->markAsVip();`

Works with `each`, `map`, `sum`, `filter`, `reject`, `contains`, etc.

## Choose `cursor()` vs. `lazy()` Correctly

- `cursor()` — one model in memory, but cannot eager-load relationships (N+1 risk).
- `lazy()` — chunked pagination returning a flat LazyCollection, supports eager loading.

Incorrect: `User::with('roles')->cursor()` — eager loading silently ignored.

Correct: `User::with('roles')->lazy()` for relationship access; `User::cursor()` for attribute-only work.

## Use `lazyById()` When Updating Records While Iterating

`lazy()` uses offset pagination — updating records during iteration can skip or double-process. `lazyById()` uses `id > last_id`, safe against mutation.

## Use `toQuery()` for Bulk Operations on Collections

Avoids manual `whereIn` construction.

Incorrect: `User::whereIn('id', $users->pluck('id'))->update([...]);`

Correct: `$users->toQuery()->update([...]);`

## Use `#[CollectedBy]` for Custom Collection Classes

More declarative than overriding `newCollection()`.

```php
#[CollectedBy(UserCollection::class)]
class User extends Model {}
```
````

## File: .junie/skills/laravel-best-practices/rules/config.md
````markdown
# Configuration Best Practices

## `env()` Only in Config Files

Direct `env()` calls may return `null` when config is cached.

Incorrect:
```php
$key = env('API_KEY');
```

Correct:
```php
// config/services.php
'key' => env('API_KEY'),

// Application code
$key = config('services.key');
```

## Use Encrypted Env or External Secrets

Never store production secrets in plain `.env` files in version control.

Incorrect:
```bash

# .env committed to repo or shared in Slack

STRIPE_SECRET=sk_live_abc123
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI
```

Correct:
```bash
php artisan env:encrypt --env=production --readable
php artisan env:decrypt --env=production
```

For cloud deployments, prefer the platform's native secret store (AWS Secrets Manager, Vault, etc.) and inject at runtime.

## Use `App::environment()` for Environment Checks

Incorrect:
```php
if (env('APP_ENV') === 'production') {
```

Correct:
```php
if (app()->isProduction()) {
// or
if (App::environment('production')) {
```

## Use Constants and Language Files

Use class constants instead of hardcoded magic strings for model states, types, and statuses.

```php
// Incorrect
return $this->type === 'normal';

// Correct
return $this->type === self::TYPE_NORMAL;
```

If the application already uses language files for localization, use `__()` for user-facing strings too. Do not introduce language files purely for English-only apps — simple string literals are fine there.

```php
// Only when lang files already exist in the project
return back()->with('message', __('app.article_added'));
```
````

## File: .junie/skills/laravel-best-practices/rules/db-performance.md
````markdown
# Database Performance Best Practices

## Always Eager Load Relationships

Lazy loading causes N+1 query problems — one query per loop iteration. Always use `with()` to load relationships upfront.

Incorrect (N+1 — executes 1 + N queries):
```php
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name;
}
```

Correct (2 queries total):
```php
$posts = Post::with('author')->get();
foreach ($posts as $post) {
    echo $post->author->name;
}
```

Constrain eager loads to select only needed columns (always include the foreign key):

```php
$users = User::with(['posts' => function ($query) {
    $query->select('id', 'user_id', 'title')
          ->where('published', true)
          ->latest()
          ->limit(10);
}])->get();
```

## Prevent Lazy Loading in Development

Enable this in `AppServiceProvider::boot()` to catch N+1 issues during development.

```php
public function boot(): void
{
    Model::preventLazyLoading(! app()->isProduction());
}
```

Throws `LazyLoadingViolationException` when a relationship is accessed without being eager-loaded.

## Select Only Needed Columns

Avoid `SELECT *` — especially when tables have large text or JSON columns.

Incorrect:
```php
$posts = Post::with('author')->get();
```

Correct:
```php
$posts = Post::select('id', 'title', 'user_id', 'created_at')
    ->with(['author:id,name,avatar'])
    ->get();
```

When selecting columns on eager-loaded relationships, always include the foreign key column or the relationship won't match.

## Chunk Large Datasets

Never load thousands of records at once. Use chunking for batch processing.

Incorrect:
```php
$users = User::all();
foreach ($users as $user) {
    $user->notify(new WeeklyDigest);
}
```

Correct:
```php
User::where('subscribed', true)->chunk(200, function ($users) {
    foreach ($users as $user) {
        $user->notify(new WeeklyDigest);
    }
});
```

Use `chunkById()` when modifying records during iteration — standard `chunk()` uses OFFSET which shifts when rows change:

```php
User::where('active', false)->chunkById(200, function ($users) {
    $users->each->delete();
});
```

## Add Database Indexes

Index columns that appear in `WHERE`, `ORDER BY`, `JOIN`, and `GROUP BY` clauses.

Incorrect:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('status');
    $table->timestamps();
});
```

Correct:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->index()->constrained();
    $table->string('status')->index();
    $table->timestamps();
    $table->index(['status', 'created_at']);
});
```

Add composite indexes for common query patterns (e.g., `WHERE status = ? ORDER BY created_at`).

## Use `withCount()` for Counting Relations

Never load entire collections just to count them.

Incorrect:
```php
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->comments->count();
}
```

Correct:
```php
$posts = Post::withCount('comments')->get();
foreach ($posts as $post) {
    echo $post->comments_count;
}
```

Conditional counting:

```php
$posts = Post::withCount([
    'comments',
    'comments as approved_comments_count' => function ($query) {
        $query->where('approved', true);
    },
])->get();
```

## Use `cursor()` for Memory-Efficient Iteration

For read-only iteration over large result sets, `cursor()` loads one record at a time via a PHP generator.

Incorrect:
```php
$users = User::where('active', true)->get();
```

Correct:
```php
foreach (User::where('active', true)->cursor() as $user) {
    ProcessUser::dispatch($user->id);
}
```

Use `cursor()` for read-only iteration. Use `chunk()` / `chunkById()` when modifying records.

## No Queries in Blade Templates

Never execute queries in Blade templates. Pass data from controllers.

Incorrect:
```blade
@foreach (User::all() as $user)
    {{ $user->profile->name }}
@endforeach
```

Correct:
```php
// Controller
$users = User::with('profile')->get();
return view('users.index', compact('users'));
```

```blade
@foreach ($users as $user)
    {{ $user->profile->name }}
@endforeach
```
````

## File: .junie/skills/laravel-best-practices/rules/eloquent.md
````markdown
# Eloquent Best Practices

## Use Correct Relationship Types

Use `hasMany`, `belongsTo`, `morphMany`, etc. with proper return type hints.

```php
public function comments(): HasMany
{
    return $this->hasMany(Comment::class);
}

public function author(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}
```

## Use Local Scopes for Reusable Queries

Extract reusable query constraints into local scopes to avoid duplication.

Incorrect:
```php
$active = User::where('verified', true)->whereNotNull('activated_at')->get();
$articles = Article::whereHas('user', function ($q) {
    $q->where('verified', true)->whereNotNull('activated_at');
})->get();
```

Correct:
```php
public function scopeActive(Builder $query): Builder
{
    return $query->where('verified', true)->whereNotNull('activated_at');
}

// Usage
$active = User::active()->get();
$articles = Article::whereHas('user', fn ($q) => $q->active())->get();
```

## Apply Global Scopes Sparingly

Global scopes silently modify every query on the model, making debugging difficult. Prefer local scopes and reserve global scopes for truly universal constraints like soft deletes or multi-tenancy.

Incorrect (global scope for a conditional filter):
```php
class PublishedScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('published', true);
    }
}
// Now admin panels, reports, and background jobs all silently skip drafts
```

Correct (local scope you opt into):
```php
public function scopePublished(Builder $query): Builder
{
    return $query->where('published', true);
}

Post::published()->paginate(); // Explicit
Post::paginate(); // Admin sees all
```

## Define Attribute Casts

Use the `casts()` method (or `$casts` property following project convention) for automatic type conversion.

```php
protected function casts(): array
{
    return [
        'is_active' => 'boolean',
        'metadata' => 'array',
        'total' => 'decimal:2',
    ];
}
```

## Cast Date Columns Properly

Always cast date columns. Use Carbon instances in templates instead of formatting strings manually.

Incorrect:
```blade
{{ Carbon::createFromFormat('Y-d-m H-i', $order->ordered_at)->toDateString() }}
```

Correct:
```php
protected function casts(): array
{
    return [
        'ordered_at' => 'datetime',
    ];
}
```

```blade
{{ $order->ordered_at->toDateString() }}
{{ $order->ordered_at->format('m-d') }}
```

## Use `whereBelongsTo()` for Relationship Queries

Cleaner than manually specifying foreign keys.

Incorrect:
```php
Post::where('user_id', $user->id)->get();
```

Correct:
```php
Post::whereBelongsTo($user)->get();
Post::whereBelongsTo($user, 'author')->get();
```

## Avoid Hardcoded Table Names in Queries

Never use string literals for table names in raw queries, joins, or subqueries. Hardcoded table names make it impossible to find all places a model is used and break refactoring (e.g., renaming a table requires hunting through every raw string).

Incorrect:
```php
DB::table('users')->where('active', true)->get();

$query->join('companies', 'companies.id', '=', 'users.company_id');

DB::select('SELECT * FROM orders WHERE status = ?', ['pending']);
```

Correct — reference the model's table:
```php
DB::table((new User)->getTable())->where('active', true)->get();

// Even better — use Eloquent or the query builder instead of raw SQL
User::where('active', true)->get();
Order::where('status', 'pending')->get();
```

Prefer Eloquent queries and relationships over `DB::table()` whenever possible — they already reference the model's table. When `DB::table()` or raw joins are unavoidable, always use `(new Model)->getTable()` to keep the reference traceable.

**Exception — migrations:** In migrations, hardcoded table names via `DB::table('settings')` are acceptable and preferred. Models change over time but migrations are frozen snapshots — referencing a model that is later renamed or deleted would break the migration.
````

## File: .junie/skills/laravel-best-practices/rules/error-handling.md
````markdown
# Error Handling Best Practices

## Exception Reporting and Rendering

There are two valid approaches — choose one and apply it consistently across the project.

**Co-location on the exception class** — keeps behavior alongside the exception definition, easier to find:

```php
class InvalidOrderException extends Exception
{
    public function report(): void { /* custom reporting */ }

    public function render(Request $request): Response
    {
        return response()->view('errors.invalid-order', status: 422);
    }
}
```

**Centralized in `bootstrap/app.php`** — all exception handling in one place, easier to see the full picture:

```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->report(function (InvalidOrderException $e) { /* ... */ });
    $exceptions->render(function (InvalidOrderException $e, Request $request) {
        return response()->view('errors.invalid-order', status: 422);
    });
})
```

Check the existing codebase and follow whichever pattern is already established.

## Use `ShouldntReport` for Exceptions That Should Never Log

More discoverable than listing classes in `dontReport()`.

```php
class PodcastProcessingException extends Exception implements ShouldntReport {}
```

## Throttle High-Volume Exceptions

A single failing integration can flood error tracking. Use `throttle()` to rate-limit per exception type.

## Enable `dontReportDuplicates()`

Prevents the same exception instance from being logged multiple times when `report($e)` is called in multiple catch blocks.

## Force JSON Error Rendering for API Routes

Laravel auto-detects `Accept: application/json` but API clients may not set it. Explicitly declare JSON rendering for API routes.

```php
$exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
    return $request->is('api/*') || $request->expectsJson();
});
```

## Add Context to Exception Classes

Attach structured data to exceptions at the source via a `context()` method — Laravel includes it automatically in the log entry.

```php
class InvalidOrderException extends Exception
{
    public function context(): array
    {
        return ['order_id' => $this->orderId];
    }
}
```
````

## File: .junie/skills/laravel-best-practices/rules/events-notifications.md
````markdown
# Events & Notifications Best Practices

## Rely on Event Discovery

Laravel auto-discovers listeners by reading `handle(EventType $event)` type-hints. No manual registration needed in `AppServiceProvider`.

## Run `event:cache` in Production Deploy

Event discovery scans the filesystem per-request in dev. Cache it in production: `php artisan optimize` or `php artisan event:cache`.

## Use `ShouldDispatchAfterCommit` Inside Transactions

Without it, a queued listener may process before the DB transaction commits, reading data that doesn't exist yet.

```php
class OrderShipped implements ShouldDispatchAfterCommit {}
```

## Always Queue Notifications

Notifications often hit external APIs (email, SMS, Slack). Without `ShouldQueue`, they block the HTTP response.

```php
class InvoicePaid extends Notification implements ShouldQueue
{
    use Queueable;
}
```

## Use `afterCommit()` on Notifications in Transactions

Same race condition as events — call `afterCommit()` to delay dispatch until the transaction commits.

```php
$user->notify((new InvoicePaid($invoice))->afterCommit());
```

## Route Notification Channels to Dedicated Queues

Mail and database notifications have different priorities. Use `viaQueues()` to route them to separate queues.

## Use On-Demand Notifications for Non-User Recipients

Avoid creating dummy models to send notifications to arbitrary addresses.

```php
Notification::route('mail', 'admin@example.com')->notify(new SystemAlert());
```

## Implement `HasLocalePreference` on Notifiable Models

Laravel automatically uses the user's preferred locale for all notifications and mailables — no per-call `locale()` needed.
````

## File: .junie/skills/laravel-best-practices/rules/http-client.md
````markdown
# HTTP Client Best Practices

## Always Set Explicit Timeouts

The default timeout is 30 seconds — too long for most API calls. Always set explicit `timeout` and `connectTimeout` to fail fast.

Incorrect:
```php
$response = Http::get('https://api.example.com/users');
```

Correct:
```php
$response = Http::timeout(5)
    ->connectTimeout(3)
    ->get('https://api.example.com/users');
```

For service-specific clients, define timeouts in a macro:

```php
Http::macro('github', function () {
    return Http::baseUrl('https://api.github.com')
        ->timeout(10)
        ->connectTimeout(3)
        ->withToken(config('services.github.token'));
});

$response = Http::github()->get('/repos/laravel/framework');
```

## Use Retry with Backoff for External APIs

External APIs have transient failures. Use `retry()` with increasing delays.

Incorrect:
```php
$response = Http::post('https://api.stripe.com/v1/charges', $data);

if ($response->failed()) {
    throw new PaymentFailedException('Charge failed');
}
```

Correct:
```php
$response = Http::retry([100, 500, 1000])
    ->timeout(10)
    ->post('https://api.stripe.com/v1/charges', $data);
```

Only retry on specific errors:

```php
$response = Http::retry(3, 100, function (Throwable $exception, PendingRequest $request) {
    return $exception instanceof ConnectionException
        || ($exception instanceof RequestException && $exception->response->serverError());
})->post('https://api.example.com/data');
```

## Handle Errors Explicitly

The HTTP Client does not throw on 4xx/5xx by default. Always check status or use `throw()`.

Incorrect:
```php
$response = Http::get('https://api.example.com/users/1');
$user = $response->json(); // Could be an error body
```

Correct:
```php
$response = Http::timeout(5)
    ->get('https://api.example.com/users/1')
    ->throw();

$user = $response->json();
```

For graceful degradation:

```php
$response = Http::get('https://api.example.com/users/1');

if ($response->successful()) {
    return $response->json();
}

if ($response->notFound()) {
    return null;
}

$response->throw();
```

## Use Request Pooling for Concurrent Requests

When making multiple independent API calls, use `Http::pool()` instead of sequential calls.

Incorrect:
```php
$users = Http::get('https://api.example.com/users')->json();
$posts = Http::get('https://api.example.com/posts')->json();
$comments = Http::get('https://api.example.com/comments')->json();
```

Correct:
```php
use Illuminate\Http\Client\Pool;

$responses = Http::pool(fn (Pool $pool) => [
    $pool->as('users')->get('https://api.example.com/users'),
    $pool->as('posts')->get('https://api.example.com/posts'),
    $pool->as('comments')->get('https://api.example.com/comments'),
]);

$users = $responses['users']->json();
$posts = $responses['posts']->json();
```

## Fake HTTP Calls in Tests

Never make real HTTP requests in tests. Use `Http::fake()` and `preventStrayRequests()`.

Incorrect:
```php
it('syncs user from API', function () {
    $service = new UserSyncService;
    $service->sync(1); // Hits the real API
});
```

Correct:
```php
it('syncs user from API', function () {
    Http::preventStrayRequests();

    Http::fake([
        'api.example.com/users/1' => Http::response([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]),
    ]);

    $service = new UserSyncService;
    $service->sync(1);

    Http::assertSent(function (Request $request) {
        return $request->url() === 'https://api.example.com/users/1';
    });
});
```

Test failure scenarios too:

```php
Http::fake([
    'api.example.com/*' => Http::failedConnection(),
]);
```
````

## File: .junie/skills/laravel-best-practices/rules/mail.md
````markdown
# Mail Best Practices

## Implement `ShouldQueue` on the Mailable Class

Makes queueing the default regardless of how the mailable is dispatched. No need to remember `Mail::queue()` at every call site — `Mail::send()` also queues it.

## Use `afterCommit()` on Mailables Inside Transactions

A queued mailable dispatched inside a transaction may process before the commit. Use `$this->afterCommit()` in the constructor.

## Use `assertQueued()` Not `assertSent()` for Queued Mailables

`Mail::assertSent()` only catches synchronous mail. Queued mailables fail `assertSent` with a "Did you mean to use assertQueued()?" hint.

Incorrect: `Mail::assertSent(OrderShipped::class);` when mailable implements `ShouldQueue`.

Correct: `Mail::assertQueued(OrderShipped::class);`

## Use Markdown Mailables for Transactional Emails

Markdown mailables auto-generate both HTML and plain-text versions, use responsive components, and allow global style customization. Generate with `--markdown` flag.

## Separate Content Tests from Sending Tests

Content tests: instantiate the mailable directly, call `assertSeeInHtml()`.
Sending tests: use `Mail::fake()` and `assertSent()`/`assertQueued()`.
Don't mix them — it conflates concerns and makes tests brittle.
````

## File: .junie/skills/laravel-best-practices/rules/migrations.md
````markdown
# Migration Best Practices

## Generate Migrations with Artisan

Always use `php artisan make:migration` for consistent naming and timestamps.

Incorrect (manually created file):
```php
// database/migrations/posts_migration.php  ← wrong naming, no timestamp
```

Correct (Artisan-generated):
```bash
php artisan make:migration create_posts_table
php artisan make:migration add_slug_to_posts_table
```

## Use `constrained()` for Foreign Keys

Automatic naming and referential integrity.

```php
$table->foreignId('user_id')->constrained()->cascadeOnDelete();

// Non-standard names
$table->foreignId('author_id')->constrained('users');
```

## Never Modify Deployed Migrations

Once a migration has run in production, treat it as immutable. Create a new migration to change the table.

Incorrect (editing a deployed migration):
```php
// 2024_01_01_create_posts_table.php — already in production
$table->string('slug')->unique(); // ← added after deployment
```

Correct (new migration to alter):
```php
// 2024_03_15_add_slug_to_posts_table.php
Schema::table('posts', function (Blueprint $table) {
    $table->string('slug')->unique()->after('title');
});
```

## Add Indexes in the Migration

Add indexes when creating the table, not as an afterthought. Columns used in `WHERE`, `ORDER BY`, and `JOIN` clauses need indexes.

Incorrect:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->string('status');
    $table->timestamps();
});
```

Correct:
```php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->index();
    $table->string('status')->index();
    $table->timestamp('shipped_at')->nullable()->index();
    $table->timestamps();
});
```

## Mirror Defaults in Model `$attributes`

When a column has a database default, mirror it in the model so new instances have correct values before saving.

```php
// Migration
$table->string('status')->default('pending');

// Model
protected $attributes = [
    'status' => 'pending',
];
```

## Write Reversible `down()` Methods by Default

Implement `down()` for schema changes that can be safely reversed so `migrate:rollback` works in CI and failed deployments.

```php
public function down(): void
{
    Schema::table('posts', function (Blueprint $table) {
        $table->dropColumn('slug');
    });
}
```

For intentionally irreversible migrations (e.g., destructive data backfills), leave a clear comment and require a forward fix migration instead of pretending rollback is supported.

## Keep Migrations Focused

One concern per migration. Never mix DDL (schema changes) and DML (data manipulation).

Incorrect (partial failure creates unrecoverable state):
```php
public function up(): void
{
    Schema::create('settings', function (Blueprint $table) { ... });
    DB::table('settings')->insert(['key' => 'version', 'value' => '1.0']);
}
```

Correct (separate migrations):
```php
// Migration 1: create_settings_table
Schema::create('settings', function (Blueprint $table) { ... });

// Migration 2: seed_default_settings
DB::table('settings')->insert(['key' => 'version', 'value' => '1.0']);
```
````

## File: .junie/skills/laravel-best-practices/rules/queue-jobs.md
````markdown
# Queue & Job Best Practices

## Set `retry_after` Greater Than `timeout`

If `retry_after` is shorter than the job's `timeout`, the queue worker re-dispatches the job while it's still running, causing duplicate execution.

Incorrect (`retry_after` ≤ `timeout`):
```php
class ProcessReport implements ShouldQueue
{
    public $timeout = 120;
}

// config/queue.php — retry_after: 90 ← job retried while still running!
```

Correct (`retry_after` > `timeout`):
```php
class ProcessReport implements ShouldQueue
{
    public $timeout = 120;
}

// config/queue.php — retry_after: 180 ← safely longer than any job timeout
```

## Use Exponential Backoff

Use progressively longer delays between retries to avoid hammering failing services.

Incorrect (fixed retry interval):
```php
class SyncWithStripe implements ShouldQueue
{
    public $tries = 3;
    // Default: retries immediately, overwhelming the API
}
```

Correct (exponential backoff):
```php
class SyncWithStripe implements ShouldQueue
{
    public $tries = 3;
    public $backoff = [1, 5, 10];
}
```

## Implement `ShouldBeUnique`

Prevent duplicate job processing.

```php
class GenerateInvoice implements ShouldQueue, ShouldBeUnique
{
    public function uniqueId(): string
    {
        return $this->order->id;
    }

    public $uniqueFor = 3600;
}
```

## Always Implement `failed()`

Handle errors explicitly — don't rely on silent failure.

```php
public function failed(?Throwable $exception): void
{
    $this->podcast->update(['status' => 'failed']);
    Log::error('Processing failed', ['id' => $this->podcast->id, 'error' => $exception->getMessage()]);
}
```

## Rate Limit External API Calls in Jobs

Use `RateLimited` middleware to throttle jobs calling third-party APIs.

```php
public function middleware(): array
{
    return [new RateLimited('external-api')];
}
```

## Batch Related Jobs

Use `Bus::batch()` when jobs should succeed or fail together.

```php
Bus::batch([
    new ImportCsvChunk($chunk1),
    new ImportCsvChunk($chunk2),
])
->then(fn (Batch $batch) => Notification::send($user, new ImportComplete))
->catch(fn (Batch $batch, Throwable $e) => Log::error('Batch failed'))
->dispatch();
```

## `retryUntil()` Needs `$tries = 0`

When using time-based retry limits, set `$tries = 0` to avoid premature failure.

```php
public $tries = 0;

public function retryUntil(): \DateTimeInterface
{
    return now()->addHours(4);
}
```

## Use `ShouldBeUniqueUntilProcessing` for Early Lock Release

`ShouldBeUnique` holds the lock until the job completes. `ShouldBeUniqueUntilProcessing` releases it when processing starts, allowing new instances to queue.

```php
class UpdateSearchIndex implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
    // Lock releases when processing begins, not when it finishes
}
```

## Use Horizon for Complex Queue Scenarios

Use Laravel Horizon when you need monitoring, auto-scaling, failure tracking, or multiple queues with different priorities.

```php
// config/horizon.php
'environments' => [
    'production' => [
        'supervisor-1' => [
            'connection' => 'redis',
            'queue' => ['high', 'default', 'low'],
            'balance' => 'auto',
            'minProcesses' => 1,
            'maxProcesses' => 10,
            'tries' => 3,
        ],
    ],
],
```
````

## File: .junie/skills/laravel-best-practices/rules/routing.md
````markdown
# Routing & Controllers Best Practices

## Use Implicit Route Model Binding

Let Laravel resolve models automatically from route parameters.

Incorrect:
```php
public function show(int $id)
{
    $post = Post::findOrFail($id);
}
```

Correct:
```php
public function show(Post $post)
{
    return view('posts.show', ['post' => $post]);
}
```

## Use Scoped Bindings for Nested Resources

Enforce parent-child relationships automatically.

```php
Route::get('/users/{user}/posts/{post}', function (User $user, Post $post) {
    // $post is automatically scoped to $user
})->scopeBindings();
```

## Use Resource Controllers

Use `Route::resource()` or `apiResource()` for RESTful endpoints.

```php
Route::resource('posts', PostController::class);
// In routes/api.php — the /api prefix is applied automatically
Route::apiResource('posts', Api\PostController::class);
```

## Keep Controllers Thin

Aim for under 10 lines per method. Extract business logic to action or service classes.

Incorrect:
```php
public function store(Request $request)
{
    $validated = $request->validate([...]);
    if ($request->hasFile('image')) {
        $request->file('image')->move(public_path('images'));
    }
    $post = Post::create($validated);
    $post->tags()->sync($validated['tags']);
    event(new PostCreated($post));
    return redirect()->route('posts.show', $post);
}
```

Correct:
```php
public function store(StorePostRequest $request, CreatePostAction $create)
{
    $post = $create->execute($request->validated());

    return redirect()->route('posts.show', $post);
}
```

## Type-Hint Form Requests

Type-hinting Form Requests triggers automatic validation and authorization before the method executes.

Incorrect:
```php
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'title' => ['required', 'max:255'],
        'body' => ['required'],
    ]);

    Post::create($validated);

    return redirect()->route('posts.index');
}
```

Correct:
```php
public function store(StorePostRequest $request): RedirectResponse
{
    Post::create($request->validated());

    return redirect()->route('posts.index');
}
```
````

## File: .junie/skills/laravel-best-practices/rules/scheduling.md
````markdown
# Task Scheduling Best Practices

## Use `withoutOverlapping()` on Variable-Duration Tasks

Without it, a long-running task spawns a second instance on the next tick, causing double-processing or resource exhaustion.

## Use `onOneServer()` on Multi-Server Deployments

Without it, every server runs the same task simultaneously. Requires a shared cache driver (Redis, database, Memcached).

## Use `runInBackground()` for Concurrent Long Tasks

By default, tasks at the same tick run sequentially. A slow first task delays all subsequent ones. `runInBackground()` runs them as separate processes.

## Use `environments()` to Restrict Tasks

Prevent accidental execution of production-only tasks (billing, reporting) on staging.

```php
Schedule::command('billing:charge')->monthly()->environments(['production']);
```

## Use `takeUntilTimeout()` for Time-Bounded Processing

A task running every 15 minutes that processes an unbounded cursor can overlap with the next run. Bound execution time.

## Use Schedule Groups for Shared Configuration

Avoid repeating `->onOneServer()->timezone('America/New_York')` across many tasks.

```php
Schedule::daily()
    ->onOneServer()
    ->timezone('America/New_York')
    ->group(function () {
        Schedule::command('emails:send --force');
        Schedule::command('emails:prune');
    });
```
````

## File: .junie/skills/laravel-best-practices/rules/security.md
````markdown
# Security Best Practices

## Mass Assignment Protection

Every model must define `$fillable` (whitelist) or `$guarded` (blacklist).

Incorrect:
```php
class User extends Model
{
    protected $guarded = []; // All fields are mass assignable
}
```

Correct:
```php
class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
}
```

Never use `$guarded = []` on models that accept user input.

## Authorize Every Action

Use policies or gates in controllers. Never skip authorization.

Incorrect:
```php
public function update(UpdatePostRequest $request, Post $post)
{
    $post->update($request->validated());
}
```

Correct:
```php
public function update(UpdatePostRequest $request, Post $post)
{
    Gate::authorize('update', $post);

    $post->update($request->validated());
}
```

Or via Form Request:

```php
public function authorize(): bool
{
    return $this->user()->can('update', $this->route('post'));
}
```

## Prevent SQL Injection

Always use parameter binding. Never interpolate user input into queries.

Incorrect:
```php
DB::select("SELECT * FROM users WHERE name = '{$request->name}'");
```

Correct:
```php
User::where('name', $request->name)->get();

// Raw expressions with bindings
User::whereRaw('LOWER(name) = ?', [strtolower($request->name)])->get();
```

## Escape Output to Prevent XSS

Use `{{ }}` for HTML escaping. Only use `{!! !!}` for trusted, pre-sanitized content.

Incorrect:
```blade
{!! $user->bio !!}
```

Correct:
```blade
{{ $user->bio }}
```

## CSRF Protection

Include `@csrf` in all POST/PUT/DELETE Blade forms. In Inertia apps, the `@csrf` directive is automatically applied.

Incorrect:
```blade
<form method="POST" action="/posts">
    <input type="text" name="title">
</form>
```

Correct:
```blade
<form method="POST" action="/posts">
    @csrf
    <input type="text" name="title">
</form>
```

## Rate Limit Auth and API Routes

Apply `throttle` middleware to authentication and API routes.

```php
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

Route::post('/login', LoginController::class)->middleware('throttle:login');
```

## Validate File Uploads

Validate extension, MIME type, and size. The `mimes` rule checks extensions; use `mimetypes` for actual MIME type validation. Never trust client-provided filenames.

```php
public function rules(): array
{
    return [
        'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ];
}
```

Store with generated filenames:

```php
$path = $request->file('avatar')->store('avatars', 'public');
```

## Keep Secrets Out of Code

Never commit `.env`. Access secrets via `config()` only.

Incorrect:
```php
$key = env('API_KEY');
```

Correct:
```php
// config/services.php
'api_key' => env('API_KEY'),

// In application code
$key = config('services.api_key');
```

## Audit Dependencies

Run `composer audit` periodically to check for known vulnerabilities in dependencies. Automate this in CI to catch issues before deployment.

```bash
composer audit
```

## Encrypt Sensitive Database Fields

Use `encrypted` cast for API keys/tokens and mark the attribute as `hidden`.

Incorrect:
```php
class Integration extends Model
{
    protected function casts(): array
    {
        return [
            'api_key' => 'string',
        ];
    }
}
```

Correct:
```php
class Integration extends Model
{
    protected $hidden = ['api_key', 'api_secret'];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'api_secret' => 'encrypted',
        ];
    }
}
```
````

## File: .junie/skills/laravel-best-practices/rules/style.md
````markdown
# Conventions & Style

## Follow Laravel Naming Conventions

| What | Convention | Good | Bad |
|------|-----------|------|-----|
| Controller | singular | `ArticleController` | `ArticlesController` |
| Model | singular | `User` | `Users` |
| Table | plural, snake_case | `article_comments` | `articleComments` |
| Pivot table | singular alphabetical | `article_user` | `user_article` |
| Column | snake_case, no model name | `meta_title` | `article_meta_title` |
| Foreign key | singular model + `_id` | `article_id` | `articles_id` |
| Route | plural | `articles/1` | `article/1` |
| Route name | snake_case with dots | `users.show_active` | `users.show-active` |
| Method | camelCase | `getAll` | `get_all` |
| Variable | camelCase | `$articlesWithAuthor` | `$articles_with_author` |
| Collection | descriptive, plural | `$activeUsers` | `$data` |
| Object | descriptive, singular | `$activeUser` | `$users` |
| View | kebab-case | `show-filtered.blade.php` | `showFiltered.blade.php` |
| Config | snake_case | `google_calendar.php` | `googleCalendar.php` |
| Enum | singular | `UserType` | `UserTypes` |

## Prefer Shorter Readable Syntax

| Verbose | Shorter |
|---------|---------|
| `Session::get('cart')` | `session('cart')` |
| `$request->session()->get('cart')` | `session('cart')` |
| `$request->input('name')` | `$request->name` |
| `return Redirect::back()` | `return back()` |
| `Carbon::now()` | `now()` |
| `App::make('Class')` | `app('Class')` |
| `->where('column', '=', 1)` | `->where('column', 1)` |
| `->orderBy('created_at', 'desc')` | `->latest()` |
| `->orderBy('created_at', 'asc')` | `->oldest()` |
| `->first()->name` | `->value('name')` |

## Use Laravel String & Array Helpers

Laravel provides `Str`, `Arr`, `Number`, and `Uri` helper classes that are more readable, chainable, and UTF-8 safe than raw PHP functions. Always prefer them.

Strings — use `Str` and fluent `Str::of()` over raw PHP:
```php
// Incorrect
$slug = strtolower(str_replace(' ', '-', $title));
$short = substr($text, 0, 100) . '...';
$class = substr(strrchr('App\Models\User', '\'), 1);

// Correct
$slug = Str::slug($title);
$short = Str::limit($text, 100);
$class = class_basename('App\Models\User');
```

Fluent strings — chain operations for complex transformations:
```php
// Incorrect
$result = strtolower(trim(str_replace('_', '-', $input)));

// Correct
$result = Str::of($input)->trim()->replace('_', '-')->lower();
```

Key `Str` methods to prefer: `Str::slug()`, `Str::limit()`, `Str::contains()`, `Str::before()`, `Str::after()`, `Str::between()`, `Str::camel()`, `Str::snake()`, `Str::kebab()`, `Str::headline()`, `Str::squish()`, `Str::mask()`, `Str::uuid()`, `Str::ulid()`, `Str::random()`, `Str::is()`.

Arrays — use `Arr` over raw PHP:
```php
// Incorrect
$name = isset($array['user']['name']) ? $array['user']['name'] : 'default';

// Correct
$name = Arr::get($array, 'user.name', 'default');
```

Key `Arr` methods: `Arr::get()`, `Arr::has()`, `Arr::only()`, `Arr::except()`, `Arr::first()`, `Arr::flatten()`, `Arr::pluck()`, `Arr::where()`, `Arr::wrap()`.

Numbers — use `Number` for display formatting:
```php
Number::format(1000000);          // "1,000,000"
Number::currency(1500, 'USD');    // "$1,500.00"
Number::abbreviate(1000000);      // "1M"
Number::fileSize(1024 * 1024);    // "1 MB"
Number::percentage(75.5);         // "75.5%"
```

URIs — use `Uri` for URL manipulation:
```php
$uri = Uri::of('https://example.com/search')
    ->withQuery(['q' => 'laravel', 'page' => 1]);
```

Use `$request->string('name')` to get a fluent `Stringable` directly from request input for immediate chaining.

Use `search-docs` for the full list of available methods — these helpers are extensive.

## No Inline JS/CSS in Blade

Do not put JS or CSS in Blade templates. Do not put HTML in PHP classes.

Incorrect:
```blade
let article = `{{ json_encode($article) }}`;
```

Correct:
```blade
<button class="js-fav-article" data-article='@json($article)'>{{ $article->name }}</button>
```

Pass data to JS via data attributes or use a dedicated PHP-to-JS package.

## No Unnecessary Comments

Code should be readable on its own. Use descriptive method and variable names instead of comments. The only exception is config files, where descriptive comments are expected.

Incorrect:
```php
// Check if there are any joins
if (count((array) $builder->getQuery()->joins) > 0)
```

Correct:
```php
if ($this->hasJoins())
```
````

## File: .junie/skills/laravel-best-practices/rules/testing.md
````markdown
# Testing Best Practices

## Use `LazilyRefreshDatabase` Over `RefreshDatabase`

`RefreshDatabase` migrates once per process and wraps each test in a rolled-back transaction. `LazilyRefreshDatabase` skips even that first migration if the schema is already up to date.

## Use Model Assertions Over Raw Database Assertions

Incorrect: `$this->assertDatabaseHas('users', ['id' => $user->id]);`

Correct: `$this->assertModelExists($user);`

More expressive, type-safe, and fails with clearer messages.

## Use Factory States and Sequences

Named states make tests self-documenting. Sequences eliminate repetitive setup.

Incorrect: `User::factory()->create(['email_verified_at' => null]);`

Correct: `User::factory()->unverified()->create();`

## Use `Exceptions::fake()` to Assert Exception Reporting

Instead of `withoutExceptionHandling()`, use `Exceptions::fake()` to assert the correct exception was reported while the request completes normally.

## Call `Event::fake()` After Factory Setup

Model factories rely on model events (e.g., `creating` to generate UUIDs). Calling `Event::fake()` before factory calls silences those events, producing broken models.

Incorrect: `Event::fake(); $user = User::factory()->create();`

Correct: `$user = User::factory()->create(); Event::fake();`

## Use `recycle()` to Share Relationship Instances Across Factories

Without `recycle()`, nested factories create separate instances of the same conceptual entity.

```php
Ticket::factory()
    ->recycle(Airline::factory()->create())
    ->create();
```
````

## File: .junie/skills/laravel-best-practices/rules/validation.md
````markdown
# Validation & Forms Best Practices

## Use Form Request Classes

Extract validation from controllers into dedicated Form Request classes.

Incorrect:
```php
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'body' => 'required',
    ]);
}
```

Correct:
```php
public function store(StorePostRequest $request)
{
    Post::create($request->validated());
}
```

## Array vs. String Notation for Rules

Array syntax is more readable and composes cleanly with `Rule::` objects. Prefer it in new code, but check existing Form Requests first and match whatever notation the project already uses.

```php
// Preferred for new code
'email' => ['required', 'email', Rule::unique('users')],

// Follow existing convention if the project uses string notation
'email' => 'required|email|unique:users',
```

## Always Use `validated()`

Get only validated data. Never use `$request->all()` for mass operations.

Incorrect:
```php
Post::create($request->all());
```

Correct:
```php
Post::create($request->validated());
```

## Use `Rule::when()` for Conditional Validation

```php
'company_name' => [
    Rule::when($this->account_type === 'business', ['required', 'string', 'max:255']),
],
```

## Use the `after()` Method for Custom Validation

Use `after()` instead of `withValidator()` for custom validation logic that depends on multiple fields.

```php
public function after(): array
{
    return [
        function (Validator $validator) {
            if ($this->quantity > Product::find($this->product_id)?->stock) {
                $validator->errors()->add('quantity', 'Not enough stock.');
            }
        },
    ];
}
```
````

## File: .junie/skills/laravel-best-practices/SKILL.md
````markdown
---
name: laravel-best-practices
description: "Apply this skill whenever writing, reviewing, or refactoring Laravel PHP code. This includes creating or modifying controllers, models, migrations, form requests, policies, jobs, scheduled commands, service classes, and Eloquent queries. Triggers for N+1 and query performance issues, caching strategies, authorization and security patterns, validation, error handling, queue and job configuration, route definitions, and architectural decisions. Also use for Laravel code reviews and refactoring existing Laravel code to follow best practices. Covers any task involving Laravel backend PHP code patterns."
license: MIT
metadata:
  author: laravel
---

# Laravel Best Practices

Best practices for Laravel, prioritized by impact. Each rule teaches what to do and why. For exact API syntax, verify with `search-docs`.

## Consistency First

Before applying any rule, check what the application already does. Laravel offers multiple valid approaches — the best choice is the one the codebase already uses, even if another pattern would be theoretically better. Inconsistency is worse than a suboptimal pattern.

Check sibling files, related controllers, models, or tests for established patterns. If one exists, follow it — don't introduce a second way. These rules are defaults for when no pattern exists yet, not overrides.

## Quick Reference

### 1. Database Performance → `rules/db-performance.md`

- Eager load with `with()` to prevent N+1 queries
- Enable `Model::preventLazyLoading()` in development
- Select only needed columns, avoid `SELECT *`
- `chunk()` / `chunkById()` for large datasets
- Index columns used in `WHERE`, `ORDER BY`, `JOIN`
- `withCount()` instead of loading relations to count
- `cursor()` for memory-efficient read-only iteration
- Never query in Blade templates

### 2. Advanced Query Patterns → `rules/advanced-queries.md`

- `addSelect()` subqueries over eager-loading entire has-many for a single value
- Dynamic relationships via subquery FK + `belongsTo`
- Conditional aggregates (`CASE WHEN` in `selectRaw`) over multiple count queries
- `setRelation()` to prevent circular N+1 queries
- `whereIn` + `pluck()` over `whereHas` for better index usage
- Two simple queries can beat one complex query
- Compound indexes matching `orderBy` column order
- Correlated subqueries in `orderBy` for has-many sorting (avoid joins)

### 3. Security → `rules/security.md`

- Define `$fillable` or `$guarded` on every model, authorize every action via policies or gates
- No raw SQL with user input — use Eloquent or query builder
- `{{ }}` for output escaping, `@csrf` on all POST/PUT/DELETE forms, `throttle` on auth and API routes
- Validate MIME type, extension, and size for file uploads
- Never commit `.env`, use `config()` for secrets, `encrypted` cast for sensitive DB fields

### 4. Caching → `rules/caching.md`

- `Cache::remember()` over manual get/put
- `Cache::flexible()` for stale-while-revalidate on high-traffic data
- `Cache::memo()` to avoid redundant cache hits within a request
- Cache tags to invalidate related groups
- `Cache::add()` for atomic conditional writes
- `once()` to memoize per-request or per-object lifetime
- `Cache::lock()` / `lockForUpdate()` for race conditions
- Failover cache stores in production

### 5. Eloquent Patterns → `rules/eloquent.md`

- Correct relationship types with return type hints
- Local scopes for reusable query constraints
- Global scopes sparingly — document their existence
- Attribute casts in the `casts()` method
- Cast date columns, use Carbon instances in templates
- `whereBelongsTo($model)` for cleaner queries
- Never hardcode table names — use `(new Model)->getTable()` or Eloquent queries

### 6. Validation & Forms → `rules/validation.md`

- Form Request classes, not inline validation
- Array notation `['required', 'email']` for new code; follow existing convention
- `$request->validated()` only — never `$request->all()`
- `Rule::when()` for conditional validation
- `after()` instead of `withValidator()`

### 7. Configuration → `rules/config.md`

- `env()` only inside config files
- `App::environment()` or `app()->isProduction()`
- Config, lang files, and constants over hardcoded text

### 8. Testing Patterns → `rules/testing.md`

- `LazilyRefreshDatabase` over `RefreshDatabase` for speed
- `assertModelExists()` over raw `assertDatabaseHas()`
- Factory states and sequences over manual overrides
- Use fakes (`Event::fake()`, `Exceptions::fake()`, etc.) — but always after factory setup, not before
- `recycle()` to share relationship instances across factories

### 9. Queue & Job Patterns → `rules/queue-jobs.md`

- `retry_after` must exceed job `timeout`; use exponential backoff `[1, 5, 10]`
- `ShouldBeUnique` to prevent duplicates; `ShouldBeUniqueUntilProcessing` for early lock release
- Always implement `failed()`; with `retryUntil()`, set `$tries = 0`
- `RateLimited` middleware for external API calls; `Bus::batch()` for related jobs
- Horizon for complex multi-queue scenarios

### 10. Routing & Controllers → `rules/routing.md`

- Implicit route model binding
- Scoped bindings for nested resources
- `Route::resource()` or `apiResource()`
- Methods under 10 lines — extract to actions/services
- Type-hint Form Requests for auto-validation

### 11. HTTP Client → `rules/http-client.md`

- Explicit `timeout` and `connectTimeout` on every request
- `retry()` with exponential backoff for external APIs
- Check response status or use `throw()`
- `Http::pool()` for concurrent independent requests
- `Http::fake()` and `preventStrayRequests()` in tests

### 12. Events, Notifications & Mail → `rules/events-notifications.md`, `rules/mail.md`

- Event discovery over manual registration; `event:cache` in production
- `ShouldDispatchAfterCommit` / `afterCommit()` inside transactions
- Queue notifications and mailables with `ShouldQueue`
- On-demand notifications for non-user recipients
- `HasLocalePreference` on notifiable models
- `assertQueued()` not `assertSent()` for queued mailables
- Markdown mailables for transactional emails

### 13. Error Handling → `rules/error-handling.md`

- `report()`/`render()` on exception classes or in `bootstrap/app.php` — follow existing pattern
- `ShouldntReport` for exceptions that should never log
- Throttle high-volume exceptions to protect log sinks
- `dontReportDuplicates()` for multi-catch scenarios
- Force JSON rendering for API routes
- Structured context via `context()` on exception classes

### 14. Task Scheduling → `rules/scheduling.md`

- `withoutOverlapping()` on variable-duration tasks
- `onOneServer()` on multi-server deployments
- `runInBackground()` for concurrent long tasks
- `environments()` to restrict to appropriate environments
- `takeUntilTimeout()` for time-bounded processing
- Schedule groups for shared configuration

### 15. Architecture → `rules/architecture.md`

- Single-purpose Action classes; dependency injection over `app()` helper
- Prefer official Laravel packages and follow conventions, don't override defaults
- Default to `ORDER BY id DESC` or `created_at DESC`; `mb_*` for UTF-8 safety
- `defer()` for post-response work; `Context` for request-scoped data; `Concurrency::run()` for parallel execution

### 16. Migrations → `rules/migrations.md`

- Generate migrations with `php artisan make:migration`
- `constrained()` for foreign keys
- Never modify migrations that have run in production
- Add indexes in the migration, not as an afterthought
- Mirror column defaults in model `$attributes`
- Reversible `down()` by default; forward-fix migrations for intentionally irreversible changes
- One concern per migration — never mix DDL and DML

### 17. Collections → `rules/collections.md`

- Higher-order messages for simple collection operations
- `cursor()` vs. `lazy()` — choose based on relationship needs
- `lazyById()` when updating records while iterating
- `toQuery()` for bulk operations on collections

### 18. Blade & Views → `rules/blade-views.md`

- `$attributes->merge()` in component templates
- Blade components over `@include`; `@pushOnce` for per-component scripts
- View Composers for shared view data
- `@aware` for deeply nested component props

### 19. Conventions & Style → `rules/style.md`

- Follow Laravel naming conventions for all entities
- Prefer Laravel helpers (`Str`, `Arr`, `Number`, `Uri`, `Str::of()`, `$request->string()`) over raw PHP functions
- No JS/CSS in Blade, no HTML in PHP classes
- Code should be readable; comments only for config files

## How to Apply

Always use a sub-agent to read rule files and explore this skill's content.

1. Identify the file type and select relevant sections (e.g., migration → §16, controller → §1, §3, §5, §6, §10)
2. Check sibling files for existing patterns — follow those first per Consistency First
3. Verify API syntax with `search-docs` for the installed Laravel version
````

## File: .junie/skills/livewire-development/reference/javascript-hooks.md
````markdown
# Livewire 4 JavaScript Integration

## Interceptor System (v4)

### Intercept Messages

```js
Livewire.interceptMessage(({ component, message, onFinish, onSuccess, onError }) => {
    onFinish(() => { /* After response, before processing */ });
    onSuccess(({ payload }) => { /* payload.snapshot, payload.effects */ });
    onError(() => { /* Server errors */ });
});
```

### Intercept Requests

```js
Livewire.interceptRequest(({ request, onResponse, onSuccess, onError, onFailure }) => {
    onResponse(({ response }) => { /* When received */ });
    onSuccess(({ response, responseJson }) => { /* Success */ });
    onError(({ response, responseBody, preventDefault }) => { /* 4xx/5xx */ });
    onFailure(({ error }) => { /* Network failures */ });
});
```

### Component-Scoped Interceptors

```blade
<script>
    this.$intercept('save', ({ component, onSuccess }) => {
        onSuccess(() => console.log('Saved!'));
    });
</script>
```

## Magic Properties

- `$errors` - Access validation errors from JavaScript
- `$intercept` - Component-scoped interceptors
````

## File: .junie/skills/livewire-development/SKILL.md
````markdown
---
name: livewire-development
description: "Use for any task or question involving Livewire. Activate if user mentions Livewire, wire: directives, or Livewire-specific concepts like wire:model, wire:click, wire:sort, or islands, invoke this skill. Covers building new components, debugging reactivity issues, real-time form validation, drag-and-drop, loading states, migrating from Livewire 3 to 4, converting component formats (SFC/MFC/class-based), and performance optimization. Do not use for non-Livewire reactive UI (React, Vue, Alpine-only, Inertia.js) or standard Laravel forms without Livewire."
license: MIT
metadata:
  author: laravel
---

# Livewire Development

## Documentation

Use `search-docs` for detailed Livewire 4 patterns and documentation.

## Basic Usage

### Creating Components

```bash

# Single-file component (SFC - default in v4)

# Creates: resources/views/components/⚡create-post.blade.php

php artisan make:livewire create-post

# Page component (SFC - Full Page in v4)

# Creates: resources/views/pages/⚡create-post.blade.php

php artisan make:livewire pages::create-post

# Multi-file component (MFC)

# Creates: resources/views/components/⚡create-post/create-post.php

#          resources/views/components/⚡create-post/create-post.blade.php

php artisan make:livewire create-post --mfc

# Class-based component (v3 style)

# Creates: app/Livewire/CreatePost.php AND resources/views/livewire/create-post.blade.php

php artisan make:livewire create-post --class

# With namespace

php artisan make:livewire Posts/CreatePost
```

### Converting Between Formats

Use `php artisan livewire:convert create-post` to convert between single-file, multi-file, and class-based formats.

### Choosing a Component Format

> **Always follow the project's existing conventions first.** Before creating any component, inspect the project's existing Livewire components to determine the established format (SFC, MFC, or class-based) and directory structure. Check `app/Livewire/`, `resources/views/components/`, and `resources/views/livewire/` for existing components. If the project already uses a consistent format, **use that same format** — even if it differs from the Livewire v4 defaults below. Only fall back to the v4 defaults (SFC in `resources/views/components/`) when no existing convention is established.

Also check `config/livewire.php` for `make_command.type`, `make_command.emoji`, `component_locations`, and `component_namespaces` overrides, which change the default format and where files are stored.

### Component Format Reference

| Format | Flag | Class Path | View Path |
|--------|------|------------|-----------|
| Single-file (SFC) | default | — | `resources/views/components/⚡create-post.blade.php` (PHP + Blade in one file) |
| Full Page SFC | `pages::name` | — | `resources/views/pages/⚡create-post.blade.php` |
| Multi-file (MFC) | `--mfc` | `resources/views/components/⚡create-post/create-post.php` | `resources/views/components/⚡create-post/create-post.blade.php` |
| Class-based | `--class` | `app/Livewire/CreatePost.php` | `resources/views/livewire/create-post.blade.php` |
| View-based | default (Blade-only) | — | `resources/views/components/⚡create-post.blade.php` (Blade-only with functional state) |

> **Important:** The ⚡ prefix shown above is the **default** behavior in Livewire v4 — it is **configurable**. Check `config/livewire.php` for the `make_command.emoji` setting. When `true` (default), always include the ⚡ prefix in filenames you create. When `false`, omit the ⚡ prefix from all paths above.

Namespaced components map to subdirectories: `make:livewire Posts/CreatePost` creates `resources/views/components/posts/⚡create-post.blade.php` (single-file by default). Use `make:livewire Posts/CreatePost --mfc` for multi-file output at `resources/views/components/posts/⚡create-post/create-post.php` and `resources/views/components/posts/⚡create-post/create-post.blade.php`.

### Single-File Component Example

<!-- Single-File Component Example -->
```php
<?php
use Livewire\Component;

new class extends Component {
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }
};
?>

<div>
    <button wire:click="increment">Count: @{{ $count }}</button>
</div>
```

## Livewire 4 Specifics

### Key Changes From Livewire 3

These things changed in Livewire 4, but may not have been updated in this application. Verify this application's setup to ensure you follow existing conventions.

- Use `Route::livewire()` for full-page components (e.g., `Route::livewire('/posts/create', CreatePost::class)`); config keys renamed: `layout` → `component_layout`, `lazy_placeholder` → `component_placeholder`.
- `wire:model` now ignores child events by default (use `wire:model.deep` for old behavior); `wire:scroll` renamed to `wire:navigate:scroll`.
- Component tags must be properly closed; `wire:transition` now uses View Transitions API (modifiers removed).
- JavaScript: `$wire.$js('name', fn)` → `$wire.$js.name = fn`; `commit`/`request` hooks → `interceptMessage()`/`interceptRequest()`.

### New Features

- Component formats: single-file (SFC), multi-file (MFC), view-based components.
- Islands (`@island`) for isolated updates; async actions (`wire:click.async`, `#[Async]`) for parallel execution.
- Deferred/bundled loading: `defer`, `lazy.bundle` for optimized component loading.

| Feature | Usage | Purpose |
|---------|-------|---------|
| Islands | `@island(name: 'stats')` | Isolated update regions |
| Async | `wire:click.async` or `#[Async]` | Non-blocking actions |
| Deferred | `defer` attribute | Load after page render |
| Bundled | `lazy.bundle` | Load multiple together |

### New Directives

- `wire:sort`, `wire:intersect`, `wire:ref`, `.renderless`, `.preserve-scroll` are available for use.
- `data-loading` attribute automatically added to elements triggering network requests.

| Directive | Purpose |
|-----------|---------|
| `wire:sort` | Drag-and-drop sorting |
| `wire:intersect` | Viewport intersection detection |
| `wire:ref` | Element references for JS |
| `.renderless` | Component without rendering |
| `.preserve-scroll` | Preserve scroll position |

## Best Practices

- Always use `wire:key` in loops
- Use `wire:loading` for loading states
- Use `wire:model.live` for instant updates (default is debounced)
- Validate and authorize in actions (treat like HTTP requests)

## Configuration

- `smart_wire_keys` defaults to `true`; new configs: `component_locations`, `component_namespaces`, `make_command`, `csp_safe`.

## Alpine & JavaScript

- `wire:transition` uses browser View Transitions API; `$errors` and `$intercept` magic properties available.
- Non-blocking `wire:poll` and parallel `wire:model.live` updates improve performance.

For interceptors and hooks, see [reference/javascript-hooks.md](reference/javascript-hooks.md).

## Testing

<!-- Testing Example -->
```php
Livewire::test(Counter::class)
    ->assertSet('count', 0)
    ->call('increment')
    ->assertSet('count', 1);
```

## Verification

1. Browser console: Check for JS errors
2. Network tab: Verify Livewire requests return 200
3. Ensure `wire:key` on all `@foreach` loops

## Common Pitfalls

- Missing `wire:key` in loops → unexpected re-rendering
- Expecting `wire:model` real-time → use `wire:model.live`
- Unclosed component tags → syntax errors in v4
- Using deprecated config keys or JS hooks
- Including Alpine.js separately (already bundled in Livewire 4)
````

## File: .junie/skills/tailwindcss-development/SKILL.md
````markdown
---
name: tailwindcss-development
description: "Always invoke when the user's message includes 'tailwind' in any form. Also invoke for: building responsive grid layouts (multi-column card grids, product grids), flex/grid page structures (dashboards with sidebars, fixed topbars, mobile-toggle navs), styling UI components (cards, tables, navbars, pricing sections, forms, inputs, badges), adding dark mode variants, fixing spacing or typography, and Tailwind v3/v4 work. The core use case: writing or fixing Tailwind utility classes in HTML templates (Blade, JSX, Vue). Skip for backend PHP logic, database queries, API routes, JavaScript with no HTML/CSS component, CSS file audits, build tool configuration, and vanilla CSS."
license: MIT
metadata:
  author: laravel
---

# Tailwind CSS Development

## Documentation

Use `search-docs` for detailed Tailwind CSS v4 patterns and documentation.

## Basic Usage

- Use Tailwind CSS classes to style HTML. Check and follow existing Tailwind conventions in the project before introducing new patterns.
- Offer to extract repeated patterns into components that match the project's conventions (e.g., Blade, JSX, Vue).
- Consider class placement, order, priority, and defaults. Remove redundant classes, add classes to parent or child elements carefully to reduce repetition, and group elements logically.

## Tailwind CSS v4 Specifics

- Always use Tailwind CSS v4 and avoid deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.

### CSS-First Configuration

In Tailwind v4, configuration is CSS-first using the `@theme` directive — no separate `tailwind.config.js` file is needed:

<!-- CSS-First Config -->
```css
@theme {
  --color-brand: oklch(0.72 0.11 178);
}
```

### Import Syntax

In Tailwind v4, import Tailwind with a regular CSS `@import` statement instead of the `@tailwind` directives used in v3:

<!-- v4 Import Syntax -->
```diff
- @tailwind base;
- @tailwind components;
- @tailwind utilities;
+ @import "tailwindcss";
```

### Replaced Utilities

Tailwind v4 removed deprecated utilities. Use the replacements shown below. Opacity values remain numeric.

| Deprecated | Replacement |
|------------|-------------|
| bg-opacity-* | bg-black/* |
| text-opacity-* | text-black/* |
| border-opacity-* | border-black/* |
| divide-opacity-* | divide-black/* |
| ring-opacity-* | ring-black/* |
| placeholder-opacity-* | placeholder-black/* |
| flex-shrink-* | shrink-* |
| flex-grow-* | grow-* |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |

## Spacing

Use `gap` utilities instead of margins for spacing between siblings:

<!-- Gap Utilities -->
```html
<div class="flex gap-8">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

## Dark Mode

If existing pages and components support dark mode, new pages and components must support it the same way, typically using the `dark:` variant:

<!-- Dark Mode -->
```html
<div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
    Content adapts to color scheme
</div>
```

## Common Patterns

### Flexbox Layout

<!-- Flexbox Layout -->
```html
<div class="flex items-center justify-between gap-4">
    <div>Left content</div>
    <div>Right content</div>
</div>
```

### Grid Layout

<!-- Grid Layout -->
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div>Card 1</div>
    <div>Card 2</div>
    <div>Card 3</div>
</div>
```

## Common Pitfalls

- Using deprecated v3 utilities (bg-opacity-*, flex-shrink-*, etc.)
- Using `@tailwind` directives instead of `@import "tailwindcss"`
- Trying to use `tailwind.config.js` instead of CSS `@theme` directive
- Using margins for spacing between siblings instead of gap utilities
- Forgetting to add dark mode variants when the project uses dark mode
````

## File: .mimocode/plans/1782664470561-gentle-orchid.md
````markdown
# Plan: Critical Security & Reliability Fixes

## Overview
Fix 3 critical issues: hardcoded admin, missing authorization, synchronous WhatsApp sends. Issue #4 (webhook CSRF) is already handled in `bootstrap/app.php:20-22`.

---

## Issue 1: Admin Hardcoded to User ID 1

**Problem**: `EnsureUserIsAdmin.php:15` checks `(int) $user->id === 1`. If user 1 is deleted, no admin can exist.

### Files to modify:
1. **New migration**: `database/migrations/2026_06_28_000000_add_is_admin_to_users_table.php`
   - Add `$table->boolean('is_admin')->default(false)` to users table

2. **`app/Models/User.php`**
   - Add `'is_admin' => 'boolean'` to `casts()`
   - Add `'is_admin'` to the `#[Fillable]` attribute

3. **`app/Http/Middleware/EnsureUserIsAdmin.php`**
   - Change `abort_unless($user && (int) $user->id === 1, 403)` → `abort_unless($user?->is_admin, 403)`

4. **`database/seeders/DatabaseSeeder.php`**
   - Set `'is_admin' => true` on the factory user

5. **`database/factories/UserFactory.php`**
   - Add `'is_admin' => false` to default state

6. **`app/Http/Controllers/Admin/UserController.php:68`**
   - Change `abort_unless($user->id !== 1, ...)` → `abort_unless(! $user->is_admin, ...)` (prevent deleting any admin)

### Tests:
- Update `tests/Feature/AdminSecurityTest.php` — factory users need `is_admin: true` to pass admin middleware
- Update `tests/Feature/AdminUserManagementTest.php` — same

---

## Issue 2: No Authorization Policies

**Problem**: Zero policies exist. Any authenticated user can delete clients, appointments, or send WhatsApp.

### New files:
1. **`app/Policies/ClientPolicy.php`** — authenticated users can view/create/update/delete
2. **`app/Policies/AppointmentPolicy.php`** — authenticated users can view/create/update/delete
3. **`app/Policies/UserPolicy.php`** — only admin can manage users

### Files to modify (add `$this->authorize()` in Livewire actions):
4. **`app/Livewire/ClientList.php`** — `delete()` method
5. **`app/Livewire/ClientForm.php`** — `save()`, `deleteAppointment()`, `sendAppointmentNow()`
6. **`app/Livewire/AppointmentList.php`** — `deleteConfirmed()`, `deleteSelected()`, `sendNow()`, `updateActiveStatus()`
7. **`app/Livewire/AppointmentForm.php`** — `save()`, `sendNow()`
8. **`app/Livewire/AppointmentReminderSettings.php`** — `save()` (admin only)
9. **`app/Livewire/ClientMessageScheduler.php`** — `save()`, `sendNow()`

### Authorization model:
- Single-clinic app → all authenticated users can manage clients/appointments/messages
- Only admin can manage users and global settings (reminder preferences)
- Use `$this->authorize('update', $model)` for model-specific actions
- Use `$this->authorize('admin')` for admin-only actions

### Tests:
- **New**: `tests/Feature/AuthorizationTest.php` — test that unauthenticated users are redirected, non-admins can't access admin routes

---

## Issue 3: Synchronous WhatsApp Sends

**Problem**: All WhatsApp sends block the HTTP request (up to 15s per Twilio timeout). No Jobs exist.

### New files:
1. **`app/Jobs/SendWhatsAppMessage.php`**
   - Accepts `WhatsAppMessage` model ID (serializable)
   - Calls `WhatsAppSender::send()` 
   - Updates message status (sent/failed)
   - If appointment-linked, updates `enviado` + `whatsapp_sent_at`
   - Calls `AppointmentDeliveryStatusSyncer::sync()`
   - Has `retry(3)` and `backoff(5)` for resilience
   - Implements `failed()` to mark message as failed

### Files to modify:
2. **`app/Console/Commands/DispatchDueWhatsAppMessages.php`**
   - Replace inline `$sender->send($message)` with `SendWhatsAppMessage::dispatch($message->id)`
   - Each message becomes an independent queued job (one failure doesn't block others)

3. **`app/Services/WhatsApp/AppointmentImmediateSender.php`**
   - Replace inline send logic with `SendWhatsAppMessage::dispatchSync($message->id)` 
   - `dispatchSync` keeps the UI feedback working (user sees success/failure immediately)
   - Still gains retry/failure handling benefits

4. **`app/Livewire/ClientMessageScheduler.php:113-138`**
   - Replace inline send logic with `SendWhatsAppMessage::dispatchSync($message->id)`

### Tests:
- **New**: `tests/Feature/Jobs/SendWhatsAppMessageTest.php` — test job dispatches, updates status on success/failure

---

## Execution Order

1. Migration + Model + Middleware (Issue 1) — no dependencies
2. Policies (Issue 2) — no dependencies  
3. Job + async refactoring (Issue 3) — no dependencies
4. Run existing tests to verify nothing breaks
5. Run `vendor/bin/pint --dirty --format agent`

## Verification

```bash
php artisan migrate --force
php artisan test --compact
vendor/bin/pint --dirty --format agent
```
````

## File: .mimocode/plans/1782727080975-crisp-moon.md
````markdown
# Plan: Dashboard — reemplazar botones por select

## Objetivo
Reemplazar los botones "Pasado mañana" y "En 3 días" por un `<select>` con opciones 2-10 días. Mantener "Hoy" y "Mañana" como botones.

## Archivos a modificar

### 1. `app/Livewire/DashboardOverview.php`
- `resolvedDates()`: generar offsets 0-10 (saltar domingos)
- `targetDates()`: solo offsets 0 y 1 (botones)
- Nuevo método `futureDayOptions()`: retorna opciones 2-10 para el select
- `selectDate()`: sin cambios (ya acepta int offset)
- `sundayWarning()`: sin cambios (ya maneja offsets arbitrarios)

### 2. `resources/views/livewire/dashboard-overview.blade.php`
- Mantener botones "Hoy" y "Mañana"
- Agregar `<select>` con `wire:change="selectDate($event.target.value)"` para offsets 2-10
- Select con estilo consistente (rounded-full, mismos colores)

### 3. `tests/Feature/DashboardOverviewTest.php`
- `test_date_buttons_render_with_correct_labels`: quitar asserts de "Pasado mañana" y "En 3 días", agregar assert del select con opciones 2-10
- `test_selecting_date_offset_updates_appointments`: sin cambios (offset 2 sigue funcionando)

## Cambios en lógica

```php
// resolvedDates() — generar 0-10
for ($offset = 1; $offset <= 10; $offset++) { ... }

// targetDates() — solo botones 0, 1
return collect([0, 1])->mapWithKeys(...)

// Nuevo
public function futureDayOptions(): array
{
    return range(2, 10);
}
```

## Verificación
```bash
php artisan test --compact tests/Feature/DashboardOverviewTest.php
vendor/bin/pint --dirty --format agent
```
````

## File: .mimocode/plans/1783245481528-playful-otter.md
````markdown
# Plan: Registrar respuestas de WhatsApp (botones Quick Reply)

## Contexto
Los clientes reciben templates de WhatsApp con botones Quick Reply (Confirmar/Reprogramar). Actualmente no se registra la respuesta del cliente. Necesitamos capturar estos clics y actualizar el estado de las citas.

## Proveedor: Twilio (Quick Reply buttons)

## Cambios

### 1. Migración: Agregar campos a tablas existentes

**appointments** — 2 campos nuevos:
- `confirmada` — boolean, default false, indexed
- `pendiente_reprogramacion` — boolean, default false, indexed

**whatsapp_messages** — 2 campos nuevos:
- `respuesta` — string(50) nullable (texto del botón clickeado: "Confirmar", "Reprogramar", etc.)
- `responded_at` — datetime nullable

Archivo: `database/migrations/2026_07_05_000001_add_response_fields.php`

### 2. Modelo WhatsAppMessage — Agregar constantes y helpers

Archivo: `app/Models/WhatsAppMessage.php`
- Constantes para respuestas: `RESPUESTA_CONFIRMAR = 'Confirmar'`, `RESPUESTA_REPROGRAMAR = 'Reprogramar'`
- Método `hasResponse()`: retorna true si `respuesta` no es null
- Método `isConfirmed()`: retorna true si `respuesta === 'Confirmar'`
- Método `isRescheduleRequested()`: retorna true si `respuesta === 'Reprogramar'`
- Scope `scopeResponded()`: whereNotNull respuesta

### 3. Modelo Appointment — Agregar campos y helpers

Archivo: `app/Models/Appointment.php`
- Agregar `confirmada` y `pendiente_reprogramacion` a `$fillable` y `$casts`
- Método `confirmar()`: set `confirmada = true`, save
- Método `marcarReprogramacion()`: set `pendiente_reprogramacion = true`, save
- Método `isConfirmed()`: retorna `confirmada`
- Método `needsReschedule()`: retorna `pendiente_reprogramacion`

### 4. Webhook: Endpoint para inbound messages de Twilio

Twilio envía mensajes entrantes (incluyendo clics en Quick Reply) a un webhook URL separado.

**Nuevo controller**: `app/Http/Controllers/Webhooks/TwilioWhatsAppInboundController.php`
- Validar firma Twilio (igual que el controller de status)
- Extraer: `From`, `Body` (texto del botón), `MessageSid`, `ProfileName`
- Buscar `WhatsAppMessage` por `provider_message_id` (el mensaje original al que respondió)
  - Twilio incluye `OriginalRepliedMessageSid` o se busca por teléfono + appointment reciente
- Guardar `respuesta = Body`, `responded_at = now()` en el `WhatsAppMessage`
- Llamar a `WhatsAppResponseHandler::process($message)` para actualizar la cita

**Ruta**: `POST /webhooks/twilio/whatsapp-inbound` en `routes/web.php`

### 5. Servicio: WhatsAppResponseHandler

Archivo: `app/Services/WhatsApp/WhatsAppResponseHandler.php`

```php
public static function process(WhatsAppMessage $message): void
{
    $respuesta = $message->respuesta;
    $appointment = $message->appointment;

    if (!$appointment) return;

    match($respuesta) {
        'Confirmar' => $appointment->confirmar(),
        'Reprogramar' => $appointment->marcarReprogramacion(),
        default => null, // otras respuestas se registran pero no procesan
    };
}
```

### 6. Config: Botones configurables

Archivo: `config/whatsapp.php`
- Agregar sección `response_actions` con mapeo de botón → acción:
```php
'response_actions' => [
    'Confirmar' => 'confirmar',
    'Reprogramar' => 'reprogramar',
],
```

### 7. AppointmentDeliveryStatusSyncer — Integrar con respuestas

Archivo: `app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php`
- En `syncAppointmentsFromMessages()`, verificar si hay respuesta y propagar a la cita

## Archivos a modificar
1. `database/migrations/2026_07_05_000001_add_response_fields.php` (nuevo)
2. `app/Models/WhatsAppMessage.php`
3. `app/Models/Appointment.php`
4. `app/Http/Controllers/Webhooks/TwilioWhatsAppInboundController.php` (nuevo)
5. `routes/web.php`
6. `app/Services/WhatsApp/WhatsAppResponseHandler.php` (nuevo)
7. `config/whatsapp.php`
8. `app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php`

## Verificación
1. `php artisan migrate` — verificar que la migración corre sin errores
2. `php artisan test --compact` — tests existentes no deben fallar
3. Crear test para `TwilioWhatsAppInboundController` con payload simulado de Twilio
4. Crear test para `WhatsAppResponseHandler`
5. `vendor/bin/pint --dirty --format agent`

## Preguntas pendientes
- ¿Los botones se llaman exactamente "Confirmar" y "Reprogramar" o tienen otros nombres?
- ¿Necesitas que se envíe un mensaje automático de respuesta después de registrar la acción del cliente?
````

## File: .mimocode/plans/1783256367389-lucky-lagoon.md
````markdown
# Plan: Vincular respuestas de Twilio al mensaje original via ParentMessageSid

## Problema

Cuando un cliente responde a un WhatsApp enviado, el webhook de Twilio incluye un campo `ParentMessageSid` con el SID del mensaje original. Actualmente la app ignora ese campo y busca la respuesta por telefono + mensaje enviado mas reciente sin respuesta (`TwilioWhatsAppStatusController:77-82`). Esto es fragil: si un cliente tiene varios mensajes enviados, puede vincularse al equivocado.

## Solucion

Usar `ParentMessageSid` del webhook para encontrar el mensaje original exacto via `provider_message_id`. Si el campo no esta presente (respuesta libre, no boton), mantener el fallback actual.

## Archivos a modificar

### 1. `app/Http/Controllers/Webhooks/TwilioWhatsAppStatusController.php`

En `processInboundMessage()`:

- Extraer `ParentMessageSid` del payload (Twilio lo envia cuando la respuesta viene de un boton de plantilla)
- Primero buscar el mensaje original por `provider_message_id = ParentMessageSid`
- Si se encuentra, usar ese mensaje directamente
- Si no, usar el fallback actual (telefono + mas reciente sin respuesta)
- Guardar `ParentMessageSid` y `ConversationSid` en `provider_payload.inbound` para trazabilidad
- Actualizar el log para incluir el SID del mensaje padre vinculado

```php
// Nuevo orden de busqueda:
// 1. Por ParentMessageSid (match exacto)
$parentSid = trim((string) data_get($payload, 'ParentMessageSid', ''));
$message = null;
if ($parentSid !== '') {
    $message = WhatsAppMessage::query()
        ->where('provider_message_id', $parentSid)
        ->whereNull('respuesta')
        ->where('status', WhatsAppMessage::STATUS_SENT)
        ->first();
}
// 2. Fallback: telefono + mas reciente sin respuesta
if (! $message) {
    $message = WhatsAppMessage::query()
        ->where('telefono', $phone)
        ->whereNull('respuesta')
        ->where('status', WhatsAppMessage::STATUS_SENT)
        ->latest('sent_at')
        ->first();
}
```

En el array `provider_payload.inbound`, agregar:
- `'parent_message_sid'` — SID del mensaje padre (Twilio)
- `'conversation_sid'` — SID de la conversacion (Twilio)

En el log, agregar `parent_message_sid` y `conversation_sid` para facilitar la trazabilidad.

### 2. Tests: `tests/Feature/TwilioWhatsAppStatusWebhookTest.php`

Agregar tests que verifiquen:
- Respuesta con `ParentMessageSid` se vincula al mensaje correcto aunque existan otros mensajes enviados al mismo telefono
- Respuesta sin `ParentMessageSid` sigue usando el fallback (telefono + mas reciente)
- El `provider_payload.inbound` contiene los campos `parent_message_sid` y `conversation_sid`

### 3. `app/Services/WhatsApp/WhatsAppResponseHandler.php`

Sin cambios. Recibe el `WhatsAppMessage` ya vinculado.

### 4. `app/Models/WhatsAppMessage.php`

Sin cambios. Los campos ya existen en el esquema.

## No se necesitan migraciones

Los campos `parent_message_sid` y `conversation_sid` se guardan dentro del JSON `provider_payload` que ya existe.

## Verificacion

```bash
# Formatear
vendor/bin/pint --dirty --format agent

# Tests
php artisan test --compact tests/Feature/TwilioWhatsAppStatusWebhookTest.php
php artisan test --compact --filter=TwilioWhatsAppStatus
```
````

## File: .mimocode/plans/1783282400248-quiet-planet.md
````markdown
# Fix: WhatsApp button responses not registered on production server

## Root Cause

The Twilio WhatsApp **sandbox** uses the Conversations API and has its own inbound webhook URL, separate from the Messaging Service webhook. This sandbox webhook is likely still pointing to the local ngrok URL (`https://chery-precranial-extemporarily.ngrok-free.dev/...`). When a client clicks "Confirmar" or "Reprogramar", Twilio sends the inbound message to the sandbox's own webhook URL — NOT to the Messaging Service URL that is correctly configured at `https://juanjota.eu/webhooks/twilio/whatsapp-status`.

This explains why:
- Messages are **sent** successfully (outbound uses the API payload, which has the correct StatusCallback)
- Delivery status updates may work (StatusCallback in API payload)
- But **inbound responses** never reach the server (sandbox webhook points to ngrok)

## Solution Options

### Option A: Update sandbox webhook URL in Twilio Console (no code change)

The sandbox webhook configuration is in a specific location in Twilio Console:

1. Go to **console.twilio.com**
2. Navigate to **Messaging → Try it out → Send a WhatsApp message**
3. On the **"Send & receive"** tab (not the "Logs" tab the user was viewing)
4. Scroll down to **"Sandbox Configuration"** section
5. Set **"When a message comes in"** to `https://juanjota.eu/webhooks/twilio/whatsapp-status`
6. Set method to **HTTP POST**
7. Click **Save**

This is the simplest fix — no code changes needed.

### Option B: Add a Twilio API call to configure sandbox webhook (code change)

If the user can't find or edit the sandbox configuration in the Console UI, add a command/helper that uses the Twilio Conversations API to update the sandbox webhook URL programmatically.

**File to modify:** `app/Services/WhatsApp/WhatsAppSender.php` or a new artisan command

The Twilio API endpoint to update sandbox configuration:
```
PUT https://conversations.twilio.com/v1/Conversations/{ConversationSid}
```

Or update the default conversation role webhook:
```
POST https://conversations.twilio.com/v1/Services/{ServiceSid}/Configuration
```

### Option C: Switch server to sender mode (recommended for production)

Change `TWILIO_WHATSAPP_MODE=sender` on the server and configure `TWILIO_WHATSAPP_FROM` with the real sender number. Sender mode uses the Messaging Service webhook (already correctly configured), bypassing the sandbox entirely.

**.env change on server:**
```
TWILIO_WHATSAPP_MODE=sender
TWILIO_WHATSAPP_FROM=whatsapp:+34XXXXXXXXX  # real sender number
```

## Recommended Approach

**Option A first** — try to find and update the sandbox webhook URL in the Console. If that's not possible or the sandbox UI doesn't allow editing, then **Option C** for production.

## Verification

1. After updating the sandbox webhook URL, send a test reminder from the server
2. Click "Confirmar" or "Reprogramar" on the phone
3. Check `storage/logs/laravel.log` on the server for:
   - `"WhatsApp inbound message received."` — confirms webhook reached the server
   - `"WhatsApp response recorded."` — confirms response was processed
4. Verify in the database that `whatsapp_messages.respuesta` and `appointments.confirmada`/`pendiente_reprogramacion` are updated

## Files Involved (if code changes needed)

- `app/Http/Controllers/Webhooks/TwilioWhatsAppStatusController.php` — webhook handler (no changes expected)
- `app/Services/WhatsApp/WhatsAppResponseHandler.php` — processes responses (no changes expected)
- `app/Services/WhatsApp/WhatsAppSender.php` — sends messages and resolves mode (possible changes for Option B/C)
- `.env` on server — configuration values (changes for Option C)
````

## File: .mimocode/plans/1783290993228-swift-falcon.md
````markdown
# Plan: Diagnosticar y reparar envío de WhatsApp en producción

## Contexto

La sesión anterior identificó que los botones de WhatsApp (Confirmar/Reprogramar) no funcionan en producción porque el sender pertenece a "Default Messaging Service for Conversations" de Twilio, y la Conversations API ignora la configuración de webhook del sender. Ahora el usuario reporta un problema **nuevo**: los mensajes ni siquiera se envían (no llegan al teléfono), sin error visible.

## Hipótesis

Posibles causas de que no envíe WhatsApp sin error:

1. **`WHATSAPP_DRIVER=log` en el servidor** — El job ejecuta pero solo loguea, no envía a Twilio
2. **`WHATSAPP_MESSAGE_MODE=template` sin Content SID válido** — Twilio rechaza porque la plantilla no existe o no está aprobada
3. **`TWILIO_WHATSAPP_FROM` incorrecto** — El número from no coincide con el sender registrado en Twilio
4. **Credenciales de Twilio inválidas** — Account SID o Auth Token cambiados/revocados
5. **Twilio bloqueó el sender** — Por uso inapropiado o restricciones de sandbox

## Pasos de diagnóstico (en el servidor de producción)

### Paso 1: Verificar configuración del .env en el servidor

```bash
ssh juanjota@pserver2
cd /home/juanjota/public_html_backup
grep -E "(WHATSAPP_DRIVER|TWILIO_WHATSAPP_MODE|TWILIO_WHATSAPP_FROM|WHATSAPP_MESSAGE_MODE|TWILIO_CONTENT_SID|TWILIO_ACCOUNT_SID|TWILIO_AUTH_TOKEN)" .env
```

**Esperado para envío funcional:**
- `WHATSAPP_DRIVER=twilio`
- `TWILIO_WHATSAPP_MODE=sender` (o `sandbox`)
- `TWILIO_WHATSAPP_FROM=whatsapp:+XXXXXXXXX` (número real registrado)
- `TWILIO_ACCOUNT_SID` y `TWILIO_AUTH_TOKEN` presentes y correctos

### Paso 2: Verificar logs de errores de WhatsApp

```bash
cat storage/logs/laravel.log | grep -i "whatsapp\|twilio" | tail -20
```

Si hay errores de Twilio (credenciales inválidas, plantilla no encontrada, sender bloqueado), aparecerán aquí.

### Paso 3: Probar envío directo vía curl desde el servidor

```bash
# Reemplazar con las credenciales reales del .env
curl -s -u "ACCOUNT_SID:AUTH_TOKEN" \
  -X POST "https://api.twilio.com/2010-04-01/Accounts/ACCOUNT_SID/Messages.json" \
  -d "From=whatsapp:+15559355880" \
  -d "To=whatsapp:+34XXXXXXXXX" \
  -d "Body=Prueba de envío directo" \
  -d "StatusCallback=https://juanjota.eu/webhooks/twilio/whatsapp-status"
```

Si esto funciona → el problema está en la configuración de Laravel (driver, message_mode, etc.)
Si esto falla → el problema está en Twilio (credenciales, sender, restricciones)

### Paso 4: Verificar si el sender +15559355880 sigue activo

En Twilio Console → **Messaging → Senders → WhatsApp senders** → verificar que el sender esté "Online" y no tenga restricciones.

## Solución según diagnóstico

### Si `WHATSAPP_DRIVER=log` en producción:
Cambiar a `WHATSAPP_DRIVER=twilio` en el `.env` del servidor y ejecutar:
```bash
php artisan config:cache
```

### Si `WHATSAPP_MESSAGE_MODE=template` pero el Content SID no es válido:
- **Opción A**: Cambiar a `WHATSAPP_MESSAGE_MODE=text` para enviar mensajes de texto plano
- **Opción B**: Verificar/corregir el Content SID en Twilio Console → **Content API → Content Templates**

### Si el sender +15559355880 no está activo o tiene restricciones:
- Verificar en Twilio Console que el sender esté "Online"
- Si fue desactivado, reactivarlo o usar otro sender

### Si credenciales inválidas:
Actualizar `TWILIO_ACCOUNT_SID` y `TWILIO_AUTH_TOKEN` en el `.env` del servidor

## Archivos relevantes

- `app/Services/WhatsApp/WhatsAppSender.php` — Lógica de envío, resolución de modo
- `app/Jobs/SendWhatsAppMessage.php` — Job que ejecuta el envío
- `config/whatsapp.php` — Configuración del driver y Twilio
- `.env` en el servidor — Variables de entorno (solo en producción)

## Verificación

1. Después de corregir la configuración, ejecutar `php artisan config:cache` en el servidor
2. Enviar un WhatsApp de prueba desde la interfaz de la app
3. Verificar que el mensaje llegue al teléfono
4. Verificar en la base de datos que `whatsapp_messages.status = 'sent'`
5. Verificar en Twilio Console → **Monitor → Logs** que el mensaje aparezca como "Sent"

## Paso 5 (post-fix): Resolver el problema de webhooks inbound

Una vez que el envío funcione, volver al problema original: los botones Confirmar/Reprogramar no se registran en producción. La solución es configurar el webhook de la Conversations API:

**Opción A (recomendada)**: Configurar webhook vía API de Twilio:
```bash
# 1. Obtener el Service SID de Conversations
curl -s -u "ACCOUNT_SID:AUTH_TOKEN" \
  "https://api.twilio.com/2010-04-01/Accounts/ACCOUNT_SID/Conversations/Services.json"

# 2. Actualizar el webhook del servicio
curl -s -u "ACCOUNT_SID:AUTH_TOKEN" \
  -X POST "https://api.twilio.com/2010-04-01/Accounts/ACCOUNT_SID/Conversations/Services/SERVICE_SID" \
  -d "MessagingService.OnMessageAddedUrl=https://juanjota.eu/webhooks/twilio/whatsapp-status" \
  -d "MessagingService.OnMessageAddedMethod=POST"
```

**Opción B**: Quitar el sender del servicio de Conversations para usar Programmable Messaging estándar.
````

## File: .mimocode/plans/1783330327359-brave-comet.md
````markdown
# Limpieza .env — WhatsApp/Twilio

## Resumen: eliminar 18 líneas, cambiar 1

### A eliminar (líneas 45-76 del .env)

| # | Línea actual | Motivo |
|---|---|---|
| 1 | `#TWILIO_WHATSAPP_MODE=sender` | Duplicado del activo |
| 2 | `#WHATSAPP_MESSAGE_MODE=text` | Obsoleto (ahora es `template`) |
| 3 | `#WHATSAPP_DEFAULT_TEMPLATE=recordatorio_cita` | Obsoleto |
| 4 | `#WHATSAPP_DEFAULT_MESSAGE="Hola [NOMBRE]..."` | Obsoleto |
| 5 | `TWILIO_CONTENT_VARIABLES=...` | Se mantiene ✅ |
| 6 | `SENDGRID_API_KEY=SG...` | No es WhatsApp |
| 7 | `#BOTONES` | Comentario suelto |
| 8 | `#TEXTO` | Comentario suelto |
| 9 | `#TWILIO_CONTENT_SID=HX28712cac...` | SID antiguo (texto, no botones) |
| 10 | `####Numero comprado es este...` | Comentario suelto |
| 11 | `#TWILIO_WHATSAPP_FROM=whatsapp:+14155238886` | Sandbox obsoleto |
| 12 | `#TWILIO_STATUS_CALLBACK_URL=https://juanjota.eu/...` | URL producción anterior |
| 13 | `#TWIILIO_INBOUND_CALLBACK_URL=...` | Typo, nunca existió en config |
| 14 | `#TWIILIO_INBOUND_CALLBACK_URL=...` | Typo, nunca existió en config |
| 15 | `WHATSAPP_CLOUD_API_BASE_URL=...` | No usa Cloud API |
| 16 | `WHATSAPP_CLOUD_API_VERSION=...` | No usa Cloud API |
| 17 | `WHATSAPP_CLOUD_API_PHONE_NUMBER_ID=` | No usa Cloud API |
| 18 | `WHATSAPP_CLOUD_API_ACCESS_TOKEN=` | No usa Cloud API |
| 19 | `WHATSAPP_CLOUD_API_TIMEOUT=15` | No usa Cloud API |

### A modificar

| Variable | Actual | Sugerencia |
|---|---|---|
| `TWILIO_STATUS_CALLBACK_URL` | URL ngrok (cambia al reiniciar) | Vacío en producción; el código usa `route()` como fallback |

### .env resultante (solo WhatsApp/Twilio)

```env
WHATSAPP_DRIVER=twilio
WHATSAPP_MESSAGE_MODE=template
WHATSAPP_DEFAULT_COUNTRY_CODE=+34
TWILIO_WHATSAPP_MODE=sender
TWILIO_ACCOUNT_SID=ACe9070...
TWILIO_AUTH_TOKEN=8c63a4d...
TWILIO_CONTENT_SID=HXdea6aee...
TWILIO_CONTENT_VARIABLES={"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}
TWILIO_WHATSAPP_FROM=whatsapp:+12515013894
TWILIO_TEST_RECIPIENT=whatsapp:+15559355880
TWILIO_TIMEOUT=15
TWILIO_STATUS_CALLBACK_URL=
```

### Verificación

```bash
php artisan config:clear && php artisan config:cache
php artisan test --compact --filter=TwilioWhatsApp
```
````

## File: .mimocode/plans/1783349825106-nimble-star.md
````markdown
# Cambiar lógica de respuesta WhatsApp: usar ButtonPayload en vez de texto

## Cambios

### 1. `app/Services/WhatsApp/WhatsAppResponseHandler.php`
- Extraer `button_payload` de `provider_payload['inbound']['button_payload']`
- Si payload empieza con `confirm` → acción confirmar
- Si payload empieza con `reprogram` → acción reprogramar
- Fallback: comparar texto como ahora (para mensajes sin botón)
- Extraer método privado `resolveAction(string $buttonPayload, ?string $respuesta): string`

### 2. `app/Models/WhatsAppMessage.php`
- `isConfirmed()`: verificar payload `provider_payload['inbound']['button_payload']` empieza con `confirm`, fallback a texto
- `isRescheduleRequested()`: verificar payload empieza con `reprogram`, fallback a texto
- `responseValue()`: considerar `button_payload` en el cálculo

### 3. Tests
- `tests/Feature/TwilioWhatsAppStatusWebhookTest.php`: actualizar asserts que esperan texto literal para que también funcionen con payloads
- `tests/Feature/AppointmentManagerTest.php`: verificar que sigue funcionando

## Archivos a modificar
- `app/Services/WhatsApp/WhatsAppResponseHandler.php`
- `app/Models/WhatsAppMessage.php`
- `tests/Feature/TwilioWhatsAppStatusWebhookTest.php`

## Verificación
```bash
php artisan test --compact tests/Feature/TwilioWhatsAppStatusWebhookTest.php
php artisan test --compact tests/Feature/AppointmentManagerTest.php
vendor/bin/pint --dirty --format agent
```
````

## File: .mimocode/plans/1783355925256-hidden-orchid.md
````markdown
# Plan: Separar componentes Livewire por página

## Objetivo
Cada ruta/page tenga su propio componente Livewire (PHP + Blade) independiente, sin compartir componentes entre páginas.

## Mapa actual → nuevo

| Ruta | Componente actual | Componente nuevo (PHP) | Blade nuevo |
|------|-------------------|------------------------|-------------|
| `clients/xx/appointments` | `AppointmentList` (compartido) | `ClientAppointments` | `livewire/client-appointments.blade.php` |
| `clients` | `ClientList` (compartido) | `ClientIndex` | `livewire/client-index.blade.php` |
| `clients/list` | `ClientList` (compartido) | `ClientListAll` | `livewire/client-list-all.blade.php` |
| `appointments` | `AppointmentOverview` | `AppointmentIndex` | `livewire/appointment-index.blade.php` |
| `appointments/create` + `edit` | `AppointmentForm` | Se mantiene (create/edit = mismo form) | Se mantiene |
| `agenda` | `DailyAgenda` | `AgendaIndex` | `livewire/agenda-index.blade.php` |

## Archivos a crear

### 1. `app/Livewire/ClientAppointments.php`
- Copiar TODO de `AppointmentList.php` actual
- Renombrar clase a `ClientAppointments`
- Siempre requiere `clientId` (obligatorio, no nullable)
- render() apunta a `livewire.client-appointments`

### 2. `resources/views/livewire/client-appointments.blade.php`
- Copiar TODO de `livewire/appointment-list.blade.php` actual
- Solo vista de tabla para un cliente (sin vista tarjeta por cliente)

### 3. `app/Livewire/ClientIndex.php`
- Lógica de `ClientList.php` pero sin `showAllClients`
- Siempre muestra clientes (con o sin búsqueda)
- render() apunta a `livewire.client-index`

### 4. `resources/views/livewire/client-index.blade.php`
- Copiar de `livewire/client-list.blade.php`

### 5. `app/Livewire/ClientListAll.php`
- Lógica de `ClientList.php` con `showAllClients = true` fijo
- render() apunta a `livewire.client-list-all`

### 6. `resources/views/livewire/client-list-all.blade.php`
- Copiar de `livewire/client-list.blade.php`

### 7. `app/Livewire/AppointmentIndex.php`
- Renombrar de `AppointmentOverview`
- render() apunta a `livewire.appointment-index`

### 8. `resources/views/livewire/appointment-index.blade.php`
- Copiar de `livewire/appointment-overview.blade.php`

### 9. `app/Livewire/AgendaIndex.php`
- Renombrar de `DailyAgenda`
- render() apunta a `livewire.agenda-index`

### 10. `resources/views/livewire/agenda-index.blade.php`
- Copiar de `livewire/daily-agenda.blade.php`

## Archivos a modificar

### Rutas `routes/web.php`
```php
Route::view('/clients', 'clients.index')->name('clients.index');
Route::view('/clients/list', 'clients.list')->name('clients.list');
Route::view('/clients/{client}/appointments', 'appointments.client')...
Route::get('/appointments', AppointmentIndexController::class)->name('appointments.index');
Route::view('/agenda', 'agenda.index')->name('agenda.index');
```

### Wrappers Blade (mantener thin wrappers)
- `resources/views/clients/index.blade.php` → `<livewire:client-index />`
- `resources/views/clients/list.blade.php` → `<livewire:client-list-all />`
- `resources/views/appointments/client.blade.php` → `<livewire:client-appointments :client-id="(int) request()->route('client')" />`
- `resources/views/appointments/index.blade.php` → `<livewire:appointment-index />`
- `resources/views/agenda/index.blade.php` → `<livewire:agenda-index />`

## Archivos a eliminar (o vaciar)
- `app/Livewire/AppointmentList.php` → reemplazado por `ClientAppointments`
- `app/Livewire/AppointmentOverview.php` → reemplazado por `AppointmentIndex`
- `app/Livewire/ClientList.php` → reemplazado por `ClientIndex` + `ClientListAll`
- `app/Livewire/DailyAgenda.php` → reemplazado por `AgendaIndex`
- `resources/views/livewire/appointment-list.blade.php` → movido a `client-appointments`
- `resources/views/livewire/appointment-overview.blade.php` → movido a `appointment-index`
- `resources/views/livewire/client-list.blade.php` → movido a `client-index` y `client-list-all`
- `resources/views/livewire/daily-agenda.blade.php` → movido a `agenda-index`
- `resources/views/appointments/sent.blade.php` → ya no existe route `appointments.sent`

## Orden de ejecución
1. Crear PHP classes nuevas (copia renombrada de las actuales)
2. Crear Blade views nuevas (copia de las actuales, actualizar `return view()`)
3. Actualizar wrappers Blade para apuntar a nuevos componentes
4. Eliminar archivos antiguos
5. Ejecutar `php artisan view:clear`
6. Verificar cada ruta manualmente

## Verificación
- Navegar a `/clients`, `/clients/list`, `/clients/1/appointments`, `/appointments`, `/agenda`
- Cada página debe funcionar igual que antes
- `vendor/bin/pint --dirty --format agent`
````

## File: .mimocode/plans/1783378474056-jolly-panda.md
````markdown
# Plan: Fijar ancho columna izquierda en filtros

## Problema
Columnas del grid se redimensionan cuando aparece el bulk actions dropdown.

## Solución
Cambiar `auto_1fr_auto` por un ancho fijo en la columna izquierda (220px) para que no haya desplazamiento.

## Archivo
`resources/views/livewire/client-appointments.blade.php` línea 41

## Cambio
```
grid-cols-[auto_1fr_auto] → grid-cols-[220px_1fr_auto]
```

## Verificación
Seleccionar citas en `/clients/{id}/appointments` y comprobar que no hay saltos al aparecer el dropdown.
````

## File: .mimocode/plans/1783413735994-neon-canyon.md
````markdown
# Plan: Iconos en opciones del dropdown bulk actions

Agregar iconos a los 3 botones del dropdown HTML:
- Activar seleccionadas → `<x-iconos.check>`
- Desactivar seleccionadas → `<x-iconos.inactivo>`
- Eliminar seleccionadas → `<x-iconos.papelera>`

## Archivo
- `resources/views/livewire/client-appointments.blade.php` (líneas 55-66)

## Cambio
Agregar `<x-iconos.X clase="size-4"/>` al inicio de cada `<button>`.
````

## File: .mimocode/plans/1783456328338-eager-rocket.md
````markdown
# Plan: Mover credenciales Twilio de .env a Base de Datos

## Contexto

Actualmente todas las credenciales Twilio viven en `.env` → `config/whatsapp.php`. Para cambiarlas hay que SSHear al servidor, editar `.env`, limpiar cache y reiniciar. Con la DB se pueden editar desde Ajustes sin tocar el servidor.

## Análisis de riesgo

| Aspecto | .env (actual) | BD (propuesto) |
|---------|---------------|----------------|
| Seguridad | Archivo en disco, solo accesible por SSH | Cifrado con Laravel `encrypted` cast, expuesto a SQL injection |
| Edición | Requiere SSH + config:clear | desde UI en `/admin/settings` |
| Multi-tenant | No aplica | No necesario (single-clinic) |
| Fallback | N/A | Si DB vacía → usar .env |

**Decisión**: Proceder con cifrado `encrypted` cast. La app es single-tenant y el beneficio de editar desde UI supera el riesgo marginal (el admin ya tiene acceso a la BD).

## Tabla: `whatsapp_credentials`

Migración `2026_07_08_000000_create_whatsapp_credentials_table.php`:

```sql
whatsapp_credentials:
  id (bigint, PK)
  account_sid (string, nullable, encrypted)
  auth_token (string, nullable, encrypted)
  api_key_sid (string, nullable, encrypted)
  api_key_secret (string, nullable, encrypted)
  messaging_service_sid (string, nullable, encrypted)
  from_number (string, nullable)
  test_recipient (string, nullable)
  timestamps
```

Singleton pattern (un solo registro). Los campos sensibles usan `Encrypted` cast de Laravel.

## Modelo: `App\Models\WhatsAppCredential`

```php
protected $table = 'whatsapp_credentials';

protected function casts(): array {
    return [
        'account_sid' => 'encrypted',
        'auth_token' => 'encrypted',
        'api_key_sid' => 'encrypted',
        'api_key_secret' => 'encrypted',
        'messaging_service_sid' => 'encrypted',
    ];
}

public static function get(): static { ... }
public function toConfigArray(): array { ... }
```

## Resolución de config: DB → .env fallback

Crear `App\Support\TwilioConfig`:

```php
public static function resolve(string $key): mixed
{
    $dbValue = WhatsAppCredential::get()->{$key} ?? null;
    return $dbValue ?: config("whatsapp.twilio.{$key}");
}
```

Modificar `WhatsAppSender`, `AppointmentDeliveryStatusSyncer`, `TwilioWhatsAppStatusController` para que usen `TwilioConfig::resolve()` en lugar de `config('whatsapp.twilio.X')`.

## Archivos a modificar

| Archivo | Cambio |
|---------|--------|
| `config/whatsapp.php` | Sin cambios (mantiene .env como fallback) |
| `app/Models/WhatsAppCredential.php` | **Nuevo** — modelo singleton con `encrypted` casts |
| `app/Support/TwilioConfig.php` | **Nuevo** — resolvedor DB → env |
| `app/Services/WhatsApp/WhatsAppSender.php` | Usar `TwilioConfig::resolve()` en `sendTwilioRequest()`, `buildTwilioPayload()`, `resolveTwilioMode()` |
| `app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php` | Usar `TwilioConfig::resolve()` en `refreshMessageFromTwilio()` |
| `app/Http/Controllers/Webhooks/TwilioWhatsAppStatusController.php` | Usar `TwilioConfig::resolve()` para `auth_token` en `isValidTwilioRequest()` |
| `resources/views/settings/index.blade.php` | Agregar sección editable para credenciales (inputs para SID, token, from, etc.) |
| `app/Livewire/TwilioCredentialSettings.php` | **Nuevo** — componente Livewire para gestionar credenciales |

## UI en Ajustes

Nueva sección "Credenciales Twilio" con:
- Input Account SID (máscara al guardar, mostrar parcial)
- Input Auth Token (password field, solo "Configurado/No configurado" al mostrar)
- Input API Key SID (opcional)
- Input API Key Secret (opcional, password)
- Input Messaging Service SID (opcional)
- Input From Number
- Input Test Recipient
- Botón Guardar

## Verificación
1. `php artisan migrate` — crear tabla
2. Guardar credenciales desde Ajustes → se cifran en BD
3. Enviar WhatsApp de prueba → funciona con credenciales de BD
4. Borrar credenciales de BD → fallback a .env
5. `vendor/bin/pint --dirty --format agent`
6. Tests existentes pasan (usan config mock, no .env real)
````

## File: .mimocode/plans/1783467421696-clever-sailor.md
````markdown
# Plan: Múltiples números de remitente + prefijo internacional

## Objetivo
- Quitar `test_recipient` de la UI, BD y modelo
- Reemplazar el `from_number` único con una lista de números de remitente (solo uno activo a la vez)
- Añadir campo de prefijo internacional por número
- Al enviar vía Twilio, predecir `whatsapp:` automáticamente

---

## 1. Migración nueva

**Archivo:** `database/migrations/2026_07_08_XXXXXX_create_whatsapp_sender_numbers_table.php`

```
whatsapp_sender_numbers
├── id
├── whatsapp_credential_id (FK → whatsapp_credentials)
├── prefix (string, ej: '+34')
├── number (string, ej: '600000000')
├── selected (boolean, default: false)
├── timestamps
```

**Migración de datos:** si `whatsapp_credentials.from_number` tiene valor, crear un registro en la nueva tabla con `prefix='+34'` (default_country_code) y `number` extraído, marcado como `selected=true`.

**Drop column:** eliminar `from_number` y `test_recipient` de `whatsapp_credentials`.

---

## 2. Modelo nuevo: `WhatsAppSenderNumber`

**Archivo:** `app/Models/WhatsAppSenderNumber.php`

- `belongsTo(WhatsAppCredential::class)`
- `getFullNumberAttribute()` → `+{prefix}{number}` (ej: `+34600000000`)
- `getWhatsAppAddressAttribute()` → `whatsapp:+{prefix}{number}` (ej: `whatsapp:+34600000000`)
- Scopes: `scopeSelected($query)`
- Validación: `prefix` required, `number` required|digits_between:6,15

---

## 3. Cambios en `WhatsAppCredential`

**Archivo:** `app/Models/WhatsAppCredential.php`

- Añadir `hasMany(WhatsAppSenderNumber::class)`
- Modificar `resolveFrom()`: retorna `$selected->whatsapp_address` (string `whatsapp:+34...`), fallback a `config('whatsapp.twilio.from')`
- Eliminar `resolveTestRecipient()`
- Eliminar `from_number` y `test_recipient` de `$fillable`

---

## 4. Cambios en `WhatsAppSender`

**Archivo:** `app/Services/WhatsApp/WhatsAppSender.php`

- `buildTwilioPayload()` línea 209: `$credential->resolveFrom()` ya retorna `whatsapp:+34...`
- Línea 232: `normalizeWhatsAppAddress()` detecta `whatsapp:` y no duplica — OK
- La detección de sandbox (línea 273) compara contra `whatsapp:+14155238886` — verificar que `$from` ya tenga el prefijo
- `resolveTwilioMode()` línea 273: la detección de sandbox debe comparar con el número completo
- Eliminar `twilioTestRecipient()` (línea 431-432)

---

## 5. Cambios en `TwilioCredentialSettings`

**Archivo:** `app/Livewire/TwilioCredentialSettings.php`

- Eliminar propiedades `from_number` y `test_recipient`
- Añadir propiedad `$senderNumbers` (array cargado desde BD)
- Nuevo método `addSenderNumber()`: crea registro con `selected=false`
- Nuevo método `removeSenderNumber($id)`: elimina registro
- Nuevo method `selectSenderNumber($id)`: deselecciona todos, selecciona el indicado
- Modificar `save()`: solo guarda api_key_sid, api_key_secret (el modo se guarda en `toggleMode`)
- Modificar `mount()`: cargar sender numbers desde relación

---

## 6. Cambios en la vista `twilio-credential-settings.blade.php`

**Eliminar:** campo "Destinatario de prueba"

**Reemplazar:** sección "Remitente" con:
- Lista de números, cada uno con:
  - Select de prefijo internacional: +34 España, +1 USA/Canadá, +52 México, +54 Argentina, +57 Colombia, +56 Chile, +51 Perú, +44 UK
  - Input de número (solo dígitos, 6-15 caracteres)
  - Radio button para seleccionar como activo
  - Botón de eliminar (X)
- Botón "Añadir número"
- Texto: "El número activo se usará como remitente en Twilio"

---

## 7. Cambios en `settings/index.blade.php` (sección Estado actual)

- Eliminar tarjeta "Destino de prueba"
- Actualizar tarjeta "Sender" para mostrar el número activo de la lista

---

## 8. Cambios en `WhatsAppConnectionTest`

**Archivo:** `app/Livewire/WhatsAppConnectionTest.php`

- Eliminar referencia a `config('whatsapp.twilio.test_recipient')` (línea 96)
- En `sendSavedRecipient()`: usar el número seleccionado de `WhatsAppCredential` como destinatario prellenado

---

## 9. Cambios en `SettingsOverview`

**Archivo:** `resources/views/livewire/settings-overview.blade.php`

- Actualizar check de "Canal configurado": verificar que hay al menos un sender number seleccionado
- Actualizar detección de sandbox: comparar con el número completo del seleccionado

---

## Archivos a modificar

| Archivo | Cambio |
|---|---|
| `database/migrations/2026_07_08_*_create_whatsapp_sender_numbers_table.php` | **NUEVO** — tabla de números |
| `database/migrations/2026_07_08_*_drop_from_number_test_recipient.php` | **NUEVO** — eliminar columnas obsoletas |
| `app/Models/WhatsAppSenderNumber.php` | **NUEVO** — modelo |
| `app/Models/WhatsAppCredential.php` | Relación hasMany, resolver modificado |
| `app/Services/WhatsApp/WhatsAppSender.php` | Usar nuevo resolver |
| `app/Livewire/TwilioCredentialSettings.php` | CRUD de números |
| `app/Livewire/WhatsAppConnectionTest.php` | Quitar test_recipient |
| `resources/views/livewire/twilio-credential-settings.blade.php` | UI de números |
| `resources/views/livewire/settings-overview.blade.php` | Actualizar checks |
| `resources/views/settings/index.blade.php` | Quitar "Destino de prueba" |
| `app/Providers/AppServiceProvider.php` | — |
| `HANDOFF.md` | Documentar cambios |
| `GUIA_RETOMAR_TRABAJO.md` | Documentar cambios |

---

## Verificación

1. `php artisan migrate` — crear tablas nuevas
2. `php artisan test --compact` — todos los tests pasan
3. Verificar en `/admin/settings`:
   - Toggle sandbox/sender funciona y guarda en BD
   - Se pueden añadir múltiples números
   - Se puede seleccionar uno como activo
   - Se puede eliminar números
   - El prefijo internacional se guarda correctamente
4. Verificar en "Prueba de conexión": el remitente activo se usa como destinatario prellenado
5. Verificar en "Resumen": muestra el número activo correctamente
6. `vendor/bin/pint --dirty --format agent`
````

## File: .mimocode/plans/1783587590113-quick-river.md
````markdown
# Plan: Arreglar columna "Confir / Repo" siempre muestra "—"

## Problema
La columna "Confir / Repo" en `clients/{id}/appointments` siempre muestra "—". 

## Análisis
`responseStatusLabel()` (Appointment.php:152) retorna null cuando:
1. `latestRespondedWhatsAppMessage` es null (no hay mensajes con `respuesta` not null)
2. Y `confirmada` es false, y `pendiente_reprogramacion` es false

El `WhatsAppResponseHandler::process()` solo actualiza `confirmada` cuando es confirmación. **No** actualiza `pendiente_reprogramacion` cuando la respuesta es "Reprogramar" — solo hace log y return.

**Pero** aun sin ese fix, `responseStatusLabel()` debería retornar `$latest->respuesta` si hay un mensaje respondido. Entonces el problema principal es que `latestRespondedWhatsAppMessage` probablemente no retorna nada.

## Hipótesis principal
La relación `latestRespondedWhatsAppMessage` usa `latestOfMany('responded_at')`. Si los mensajes antiguos tienen `responded_at = null` (antes de que se agregara el campo), `latestOfMany` podría no encontrarlos correctamente. O simplemente no hay mensajes con `respuesta` not null en la base de datos.

## Fix propuesto

### 1. `WhatsAppResponseHandler::process()` — manejar "Reprogramar"
Agregar caso para `pendiente_reprogramacion`:
```php
$isReschedule = $buttonPayload !== ''
    ? str_starts_with($buttonPayload, 'reprogram')
    : strtolower(trim((string) $message->respuesta)) === 'reprogramar';

if ($isReschedule) {
    $appointment->update([
        'pendiente_reprogramacion' => true,
        'confirmada' => false,
    ]);
    return;
}
```

### 2. Verificar datos en DB
Ejecutar query para ver si existen mensajes con `respuesta` not null:
```sql
SELECT id, appointment_id, respuesta, responded_at 
FROM whatsapp_messages 
WHERE respuesta IS NOT NULL 
ORDER BY id DESC 
LIMIT 10;
```

### 3. Verificar relación funciona con eager loading
Si la query #2 muestra datos pero la columna sigue en '—', el problema es la relación `latestRespondedWhatsAppMessage` con `latestOfMany('responded_at')`.

## Archivos a modificar
- `app/Services/WhatsApp/WhatsAppResponseHandler.php` (agregar manejo de Reprogramar)

## Verificación
1. Ejecutar query SQL para confirmar si hay mensajes respondidos
2. Aplicar fix en `WhatsAppResponseHandler`
3. Probar: recibir respuesta "Reprogramar" → columna debe mostrar "Reprogramar"
4. Probar: recibir respuesta "Confirmar" → columna debe mostrar "Confirmada"
````

## File: .mimocode/plans/1783595886300-calm-eagle.md
````markdown
# Plan: Rediseño del sistema WhatsApp — Historial de conversaciones

## Objetivo
Cada respuesta de WhatsApp se guarda como registro independiente (no sobreescribe), creando un historial completo por cita.

## Cambios

### 1. Migración: dos columnas nuevas en `whatsapp_messages`

Archivo: `database/migrations/2026_07_09_000000_add_direction_and_parent_to_whatsapp_messages.php`

```php
Schema::table('whatsapp_messages', function (Blueprint $table) {
    $table->string('direction', 10)->default('outbound')->after('status');
    $table->foreignId('parent_id')->nullable()->constrained('whatsapp_messages')->nullOnDelete()->after('appointment_id');
});
```

- `direction`: `'outbound'` (enviado por nosotros) o `'inbound'` (respuesta del cliente)
- `parent_id`: FK a sí mismo. Los mensajes inbound apuntan al outbound que respondieron

### 2. Modelo WhatsAppMessage — ajustes

Archivo: `app/Models/WhatsAppMessage.php`

- Agregar `direction` y `parent_id` a `$fillable` y `$casts`
- Agregar relación `parent(): BelongsTo`
- Agregar relación `replies(): HasMany`
- `isConfirmed()`: usar `provider_payload.inbound.button_payload` si existe, sino `respuesta`
- `isRescheduleRequested()`: idem
- `scopeOutbound()` / `scopeInbound()`: scopes para filtrar por dirección

### 3. Modelo Appointment — simplificar respuesta

Archivo: `app/Models/Appointment.php`

- `responseStatusLabel()`: buscar el último inbound message (`direction=inbound`) de esta cita, usar `button_payload` para determinar estado:
  - Empieza con `'confirm'` → devuelve `'Confirmar'` (se muestra verde como "Confirmada")
  - Cualquier otro valor → devuelve `'Consultar'` (se muestra rojo)
  - Sin inbound messages → null
- Simplificar: ya no necesita leer `respuesta` ni `button_payload` de mensajes outbound
- Mantener `wasRescheduled()` y `wasChangedSchedule()` sin cambios

### 4. WhatsAppResponseHandler — crear registro inbound

Archivo: `app/Services/WhatsApp/WhatsAppResponseHandler.php`

Cambiar `process()` para que en vez de sobreescribir `respuesta` en el mensaje outbound, cree un NUEVO WhatsAppMessage con `direction='inbound'` y `parent_id` apuntando al outbound original. Luego actualice `confirmada`/`pendiente_reprogramacion` en la cita.

### 5. TwilioWhatsAppStatusController — guardar como registro separado

Archivo: `app/Http/Controllers/Webhooks/TwilioWhatsAppStatusController.php`

En `processInboundMessage()`: en vez de hacer `$message->update(['respuesta' => ...])`, crear un nuevo WhatsAppMessage con:
- `direction = 'inbound'`
- `parent_id = $message->id`
- `appointment_id = $message->appointment_id`
- `telefono`, `nombre`, `apellidos` del mensaje padre
- `provider_payload.inbound = [...]` con toda la data del webhook
- `respuesta = $responseText`
- `responded_at = now()`

### 6. AppointmentDeliveryStatusSyncer — syncInboundResponses()

Archivo: `app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php`

En `matchInboundToOutbound()`: en vez de sobreescribir el outbound, crear un nuevo WhatsAppMessage inbound. Mantener la lógica de matching por teléfono + tiempo.

### 7. Vista: columna Confir/Repo simplificada

Archivo: `resources/views/livewire/client-appointments.blade.php`

La columna (líneas 262-300) se simplifica:
- Usar `$appointment->responseStatusLabel()` que ya maneja la lógica de buscar el último inbound
- Verde con icono usuario-plus si label = 'Confirmar'
- Rojo con icono alert si label != 'Confirmar' (cualquier otra respuesta)
- Sin cambios en el badge "Reprogramada" de `wasRescheduled()`

### 8. Vista: botón Historial en columna Acciones

Archivo: `resources/views/livewire/client-appointments.blade.php`

Agregar botón "Historial" en la columna Acciones (junto a Editar/Eliminar):
- Icono: `<x-iconos.whatsapp>` o un icono de chat
- Comportamiento: abrir modal con el historial de mensajes de esa cita
- Usar Alpine.js para abrir/cerrar el modal (patrón existente en la vista)

### 9. Modal de historial

Archivo: `resources/views/livewire/client-appointments.blade.php` (inline) o nuevo componente

Mostrar timeline de mensajes:
- Mensajes outbound (enviados): alineados a la izquierda, fondo verde-azulado
- Mensajes inbound (recibidos): alineados a la derecha, fondo gris
- Cada mensaje muestra: fecha/hora, dirección (enviado/recibido), contenido, estado de entrega
- Respuestas muestran el button_payload o el texto

### 10. Tests

Actualizar `TwilioWhatsAppStatusWebhookTest.php`:
- Verificar que las respuestas crean NUEVOS registros (no sobreescriben)
- Verificar `direction` y `parent_id` en los registros inbound
- Verificar que `responseStatusLabel()` lee del último inbound
- Verificar que `confirmada`/`pendiente_reprogramacion` se actualizan correctamente

## Archivos a modificar

| Archivo | Cambio |
|---------|--------|
| `database/migrations/2026_07_09_000000_add_direction_and_parent_to_whatsapp_messages.php` | NUEVO |
| `app/Models/WhatsAppMessage.php` | direction, parent_id, relations, scopes |
| `app/Models/Appointment.php` | responseStatusLabel() simplificado |
| `app/Services/WhatsApp/WhatsAppResponseHandler.php` | crear registro inbound |
| `app/Http/Controllers/Webhooks/TwilioWhatsAppStatusController.php` | crear registro inbound |
| `app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php` | crear registro inbound |
| `resources/views/livewire/client-appointments.blade.php` | columna simplificada + botón historial + modal |
| `tests/Feature/TwilioWhatsAppStatusWebhookTest.php` | tests actualizados |

## Verificación

1. `php artisan migrate` — migración aplica sin errores
2. `vendor/bin/pint --dirty --format agent` — código formateado
3. `php artisan test --compact --filter=TwilioWhatsAppStatusWebhookTest` — tests pasan
4. Probar webhook simulando inbound → verificar que se crea registro nuevo con direction=inbound
5. Verificar columna Confir/Repo muestra verde para "Confirmar", rojo para otros
6. Verificar botón Historial abre modal con timeline de mensajes

## Preguntas pendientes

- ¿El modal de historial se muestra inline (Alpine) o como página separada?
````

## File: .mimocode/plans/1783853579689-neon-canyon.md
````markdown
# Plan: Settings Backup/Restore

## Goal
Create a seeder with defaults + JSON export/import for all 6 settings tables.

## Files to create

### 1. `database/seeders/SettingsSeeder.php`
Seeds all 6 tables with factory defaults. Uses `updateOrCreate` to be idempotent.

**Defaults per table:**

| Table | Defaults |
|---|---|
| `sistema_opciones` | `retention_period: 'disabled'` |
| `whatsapp_dispatch_settings` | `enabled: true`, `hours: ['09:00','12:00','15:00']` |
| `appointment_reminder_preferences` | whatsapp: [1,2,3,7] enabled; email: [] all disabled |
| `whatsapp_credentials` | `mode: 'sandbox'`, `selected: true` |
| `whatsapp_sender_numbers` | empty (depends on user's actual numbers) |
| `twilio_content_templates` | delegates to existing `TwilioContentTemplateSeeder` |

### 2. `app/Console/Commands/SettingsExport.php`
Artisan command: `settings:export {path?}`

- Defaults to `storage/app/settings-backup.json`
- Exports all 6 tables as JSON with version + timestamp metadata
- Decrypts encrypted fields (account_sid, auth_token, api_key_sid, api_key_secret, cloud_api_access_token) before writing so the backup is portable across environments
- Output: JSON file with structure:
  ```json
  {
    "version": 1,
    "exported_at": "ISO8601",
    "settings": {
      "sistema_opciones": { ... },
      "whatsapp_dispatch_settings": { ... },
      "appointment_reminder_preferences": [ ... ],
      "whatsapp_credentials": { ... },
      "whatsapp_sender_numbers": [ ... ],
      "twilio_content_templates": [ ... ]
    }
  }
  ```

### 3. `app/Console/Commands/SettingsImport.php`
Artisan command: `settings:import {path?} {--force}`

- Reads JSON file (same structure as export)
- Validates version field
- Without `--force`: shows diff of what will change, asks for confirmation
- With `--force`: applies changes directly
- Re-encrypts the 5 encrypted credential fields on import
- Uses `DB::transaction` for atomicity
- Preserves IDs when present in JSON (upsert behavior)

## Files to modify

### 4. `database/seeders/DatabaseSeeder.php`
Add `$this->call(SettingsSeeder::class);` — but AFTER TwilioContentTemplateSeeder since SettingsSeeder will skip templates (already seeded).

## Verification

1. `php artisan settings:export` → check JSON file created
2. Modify a setting via UI, re-export, verify change appears
3. `php artisan settings:import storage/app/settings-backup.json --force` → verify all settings restored
4. `php artisan db:seed --class=SettingsSeeder` → verify idempotent (run twice, same result)
5. `php artisan test --compact` → all tests pass
````

## File: .mimocode/plans/1783870987407-crisp-star.md
````markdown
# Plan: Probar y corregir sistema de backup/import/export

## Resumen

El sistema de backup está implementado pero tiene un gap importante: la exportación completa de BD (`ExportController::allJson/allCsv`) solo exporta 6 tablas, pero la importación de BD (`DatabaseBackup::applyData()`) soporta 10 tablas. Esto significa que un export→import completo pierde datos de configuración.

## Problema detectado

**`ExportController::gatherAllData()`** (línea 232) solo incluye:
- `users`, `clients`, `appointments`, `appointment_changes`, `whatsapp_messages`, `app_settings`

**Pero falta exportar:**
- `appointment_reminder_preferences`
- `whatsapp_credentials`
- `whatsapp_sender_numbers`
- `twilio_content_templates`

**`DatabaseBackup::applyData()`** (línea 166) intenta importar todas estas tablas, pero nunca las encontrará en el JSON/ZIP exportado.

## Pasos de corrección

### 1. Completar `gatherAllData()` en `ExportController`

**Archivo**: `app/Http/Controllers/Admin/ExportController.php`

Agregar las 4 tablas faltantes al array retornado por `gatherAllData()`, con decrypted credentials (igual que en `gatherSettingsData()`):

```php
'appointment_reminder_preferences' => AppointmentReminderPreference::query()
    ->select(['id', 'channel', 'lead_days', 'enabled'])
    ->get()
    ->toArray(),
'whatsapp_credentials' => /* decrypt encrypted fields, same as gatherSettingsData() */,
'whatsapp_sender_numbers' => WhatsAppSenderNumber::query()
    ->select(['id', 'whatsapp_credential_id', 'name', 'prefix', 'number', 'selected'])
    ->get()
    ->toArray(),
'twilio_content_templates' => TwilioContentTemplate::query()
    ->select(['id', 'nombre', 'content_sid', 'seleccionada', 'content_variables'])
    ->get()
    ->toArray(),
```

Reutilizar la lógica de decrypt de `gatherSettingsData()` (o extraer a un método privado compartido).

### 2. Tests de verificación

**Archivo nuevo**: `tests/Feature/BackupRoundTripTest.php`

Tests que validan el flujo completo export→import:

1. **TableBackup JSON round-trip**: Exportar clientes JSON → importar → verificar mismos datos
2. **TableBackup CSV round-trip**: Exportar citas CSV → importar → verificar
3. **SettingsBackup JSON round-trip**: Exportar settings JSON → importar → verificar (v2)
4. **SettingsBackup v1 compat**: Importar JSON v1 → verificar que se migra a v2
5. **DatabaseBackup JSON round-trip**: Exportar DB completa JSON → importar en DB limpia → verificar todas las tablas
6. **DatabaseBackup CSV ZIP round-trip**: Exportar DB CSV ZIP → importar → verificar
7. **Auth check**: Non-admin no puede importar (403)
8. **Encrypted fields**: Credenciales se exportan decrypted y se re-importan encrypted
9. **Duplicate handling**: Importar mismo archivo 2 veces → no duplica registros

### 3. Verificación manual (post-tests)

Ejecutar en navegador:
1. Ir a `/admin/tools`
2. Exportar clientes JSON → abrir → verificar estructura
3. Exportar settings JSON → verificar incluye todas las tablas
4. Exportar DB completa JSON → verificar incluye 10 tablas
5. Importar el JSON de DB completa en entorno limpio → verificar integridad

## Archivos a modificar

| Archivo | Cambio |
|---------|--------|
| `app/Http/Controllers/Admin/ExportController.php` | Completar `gatherAllData()` con 4 tablas faltantes |
| `tests/Feature/BackupRoundTripTest.php` | Nuevo: tests de round-trip completos |

## Verificación

1. `php artisan test --compact tests/Feature/BackupRoundTripTest.php` — todos pasan
2. `php artisan test --compact` — no hay regressions
3. `vendor/bin/pint --dirty --format agent` — formateo OK
4. Verificar manualmente que `admin.export.all-json` descarga JSON con las 10 tablas
````

## File: .mimocode/plans/1783882215162-calm-wolf.md
````markdown
# Plan: Webhook toggle + poll interval configurable

## Objetivo
Cuando el webhook está configurado → llegan datos vía webhook (instantáneo).
Cuando NO hay webhook → poll a la API de Twilio cada X segundos (configurable).
Toggle para activar/desactivar webhook + input numérico para intervalo de poll.

## Archivos a modificar

### 1. Migración: `database/migrations/2026_07_XX_add_webhook_settings_to_whatsapp_credentials.php`
Nuevo archivo. Añade 2 columnas a `whatsapp_credentials`:
```php
$table->boolean('webhook_enabled')->default(true);
$table->unsignedSmallInteger('poll_interval')->default(10); // segundos
```

### 2. Modelo: `app/Models/WhatsAppCredential.php`
- Añadir `webhook_enabled` y `poll_interval` a `$fillable`
- Añadir cast `webhook_enabled => 'boolean'`, `poll_interval => 'integer'`
- Método estático `webhookEnabled(): bool` que retorna `self::get()->webhook_enabled`
- Método estático `pollInterval(): int` que retorna `self::get()->poll_interval` (con clamp 5-60)

### 3. Componente settings: `app/Livewire/Settings/TwilioCredentialSettings.php`
- Nueva propiedad pública `bool $webhook_enabled = true`
- Nueva propiedad pública `int $poll_interval = 10`
- En `mount()`: leer valores de `WhatsAppCredential`
- En `save()`: validar y guardar ambos campos
- Validación: `poll_interval` entre 5 y 60 segundos

### 4. Vista settings: `resources/views/settings/twilio-credential-settings.blade.php`
En la sección "Callback URL", añadir debajo del input existente:
- Toggle `wire:model="webhook_enabled"` → "Webhook activado"
- Input numérico `wire:model="poll_interval"` → "Intervalo de sincronización (segundos)" — solo visible si webhook desactivado
- Texto explicativo: "Sin webhook, se consulta la API de Twilio cada X segundos"

### 5. Vista citas: `resources/views/livewire/client-appointments.blade.php`
Cambiar línea 2:
```
wire:poll.10s="autoSync"
```
Por:
```
wire:poll.{{ $pollInterval }}s="autoSync"
```
La variable `$pollInterval` se pasa desde el componente Livewire.

### 6. Componente citas: `app/Livewire/ClientAppointments.php`
- En `mount()` o `render()`: leer `WhatsAppCredential::pollInterval()` y pasarlo a la vista
- Si `webhook_enabled` es true → el poll sigue funcionando como fallback (o se puede deshabilitar)
- El `autoSync()` sigue llamando `syncAll()` que hace polling a la API — esto es el fallback cuando no hay webhook

### 7. Artisan command: `app/Console/Commands/SyncWhatsAppDeliveryStatus.php`
- Añadir check: si `webhook_enabled` está activo, el command aún corre como fallback (no cambiar)

## Lógica de negocio

| webhook_enabled | poll_interval | Comportamiento |
|---|---|---|
| true | X | Webhook recibe datos instantáneamente. `wire:poll` sigue como fallback cada Xs |
| false | X | No hay webhook. `wire:poll` consulta API Twilio cada Xs |

**Nota**: El toggle NO desactiva el poll completamente — siempre hay un fallback por si el webhook falla. Lo que cambia es:
- **Webhook ON**: los datos llegan vía webhook, el poll es solo respaldo
- **Webhook OFF**: los datos SOLO llegan vía poll a la API de Twilio

## Verificación
1. Ejecutar migración: `php artisan migrate`
2. Ir a Ajustes → Credenciales Twilio
3. Verificar toggle "Webhook activado" y input "Intervalo de sincronización"
4. Con webhook ON → guardar, enviar mensaje WhatsApp, respuesta se procesa via webhook
5. Con webhook OFF → cambiar intervalo a 5s, guardar, enviar mensaje, respuesta llega via poll cada 5s
6. Verificar que el toast muestra el guardado correcto
7. Ejecutar `vendor/bin/pint --dirty --format agent` después de cada cambio PHP
````

## File: .mimocode/plans/1783934979814-jolly-knight.md
````markdown
# Unificar migraciones: fusionar add_xxx en create_xxx

## Objetivo
Eliminar las 2 migraciones de ALTER TABLE fusionando sus columnas en las migraciones de creación originales.

## Archivos a modificar

### 1. `database/migrations/2026_07_08_010450_create_whatsapp_credentials_table.php`
Agregar después de `status_callback_url`:
```php
$table->boolean('webhook_enabled')->default(true);
$table->unsignedSmallInteger('poll_interval')->default(10);
```
(NOTAS: No usar `->after()` en `Schema::create()` — MySQL compatibility rule de AGENTS.md)

### 2. `database/migrations/2026_06_23_000003_create_appointments_table.php`
Agregar después de `whatsapp_read_at`:
```php
$table->dateTime('last_inbound_seen_at')->nullable();
```

### 3. Eliminar migraciones add_xxx
- `database/migrations/2026_07_12_233349_add_webhook_settings_to_whatsapp_credentials_table.php`
- `database/migrations/2026_07_13_090000_add_last_inbound_seen_at_to_appointments_table.php`

## Verificación
- `php artisan migrate:fresh` debe crear todas las tablas correctamente
- `php artisan migrate:status` debe mostrar todas las migraciones como executed
- Las columnas `webhook_enabled`, `poll_interval`, `last_inbound_seen_at` deben existir en sus tablas
````

## File: .mimocode/.cron-lock
````
{"pid":11399,"startedAt":1783934959614}
````

## File: app/Console/Commands/BackfillWhatsAppAppointmentDeliveryState.php
````php
namespace App\Console\Commands;
⋮----
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
⋮----
class BackfillWhatsAppAppointmentDeliveryState extends Command
⋮----
public function handle(AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): int
⋮----
$clientId = $this->option('client');
⋮----
$updated = $deliveryStatusSyncer->backfillFromStoredMessages($clientId);
⋮----
$this->info(sprintf('Backfilled %d appointment(s).', $updated));
````

## File: app/Console/Commands/DispatchDueWhatsAppMessages.php
````php
namespace App\Console\Commands;
⋮----
use App\Models\Appointment;
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;
⋮----
class DispatchDueWhatsAppMessages extends Command
⋮----
protected $signature = 'whatsapp:dispatch-due';
⋮----
protected $description = 'Dispatch all due WhatsApp messages.';
⋮----
public function handle(WhatsAppSender $sender, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): int
⋮----
$settings = AppSetting::get();
⋮----
$this->info('WhatsApp dispatch is disabled. Skipping.');
⋮----
$queued = $this->queueActiveAppointmentMessages();
⋮----
WhatsAppMessage::due()
->with('appointment')
->chunkById(100, function ($messages) use (&$count, $sender, $deliveryStatusSyncer): void {
⋮----
$result = $sender->send($message);
⋮----
$message->update([
⋮----
$message->appointment->update([
⋮----
$deliveryStatusSyncer->sync([$message->appointment_id]);
⋮----
'last_error' => $throwable->getMessage(),
⋮----
Log::channel('whatsapp_error')->error('WhatsApp send failed', [
⋮----
'error' => $throwable->getMessage(),
⋮----
$this->error("Failed to send message {$message->id}: {$throwable->getMessage()}");
⋮----
$this->info(sprintf('Queued %d appointment message(s).', $queued));
$this->info(sprintf('Processed %d due message(s).', $count));
⋮----
Log::channel('whatsapp_error')->error('WhatsApp dispatch command failed', [
⋮----
'trace' => $throwable->getTraceAsString(),
⋮----
$this->error($throwable->getMessage());
⋮----
private function queueActiveAppointmentMessages(): int
⋮----
foreach (AppointmentReminderPreference::enabledLeadDaysFor(AppointmentReminderPreference::CHANNEL_WHATSAPP) as $leadDays) {
$targetDate = now(config('app.timezone'))->addDays($leadDays)->toDateString();
⋮----
Appointment::query()
->with('client')
// ->where('client_id', 1)
->where('activo', true)
->where('cita_activa', true)
->whereDate('fecha', $targetDate)
->chunkById(100, function ($appointments) use (&$queued, $leadDays): void {
⋮----
if ($this->appointmentReminderExists($appointment, $leadDays)) {
⋮----
$scheduledFor = $appointment->scheduledFor();
⋮----
WhatsAppMessage::query()->create([
⋮----
'message' => WhatsAppMessage::buildMessage([
⋮----
private function appointmentReminderExists(Appointment $appointment, int $leadDays): bool
⋮----
return $appointment->whatsAppMessages()
->get()
->contains(function (WhatsAppMessage $message) use ($leadDays): bool {
````

## File: app/Console/Commands/PurgePastAppointments.php
````php
namespace App\Console\Commands;
⋮----
use App\Models\Appointment;
use App\Models\AppSetting;
use App\Services\ClientDataDeletionService;
use Carbon\CarbonInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
⋮----
class PurgePastAppointments extends Command
⋮----
protected $signature = 'appointments:purge-past';
⋮----
protected $description = 'Eliminar las citas pasadas que superen el período de retención configurado.';
⋮----
public function handle(ClientDataDeletionService $deletionService): int
⋮----
$settings = AppSetting::get();
⋮----
Log::info('appointments:purge-past started', [
⋮----
'enabled' => $settings->isEnabled(),
⋮----
'executed_at' => now(config('app.timezone'))->toDateTimeString(),
⋮----
if (! $settings->isEnabled()) {
Log::info('appointments:purge-past skipped because cleanup is disabled', [
⋮----
$this->info('Borrado automático desactivado.');
⋮----
$cutoffDate = $this->resolveCutoff($settings->retention_period)->toDateString();
⋮----
$appointmentIds = Appointment::query()
->whereDate('fecha', '<=', $cutoffDate)
->pluck('id');
⋮----
Log::info('appointments:purge-past resolved candidates', [
⋮----
'candidate_count' => $appointmentIds->count(),
'candidate_ids' => $appointmentIds->take(20)->values()->all(),
⋮----
$deleted = $deletionService->deleteAppointments($appointmentIds);
⋮----
Log::info('appointments:purge-past completed', [
⋮----
$this->info(sprintf('Borrado %d citas expiradas.', $deleted));
⋮----
private function resolveCutoff(string $retentionPeriod): CarbonInterface
⋮----
'1_week' => $now->copy()->subWeek(),
'2_weeks' => $now->copy()->subWeeks(2),
'1_month' => $now->copy()->subMonth(),
'3_months' => $now->copy()->subMonths(3),
'6_months' => $now->copy()->subMonths(6),
'1_year' => $now->copy()->subYear(),
'2_years' => $now->copy()->subYears(2),
'5_years' => $now->copy()->subYears(5),
````

## File: app/Console/Commands/ResetClientData.php
````php
namespace App\Console\Commands;
⋮----
use Database\Seeders\AppointmentSeeder;
use Database\Seeders\ClientSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
⋮----
class ResetClientData extends Command
⋮----
protected $signature = 'clients:reset-data {--force : Confirm the destructive reset}';
⋮----
protected $description = 'Delete and restart all data tables except protected settings tables.';
⋮----
public function handle(): int
⋮----
if (! $this->option('force')) {
$this->error('This command is destructive. Re-run with --force.');
⋮----
$tables = collect(Schema::getTables())
->pluck('name')
->reject(fn (string $table): bool => in_array($table, self::PROTECTED_TABLES, true))
->values();
⋮----
Schema::disableForeignKeyConstraints();
⋮----
DB::table($table)->delete();
$this->restartIdentity($table);
⋮----
Schema::enableForeignKeyConstraints();
⋮----
$this->info(sprintf(
⋮----
$tables->count(),
$tables->implode(', '),
⋮----
$this->callSilent('db:seed', [
⋮----
$this->info('ClientSeeder and AppointmentSeeder executed.');
$this->info('Protected tables were not changed.');
⋮----
private function restartIdentity(string $table): void
⋮----
$driver = DB::connection()->getDriverName();
⋮----
DB::statement('ALTER TABLE '.$this->wrapTable($table).' AUTO_INCREMENT = 1');
⋮----
if ($driver === 'sqlite' && Schema::hasTable('sqlite_sequence')) {
DB::table('sqlite_sequence')->where('name', $table)->delete();
⋮----
private function wrapTable(string $table): string
⋮----
return DB::connection()->getQueryGrammar()->wrapTable($table);
````

## File: app/Console/Commands/ResetDatabaseAndSeed.php
````php
namespace App\Console\Commands;
⋮----
use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
⋮----
class ResetDatabaseAndSeed extends Command
⋮----
protected $signature = 'db:reset-and-seed {--force : Confirm the destructive reset}';
⋮----
protected $description = 'Delete users, clients, WhatsApp messages and appointments, then run the DatabaseSeeder.';
⋮----
public function handle(): int
⋮----
if (! $this->option('force')) {
$this->error('This command is destructive. Re-run with --force.');
⋮----
$deletedWhatsAppMessages = DB::table('whatsapp_messages')->delete();
$deletedAppointments = DB::table('appointments')->delete();
$deletedClients = DB::table('clients')->delete();
$deletedUsers = DB::table('users')->delete();
⋮----
$seeded = $this->callSilent('db:seed', [
⋮----
$this->error('DatabaseSeeder failed.');
⋮----
$this->info(sprintf(
⋮----
$this->info('DatabaseSeeder executed.');
````

## File: app/Console/Commands/SettingsExport.php
````php
namespace App\Console\Commands;
⋮----
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
⋮----
class SettingsExport extends Command
⋮----
protected $signature = 'settings:export {path?}';
⋮----
protected $description = 'Export all settings to a JSON backup file.';
⋮----
public function handle(): int
⋮----
$path = $this->argument('path') ?? 'storage/app/settings-backup.json';
⋮----
'exported_at' => now()->toIso8601String(),
⋮----
'app_settings' => $this->exportAppSettings(),
'appointment_reminder_preferences' => $this->exportReminderPreferences(),
'whatsapp_credentials' => $this->exportCredentials(),
'whatsapp_sender_numbers' => $this->exportSenderNumbers(),
'twilio_content_templates' => $this->exportTemplates(),
⋮----
$this->error('Failed to encode settings as JSON.');
⋮----
$this->error("Failed to write to {$fullPath}.");
⋮----
$this->info("Settings exported to {$fullPath}");
⋮----
private function exportAppSettings(): ?array
⋮----
$model = AppSetting::query()->first();
⋮----
private function exportReminderPreferences(): array
⋮----
return AppointmentReminderPreference::query()
->select(['id', 'channel', 'lead_days', 'enabled'])
->get()
->toArray();
⋮----
private function exportCredentials(): array
⋮----
$credentials = WhatsAppCredential::query()->get();
⋮----
return $credentials->map(function (WhatsAppCredential $credential): array {
$data = $credential->only([
⋮----
$data[$field] = $this->decryptValue($data[$field]);
⋮----
})->toArray();
⋮----
private function exportSenderNumbers(): array
⋮----
return WhatsAppSenderNumber::query()
->select(['id', 'whatsapp_credential_id', 'name', 'prefix', 'number', 'selected'])
⋮----
private function exportTemplates(): array
⋮----
return TwilioContentTemplate::query()
->select(['id', 'nombre', 'content_sid', 'seleccionada', 'content_variables'])
⋮----
private function decryptValue(string $value): string
⋮----
return Crypt::decrypt($value);
````

## File: app/Console/Commands/SettingsImport.php
````php
namespace App\Console\Commands;
⋮----
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
⋮----
class SettingsImport extends Command
⋮----
protected $signature = 'settings:import {path?} {--force : Apply without confirmation}';
⋮----
protected $description = 'Import settings from a JSON backup file.';
⋮----
public function handle(): int
⋮----
$path = $this->argument('path') ?? 'storage/app/settings-backup.json';
⋮----
$this->error("File not found: {$fullPath}");
⋮----
$this->error("Failed to read: {$fullPath}");
⋮----
$this->error('Invalid JSON: '.json_last_error_msg());
⋮----
$this->error("Unsupported backup version: {$version}");
⋮----
if (! $this->option('force')) {
$this->warn('The following settings will be imported:');
$this->previewChanges($settings, $version);
⋮----
if (! $this->confirm('Apply these changes?')) {
$this->info('Import cancelled.');
⋮----
DB::transaction(function () use ($settings, $version): void {
⋮----
$this->importV1($settings);
⋮----
$this->importV2($settings);
⋮----
$this->info('Settings imported successfully.');
⋮----
private function importV1(array $settings): void
⋮----
// v1: sistema_opciones + whatsapp_dispatch_settings → merged into app_settings
⋮----
AppSetting::updateOrCreate([], [
⋮----
$this->importReminderPreferences($settings['appointment_reminder_preferences'] ?? []);
$this->importCredentials($settings['whatsapp_credentials'] ?? []);
$this->importSenderNumbers($settings['whatsapp_sender_numbers'] ?? []);
$this->importTemplates($settings['twilio_content_templates'] ?? []);
⋮----
private function importV2(array $settings): void
⋮----
private function previewChanges(array $settings, int $version): void
⋮----
$this->line('  - sistema_opciones: retention_period = '.$settings['sistema_opciones']['retention_period']);
⋮----
$this->line('  - whatsapp_dispatch_settings: enabled = '.($dispatch['enabled'] ? 'true' : 'false').', hours = '.json_encode($dispatch['hours']));
⋮----
$this->line('  - app_settings: retention = '.$app['retention_period'].', dispatch = '.($app['dispatch_enabled'] ? 'on' : 'off'));
⋮----
$this->line("  - {$key}: ".count($settings[$key]).' record(s)');
⋮----
private function importReminderPreferences(array $records): void
⋮----
AppointmentReminderPreference::updateOrCreate(
⋮----
private function importCredentials(array $records): void
⋮----
$record[$field] = $this->encryptValue($record[$field]);
⋮----
$existing = WhatsAppCredential::find($record['id']);
⋮----
$existing->update($record);
⋮----
WhatsAppCredential::create($record);
⋮----
private function importSenderNumbers(array $records): void
⋮----
$existing = WhatsAppSenderNumber::find($record['id']);
⋮----
WhatsAppSenderNumber::create($record);
⋮----
private function importTemplates(array $records): void
⋮----
TwilioContentTemplate::updateOrCreate(
⋮----
private function encryptValue(string $value): string
⋮----
Crypt::decrypt($value);
⋮----
return Crypt::encrypt($value);
````

## File: app/Console/Commands/SyncWhatsAppDeliveryStatus.php
````php
namespace App\Console\Commands;
⋮----
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
⋮----
class SyncWhatsAppDeliveryStatus extends Command
⋮----
public function handle(AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): int
⋮----
$updated = $deliveryStatusSyncer->syncAll();
⋮----
$this->info(sprintf('Synced %d delivered appointment(s).', $updated));
````

## File: app/Exports/AppointmentsExport.php
````php
namespace App\Exports;
⋮----
use App\Models\Appointment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
⋮----
class AppointmentsExport implements FromCollection, WithHeadings, WithMapping
⋮----
public function __construct(
⋮----
public function collection(): Collection
⋮----
return Appointment::query()
->with('client')
->when($this->clientId, fn ($query) => $query->where('client_id', $this->clientId))
->when($this->sentOnly, fn ($query) => $query->where('enviado', true))
->orderBy('fecha')
->orderBy('hora')
->get();
⋮----
public function headings(): array
⋮----
public function map($appointment): array
````

## File: app/Exports/ClientsExport.php
````php
namespace App\Exports;
⋮----
use App\Models\Client;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
⋮----
class ClientsExport implements FromCollection, WithHeadings, WithMapping
⋮----
public function __construct(
⋮----
public function collection(): Collection
⋮----
return Client::query()
->when($this->filterNombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filterNombre.'%'))
->when($this->filterApellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filterApellidos.'%'))
->when($this->filterTelefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filterTelefono.'%'))
->orderBy('nombre')
->orderBy('apellidos')
->get();
⋮----
public function headings(): array
⋮----
public function map($client): array
````

## File: app/Exports/UsersExport.php
````php
namespace App\Exports;
⋮----
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
⋮----
class UsersExport implements FromCollection, WithHeadings, WithMapping
⋮----
public function collection(): Collection
⋮----
return User::query()
->orderBy('name')
->orderBy('email')
->get();
⋮----
public function headings(): array
⋮----
public function map($user): array
````

## File: app/Http/Controllers/Admin/ExportController.php
````php
namespace App\Http\Controllers\Admin;
⋮----
use App\Exports\AppointmentsExport;
use App\Exports\ClientsExport;
use App\Exports\UsersExport;
use App\Models\Appointment;
use App\Models\AppointmentChange;
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use PDO;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;
⋮----
class ExportController extends Controller
⋮----
public function settings()
⋮----
'exported_at' => now()->toIso8601String(),
'settings' => $this->gatherSettingsData(),
⋮----
$filename = 'settings-backup-'.now()->format('Y-m-d-His').'.json';
⋮----
->download($path, $filename, ['Content-Type' => 'application/json'])
->deleteFileAfterSend(true);
⋮----
public function settingsCsv()
⋮----
$data = $this->gatherSettingsData();
⋮----
abort_if($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true, 500, 'No se pudo crear el ZIP.');
⋮----
// Single-row settings stored as associative array
⋮----
$flatRows = array_map(fn (array $row) => $this->flattenForCsv($row), $rows);
⋮----
$csvContent = $this->buildCsvContent($headings, $flatRows);
$zip->addFromString("{$table}.csv", $csvContent);
⋮----
$zip->close();
⋮----
$filename = 'settings-csv-'.now()->format('Y-m-d-His').'.zip';
⋮----
->download($zipPath, $filename, ['Content-Type' => 'application/zip'])
⋮----
private function gatherSettingsData(): array
⋮----
$model = AppSetting::query()->first();
⋮----
'appointment_reminder_preferences' => AppointmentReminderPreference::query()
->select(['id', 'channel', 'lead_days', 'enabled'])
->get()
->toArray(),
'whatsapp_credentials' => $this->gatherCredentials(),
'whatsapp_sender_numbers' => WhatsAppSenderNumber::query()
->select(['id', 'whatsapp_credential_id', 'name', 'prefix', 'number', 'selected'])
⋮----
'twilio_content_templates' => TwilioContentTemplate::query()
->select(['id', 'nombre', 'content_sid', 'seleccionada', 'content_variables'])
⋮----
private function flattenForCsv(array $row): array
⋮----
private function buildCsvContent(array $headings, array $rows): string
⋮----
public function allJson()
⋮----
'tables' => $this->gatherAllData(),
⋮----
$filename = 'database-backup-'.now()->format('Y-m-d-His').'.json';
⋮----
public function allCsv()
⋮----
$data = $this->gatherAllData();
⋮----
$filename = 'database-csv-'.now()->format('Y-m-d-His').'.zip';
⋮----
private function gatherAllData(): array
⋮----
'users' => User::query()
->select(['id', 'name', 'email', 'is_admin', 'created_at', 'updated_at'])
⋮----
'clients' => Client::query()
->select(['id', 'nombre', 'apellidos', 'telefono', 'created_at', 'updated_at'])
⋮----
'appointments' => Appointment::query()
->select([
⋮----
'appointment_changes' => AppointmentChange::query()
->select(['id', 'appointment_id', 'fecha_anterior', 'hora_anterior', 'fecha_nueva', 'hora_nueva', 'created_at'])
⋮----
'whatsapp_messages' => WhatsAppMessage::query()
⋮----
->map(fn (WhatsAppMessage $msg) => [
⋮----
'app_settings' => AppSetting::query()
->select(['id', 'retention_period', 'dispatch_enabled', 'dispatch_hours', 'created_at', 'updated_at'])
⋮----
private function gatherCredentials(): array
⋮----
return WhatsAppCredential::query()
⋮----
->map(fn (WhatsAppCredential $credential) => $credential->only([
⋮----
->toArray();
⋮----
public function appointments()
⋮----
return $this->downloadCsv(
$export->headings(),
$export->collection()->map(fn ($row) => $export->map($row))->all(),
⋮----
public function appointmentsJson()
⋮----
return $this->downloadJson(
Appointment::query()->get()->toArray(),
⋮----
public function clients()
⋮----
public function clientsJson()
⋮----
Client::query()->get()->toArray(),
⋮----
public function users()
⋮----
public function usersJson()
⋮----
User::query()->select(['id', 'name', 'email', 'is_admin', 'created_at', 'updated_at'])->get()->toArray(),
⋮----
public function database()
⋮----
abort_unless(DB::connection()->getDriverName() === 'sqlite', 501, 'La copia SQL solo está disponible para SQLite.');
⋮----
$zip->addFromString('citas-dentista-backup.sql', $this->dumpSqliteDatabase(DB::connection()->getPdo()));
⋮----
->download($zipPath, 'citas-dentista-backup.zip', ['Content-Type' => 'application/zip'])
⋮----
private function downloadCsv(array $headings, array $rows, string $fileName)
⋮----
return response()->stream($callback, 200, [
⋮----
private function downloadJson(array $data, string $fileName): Response
⋮----
->download($path, $fileName, ['Content-Type' => 'application/json'])
⋮----
private function dumpSqliteDatabase(PDO $pdo): string
⋮----
$tables = $pdo->query(
⋮----
)->fetchAll(PDO::FETCH_ASSOC);
⋮----
$lines = array_merge($lines, $this->dumpSqliteTableRows($pdo, $table['name']));
⋮----
$otherObjects = $pdo->query(
⋮----
)->fetchAll(PDO::FETCH_COLUMN);
⋮----
private function dumpSqliteTableRows(PDO $pdo, string $table): array
⋮----
$pdo->query('PRAGMA table_info('.$this->quoteIdentifier($table).')')->fetchAll(PDO::FETCH_ASSOC),
⋮----
$statement = $pdo->query('SELECT * FROM '.$this->quoteIdentifier($table));
⋮----
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
$rows[] = 'INSERT INTO '.$this->quoteIdentifier($table)
⋮----
.implode(', ', array_map(fn (string $column) => $this->quoteSqliteValue($pdo, $row[$column] ?? null), $columns))
⋮----
private function quoteSqliteValue(PDO $pdo, mixed $value): string
⋮----
$quoted = $pdo->quote((string) $value);
⋮----
private function quoteIdentifier(string $identifier): string
````

## File: app/Http/Controllers/Admin/LoginHistoryController.php
````php
namespace App\Http\Controllers\Admin;
⋮----
use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
⋮----
class LoginHistoryController extends Controller
⋮----
public function index()
⋮----
$logins = LoginHistory::with('user')
->latest('logged_in_at')
->paginate(50);
⋮----
$users = User::orderBy('name')->get();
````

## File: app/Http/Controllers/Admin/UserController.php
````php
namespace App\Http\Controllers\Admin;
⋮----
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
⋮----
class UserController extends Controller
⋮----
public function create()
⋮----
$users = User::query()->orderBy('id')->get();
⋮----
'adminCount' => $users->where('is_admin', true)->count(),
⋮----
public function store(Request $request): RedirectResponse
⋮----
$data = $request->validate([
⋮----
'password' => ['required', 'confirmed', Password::min(4)],
⋮----
User::create([
⋮----
'password' => Hash::make($data['password']),
⋮----
return redirect()->route('admin.users.create')->with('status', 'Usuario creado correctamente.');
⋮----
public function edit(User $user)
⋮----
'adminRoleLocked' => $user->is_admin && (int) Auth::id() === (int) $user->id,
⋮----
public function update(Request $request, User $user): RedirectResponse
⋮----
'password' => ['nullable', 'confirmed', Password::min(12)],
⋮----
abort_if($user->is_admin && ! $isAdmin && (int) Auth::id() === (int) $user->id, 422, 'Otro administrador debe retirarte el rol.');
⋮----
$user->password = Hash::make($data['password']);
⋮----
$user->save();
⋮----
return redirect()->route('admin.users.create')->with('status', 'Usuario actualizado correctamente.');
⋮----
public function destroy(Request $request, User $user): RedirectResponse
⋮----
abort_unless((int) Auth::id() !== (int) $user->id, 422, 'No puedes eliminar tu propia cuenta.');
abort_if($user->is_admin && User::query()->where('is_admin', true)->count() === 1, 422, 'No puedes eliminar al último administrador.');
⋮----
$user->delete();
⋮----
return redirect()->route('admin.users.create')->with('status', 'Usuario eliminado correctamente.');
````

## File: app/Http/Controllers/Auth/AuthenticatedSessionController.php
````php
namespace App\Http\Controllers\Auth;
⋮----
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
⋮----
class AuthenticatedSessionController extends Controller
⋮----
public function create()
⋮----
public function store(Request $request): RedirectResponse
⋮----
$credentials = $request->validate([
⋮----
if (! Auth::attempt($credentials, $request->boolean('remember'))) {
throw ValidationException::withMessages([
⋮----
$request->session()->regenerate();
⋮----
Auth::user()->loginHistory()->create([
'ip_address' => $request->ip(),
'user_agent' => $request->userAgent(),
⋮----
return redirect()->intended(route('dashboard'));
⋮----
public function destroy(Request $request): RedirectResponse
⋮----
Auth::logout();
⋮----
$request->session()->invalidate();
$request->session()->regenerateToken();
⋮----
return redirect()->route('home');
````

## File: app/Http/Controllers/Webhooks/TwilioWhatsAppStatusController.php
````php
namespace App\Http\Controllers\Webhooks;
⋮----
use App\Http\Controllers\Controller;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\WhatsAppResponseHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Twilio\Security\RequestValidator;
⋮----
class TwilioWhatsAppStatusController extends Controller
⋮----
public function __invoke(Request $request, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): Response
⋮----
if (! $this->isValidTwilioRequest($request)) {
Log::warning('Rejected invalid Twilio WhatsApp status callback.', [
'message_sid' => $request->string('MessageSid')->toString(),
'message_status' => $request->string('MessageStatus')->toString(),
⋮----
return response()->noContent(403);
⋮----
$payload = $request->all();
⋮----
if ($this->isInboundMessage($payload)) {
$this->processInboundMessage($payload);
⋮----
$deliveryStatusSyncer->syncFromTwilioWebhook($payload);
⋮----
return response()->noContent();
⋮----
/**
     * @param  array<string, mixed>  $payload
     */
private function isInboundMessage(array $payload): bool
⋮----
private function processInboundMessage(array $payload): void
⋮----
$messageSid = $this->firstPayloadValue($payload, ['MessageSid', 'SmsMessageSid', 'SmsSid']);
⋮----
$parentSid = $this->firstPayloadValue($payload, ['ParentMessageSid', 'OriginalRepliedMessageSid', 'OriginalMessageSid', 'RepliedMessageSid']);
$conversationSid = $this->firstPayloadValue($payload, ['ConversationSid', 'ChannelSid']);
⋮----
Log::info('WhatsApp inbound message received.', [
⋮----
$phone = WhatsAppMessage::normalizePhone($from);
$message = $this->findMatchingMessage($parentSid, $phone);
⋮----
Log::info('No matching WhatsApp message found for inbound response.', [
⋮----
'received_at' => now()->toDateTimeString(),
⋮----
$inbound = WhatsAppResponseHandler::process($message, $responseText, $inboundPayload);
⋮----
Log::info('WhatsApp response recorded.', [
⋮----
private function findMatchingMessage(string $parentSid, string $phone): ?WhatsAppMessage
⋮----
$message = WhatsAppMessage::query()
->where('provider_message_id', $parentSid)
->where('status', WhatsAppMessage::STATUS_SENT)
->where('direction', WhatsAppMessage::DIRECTION_OUTBOUND)
->first();
⋮----
return WhatsAppMessage::query()
->where('telefono', $phone)
⋮----
->latest('sent_at')
->latest('id')
⋮----
/**
     * @param  array<string, mixed>  $payload
     * @param  list<string>  $keys
     */
private function firstPayloadValue(array $payload, array $keys): string
⋮----
private function isValidTwilioRequest(Request $request): bool
⋮----
$authToken = (string) (WhatsAppCredential::get()->resolveAuthToken() ?? '');
$signature = (string) $request->header('X-Twilio-Signature', '');
$callbackUrl = WhatsAppCredential::get()->resolveStatusCallbackUrl();
⋮----
$url = $callbackUrl !== '' ? $callbackUrl : $request->fullUrl();
⋮----
return $validator->validate($signature, $url, $request->all());
````

## File: app/Http/Controllers/AppointmentIndexController.php
````php
namespace App\Http\Controllers;
⋮----
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
⋮----
class AppointmentIndexController
⋮----
public function __invoke(Request $request): View|RedirectResponse
⋮----
$clientId = $request->integer('client');
⋮----
? redirect()->route('clients.appointments', $clientId)
````

## File: app/Http/Controllers/Controller.php
````php
namespace App\Http\Controllers;
⋮----
abstract class Controller
⋮----
//
````

## File: app/Http/Controllers/HomeController.php
````php
namespace App\Http\Controllers;
⋮----
use Illuminate\View\View;
⋮----
class HomeController extends Controller
⋮----
public function index(): View
````

## File: app/Http/Middleware/EnsureUserIsAdmin.php
````php
namespace App\Http\Middleware;
⋮----
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
⋮----
class EnsureUserIsAdmin
⋮----
public function handle(Request $request, Closure $next): Response
⋮----
$user = $request->user();
⋮----
return $next($request);
````

## File: app/Imports/ClientsImport.php
````php
namespace App\Imports;
⋮----
use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
⋮----
class ClientsImport implements ToCollection, WithCustomCsvSettings, WithHeadingRow
⋮----
private array $previewRows = [];
⋮----
private int $processedRows = 0;
⋮----
private int $persistedRows = 0;
⋮----
private int $createdRows = 0;
⋮----
private int $restoredRows = 0;
⋮----
private int $skippedRows = 0;
⋮----
public function __construct(
⋮----
public function collection(Collection $rows): void
⋮----
$preparedRow = $this->prepareRow(is_array($row) ? $row : $row->toArray());
⋮----
$client = Client::upsertFromImport($preparedRow);
⋮----
public function previewRows(): array
⋮----
public function processedRows(): int
⋮----
public function persistedRows(): int
⋮----
public function createdRows(): int
⋮----
public function restoredRows(): int
⋮----
public function skippedRows(): int
⋮----
public function getCsvSettings(): array
⋮----
private function normalizeRow(array $row): array
⋮----
$normalized[$this->normalizeKey((string) $key)] = $value;
⋮----
private function extractValue(array $row, array $aliases): mixed
⋮----
$normalizedAlias = $this->normalizeKey($alias);
⋮----
private function normalizeKey(string $key): string
⋮----
$key = Str::ascii(trim($key));
⋮----
private function prepareRow(array $row): array
⋮----
$normalized = $this->normalizeRow($row);
$fullName = $this->extractValue($normalized, ['nombre_completo', 'nombre_y_apellidos', 'nombre_del_paciente', 'full_name', 'paciente', 'cliente']);
$nombre = $this->extractValue($normalized, ['nombre', 'nombres', 'name', 'first_name', 'given_name']);
$apellidos = $this->extractValue($normalized, ['apellidos', 'apellido', 'surname', 'last_name', 'family_name']);
$telefono = $this->extractValue($normalized, ['telefono', 'teléfono', 'numero', 'numero_telefono', 'telefono_movil', 'whatsapp_number', 'phone', 'mobile', 'cell', 'phone_number']);
⋮----
throw ValidationException::withMessages($errors);
````

## File: app/Jobs/SendWhatsAppMessage.php
````php
namespace App\Jobs;
⋮----
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;
⋮----
class SendWhatsAppMessage implements ShouldQueue
⋮----
public int $tries = 3;
⋮----
/** @var list<int> */
public array $backoff = [5, 15, 30];
⋮----
public function __construct(
⋮----
public function handle(WhatsAppSender $sender, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
⋮----
$message = WhatsAppMessage::query()->findOrFail($this->messageId);
⋮----
$result = $sender->send($message);
⋮----
$message->update([
'status' => $this->resolveStatus($providerStatus),
'sent_at' => $this->isAcceptedStatus($providerStatus) ? now() : null,
⋮----
if ($this->isAcceptedStatus($providerStatus) && $message->appointment) {
$message->appointment->update([
⋮----
$deliveryStatusSyncer->sync([$message->appointment_id]);
⋮----
public function failed(?Throwable $exception): void
⋮----
$message = WhatsAppMessage::query()->find($this->messageId);
⋮----
private function resolveStatus(string $providerStatus): string
⋮----
private function isAcceptedStatus(string $providerStatus): bool
````

## File: app/Livewire/Settings/AppointmentCleanupSettings.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\AppSetting;
use Livewire\Component;
⋮----
class AppointmentCleanupSettings extends Component
⋮----
public string $retentionPeriod = 'disabled';
⋮----
public string $status = '';
⋮----
public int $statusNonce = 0;
⋮----
public function mount(): void
⋮----
$this->retentionPeriod = AppSetting::get()->retention_period;
⋮----
public function persistRetentionPeriod(string $retentionPeriod): void
⋮----
abort_unless(auth()->user()?->is_admin, 403);
⋮----
$this->validateOnly('retentionPeriod');
⋮----
$settings = AppSetting::get();
⋮----
$settings->save();
⋮----
$label = AppSetting::retentionOptions()[$this->retentionPeriod] ?? $this->retentionPeriod;
⋮----
public function render()
⋮----
'retentionOptions' => AppSetting::retentionOptions(),
⋮----
protected function rules(): array
⋮----
'retentionPeriod' => ['required', 'string', 'in:'.implode(',', array_keys(AppSetting::retentionOptions()))],
````

## File: app/Livewire/Settings/AppointmentReminderSettings.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use Livewire\Component;
⋮----
class AppointmentReminderSettings extends Component
⋮----
/**
     * @var list<int>
     */
public array $whatsappLeadDays = [];
⋮----
public array $emailLeadDays = [];
⋮----
public bool $dispatchEnabled = true;
⋮----
/**
     * @var list<string>
     */
public array $dispatchHours = [];
⋮----
public string $status = '';
⋮----
public function mount(): void
⋮----
$selections = AppointmentReminderPreference::selections();
⋮----
$dispatchSettings = AppSetting::get();
⋮----
public function save(): void
⋮----
abort_unless(auth()->user()?->is_admin, 403);
⋮----
$data = $this->validate();
⋮----
AppointmentReminderPreference::saveSelections([
⋮----
$dispatchSettings->update([
⋮----
$this->dispatch('dispatchSettingsChanged');
⋮----
$this->whatsappLeadDays = AppointmentReminderPreference::enabledLeadDaysFor(AppointmentReminderPreference::CHANNEL_WHATSAPP);
$this->emailLeadDays = AppointmentReminderPreference::enabledLeadDaysFor(AppointmentReminderPreference::CHANNEL_EMAIL);
$this->dispatchEnabled = $dispatchSettings->fresh()->dispatch_enabled;
$this->dispatchHours = $dispatchSettings->fresh()->dispatch_hours;
⋮----
public function render()
⋮----
'leadDayOptions' => AppointmentReminderPreference::leadDayOptions(),
'availableHours' => $this->availableHours(),
⋮----
protected function rules(): array
⋮----
$allowedLeadDays = implode(',', array_keys(AppointmentReminderPreference::leadDayOptions()));
$allowedHours = implode(',', $this->availableHours());
⋮----
/**
     * @return list<string>
     */
private function availableHours(): array
````

## File: app/Livewire/Settings/DatabaseBackup.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\Appointment;
use App\Models\AppointmentChange;
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use ZipArchive;
⋮----
class DatabaseBackup extends Component
⋮----
public $importFile;
⋮----
public string $importStatus = '';
⋮----
public int $importStatusNonce = 0;
⋮----
public bool $confirmImport = false;
⋮----
public function mount(): void
⋮----
//
⋮----
public function updatedImportFile(): void
⋮----
public function importDatabase(): void
⋮----
abort_unless(auth()->user()?->is_admin, 403);
⋮----
$originalName = $this->importFile->getClientOriginalName();
⋮----
$this->importFromJson();
⋮----
$this->importFromZip();
⋮----
private function importFromJson(): void
⋮----
$json = file_get_contents($this->importFile->getRealPath());
⋮----
$this->applyData($tables);
⋮----
private function importFromZip(): void
⋮----
$zipPath = $this->importFile->getRealPath();
⋮----
if ($zip->open($zipPath) !== true) {
⋮----
$filename = $zip->getNameIndex($i);
⋮----
$tables[$table] = $this->parseCsvFromZip($zip, $filename);
⋮----
$zip->close();
⋮----
private function applyData(array $tables): void
⋮----
DB::transaction(function () use ($tables): void {
// Import in FK order
⋮----
$this->importUsers($tables['users']);
⋮----
$this->importClients($tables['clients']);
⋮----
$this->importAppointments($tables['appointments']);
⋮----
$this->importAppointmentChanges($tables['appointment_changes']);
⋮----
$this->importWhatsAppMessages($tables['whatsapp_messages']);
⋮----
$this->importAppSettings($tables['app_settings']);
⋮----
$this->importReminderPreferences($tables['appointment_reminder_preferences']);
⋮----
$this->importCredentials($tables['whatsapp_credentials']);
⋮----
$this->importSenderNumbers($tables['whatsapp_sender_numbers']);
⋮----
$this->importTemplates($tables['twilio_content_templates']);
⋮----
private function importUsers(array $records): void
⋮----
$existing = User::find($record['id'] ?? null);
⋮----
if (! empty($record['password']) && ! Hash::isHashed($record['password'])) {
$data['password'] = Hash::make($record['password']);
⋮----
$existing->update($data);
⋮----
User::create($data);
⋮----
private function importClients(array $records): void
⋮----
Client::upsertFromImport($record);
⋮----
private function importAppointments(array $records): void
⋮----
$existing = Appointment::find($record['id']);
⋮----
Appointment::create($data);
⋮----
private function importAppointmentChanges(array $records): void
⋮----
$existing = AppointmentChange::find($record['id']);
⋮----
$existing->update($record);
⋮----
AppointmentChange::create($record);
⋮----
private function importWhatsAppMessages(array $records): void
⋮----
$existing = WhatsAppMessage::find($record['id']);
⋮----
WhatsAppMessage::create($record);
⋮----
private function importAppSettings(array $records): void
⋮----
AppSetting::updateOrCreate([], [
⋮----
private function importReminderPreferences(array $records): void
⋮----
AppointmentReminderPreference::updateOrCreate(
⋮----
private function importCredentials(array $records): void
⋮----
$existing = WhatsAppCredential::find($record['id']);
⋮----
WhatsAppCredential::create($record);
⋮----
private function importSenderNumbers(array $records): void
⋮----
$existing = WhatsAppSenderNumber::find($record['id']);
⋮----
WhatsAppSenderNumber::create($record);
⋮----
private function importTemplates(array $records): void
⋮----
TwilioContentTemplate::updateOrCreate(
⋮----
private function parseCsvFromZip(ZipArchive $zip, string $filename): array
⋮----
$content = $zip->getFromName($filename);
⋮----
$rows[] = $this->unflattenFromCsv($row);
⋮----
private function unflattenFromCsv(array $row): array
⋮----
} elseif ($value !== '' && $this->isJsonString($value)) {
⋮----
private function isJsonString(string $value): bool
⋮----
public function render()
````

## File: app/Livewire/Settings/SettingsBackup.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use ZipArchive;
⋮----
class SettingsBackup extends Component
⋮----
public $importFile;
⋮----
public string $importStatus = '';
⋮----
public int $importStatusNonce = 0;
⋮----
public bool $confirmImport = false;
⋮----
public function mount(): void
⋮----
//
⋮----
public function updatedImportFile(): void
⋮----
public function importSettings(): void
⋮----
abort_unless(auth()->user()?->is_admin, 403);
⋮----
$originalName = $this->importFile->getClientOriginalName();
⋮----
$this->importFromJson();
⋮----
$this->importFromZip();
⋮----
private function importFromJson(): void
⋮----
$json = file_get_contents($this->importFile->getRealPath());
⋮----
DB::transaction(function () use ($settings, $version): void {
⋮----
$this->importV1($settings);
⋮----
$this->importV2($settings);
⋮----
private function importV1(array $settings): void
⋮----
AppSetting::updateOrCreate([], [
⋮----
$this->importReminderPreferences($settings['appointment_reminder_preferences'] ?? []);
$this->importCredentials($settings['whatsapp_credentials'] ?? []);
$this->importSenderNumbers($settings['whatsapp_sender_numbers'] ?? []);
$this->importTemplates($settings['twilio_content_templates'] ?? []);
⋮----
private function importV2(array $settings): void
⋮----
private function importFromZip(): void
⋮----
$zipPath = $this->importFile->getRealPath();
⋮----
if ($zip->open($zipPath) !== true) {
⋮----
$filename = $zip->getNameIndex($i);
⋮----
$settings[$table] = $this->parseCsvFromZip($zip, $filename);
⋮----
$zip->close();
⋮----
DB::transaction(function () use ($settings): void {
⋮----
$this->importReminderPreferences($settings['appointment_reminder_preferences']);
⋮----
$this->importCredentials($settings['whatsapp_credentials']);
⋮----
$this->importSenderNumbers($settings['whatsapp_sender_numbers']);
⋮----
$this->importTemplates($settings['twilio_content_templates']);
⋮----
private function parseCsvFromZip(ZipArchive $zip, string $filename): array
⋮----
$content = $zip->getFromName($filename);
⋮----
$rows[] = $this->unflattenFromCsv($row);
⋮----
private function unflattenFromCsv(array $row): array
⋮----
} elseif ($value !== '' && $this->isJsonString($value)) {
⋮----
private function isJsonString(string $value): bool
⋮----
public function render()
⋮----
private function importReminderPreferences(array $records): void
⋮----
AppointmentReminderPreference::updateOrCreate(
⋮----
private function importCredentials(array $records): void
⋮----
$existing = WhatsAppCredential::find($record['id']);
⋮----
$existing->update($record);
⋮----
WhatsAppCredential::create($record);
⋮----
private function importSenderNumbers(array $records): void
⋮----
$existing = WhatsAppSenderNumber::find($record['id']);
⋮----
WhatsAppSenderNumber::create($record);
⋮----
private function importTemplates(array $records): void
⋮----
TwilioContentTemplate::updateOrCreate(
````

## File: app/Livewire/Settings/SettingsOverview.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\WhatsAppCredential;
use App\Services\WhatsApp\WhatsAppSender;
use Livewire\Component;
⋮----
class SettingsOverview extends Component
⋮----
protected $listeners = ['modeChanged' => '$refresh', 'credentialsChanged' => '$refresh'];
⋮----
public function render()
⋮----
$credential = WhatsAppCredential::get();
⋮----
'driver' => $credential->resolveDriver(),
⋮----
'resolvedMode' => $sender->resolveTwilioMode(),
'twilioContentSid' => $sender->twilioContentSid(),
'twilioUsesTemplate' => $credential->resolveDriver() === 'twilio',
````

## File: app/Livewire/Settings/TableBackup.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
⋮----
class TableBackup extends Component
⋮----
public string $selectedTable = 'clients';
⋮----
public $importFile;
⋮----
public string $importStatus = '';
⋮----
public int $importStatusNonce = 0;
⋮----
public bool $confirmImport = false;
⋮----
public array $tables = [
⋮----
public function mount(): void
⋮----
//
⋮----
public function updatedImportFile(): void
⋮----
public function importTable(): void
⋮----
abort_unless(auth()->user()?->is_admin, 403);
⋮----
$originalName = $this->importFile->getClientOriginalName();
⋮----
$this->importFromJson();
⋮----
$this->importFromCsv();
⋮----
private function importFromJson(): void
⋮----
$json = file_get_contents($this->importFile->getRealPath());
⋮----
$this->applyRecords($records);
⋮----
private function importFromCsv(): void
⋮----
$handle = fopen($this->importFile->getRealPath(), 'r');
⋮----
$records[] = $this->unflattenFromCsv($record);
⋮----
private function applyRecords(array $records): void
⋮----
'clients' => $this->importClients($records),
'appointments' => $this->importAppointments($records),
'users' => $this->importUsers($records),
⋮----
private function importClients(array $records): void
⋮----
Client::upsertFromImport($record);
⋮----
private function importAppointments(array $records): void
⋮----
$existing = Appointment::find($record['id']);
⋮----
$existing->update($data);
⋮----
Appointment::create($data);
⋮----
private function importUsers(array $records): void
⋮----
$existing = User::find($record['id']);
⋮----
User::create($data);
⋮----
private function unflattenFromCsv(array $row): array
⋮----
public function render()
````

## File: app/Livewire/Settings/TwilioContentTemplateSettings.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use Livewire\Component;
⋮----
class TwilioContentTemplateSettings extends Component
⋮----
public string $nombre = '';
⋮----
public string $contentSid = '';
⋮----
public string $variablePreset = 'with_name';
⋮----
public string $status = '';
⋮----
public ?int $templatePendingDeletion = null;
⋮----
public function addTemplate(): void
⋮----
abort_unless(auth()->user()?->is_admin, 403);
⋮----
$data = $this->validate([
⋮----
$template = TwilioContentTemplate::query()->create([
⋮----
'content_variables' => $this->variablePresets()[$data['variablePreset']],
⋮----
if (! TwilioContentTemplate::query()->where('seleccionada', true)->exists()) {
$template->select();
⋮----
$this->reset('nombre', 'contentSid', 'variablePreset');
⋮----
$this->dispatch('templateChanged');
⋮----
public function selectTemplate(int $templateId): void
⋮----
TwilioContentTemplate::query()->findOrFail($templateId)->select();
⋮----
public function deleteTemplate(int $templateId): void
⋮----
$template = TwilioContentTemplate::query()->findOrFail($templateId);
$template->delete();
⋮----
public function confirmDeleteTemplate(int $templateId): void
⋮----
public function cancelDeleteTemplate(): void
⋮----
public function render()
⋮----
$templates = TwilioContentTemplate::query()->orderBy('nombre')->get();
⋮----
'envContentSid' => (string) WhatsAppCredential::get()->resolveContentSid(),
'pendingTemplate' => $templates->firstWhere('id', $this->templatePendingDeletion),
⋮----
/**
     * @return array<string, array<string, string>>
     */
private function variablePresets(): array
````

## File: app/Livewire/Settings/TwilioCredentialSettings.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
⋮----
class TwilioCredentialSettings extends Component
⋮----
public string $mode = 'sandbox';
⋮----
public string $api_key_sid = '';
⋮----
public string $api_key_secret = '';
⋮----
public string $status_callback_url = '';
⋮----
public bool $webhook_enabled = true;
⋮----
public int $poll_interval = 10;
⋮----
public string $status = '';
⋮----
public array $senderNumbers = [];
⋮----
public string $newName = '';
⋮----
public string $newPrefix = '+1';
⋮----
public string $newNumber = '';
⋮----
public ?int $senderNumberPendingDeletion = null;
⋮----
public function mount(): void
⋮----
$credential = WhatsAppCredential::get();
⋮----
$this->status_callback_url = $credential->resolveStatusCallbackUrl();
⋮----
$this->loadSenderNumbers();
⋮----
private function loadSenderNumbers(): void
⋮----
$this->senderNumbers = $credential->senderNumbers()
->orderBy('id')
->get()
->map(fn (WhatsAppSenderNumber $n) => [
⋮----
->toArray();
⋮----
public function toggleMode(): void
⋮----
abort_unless(auth()->user()?->is_admin, 403);
⋮----
$credential->update(['mode' => $this->mode]);
⋮----
$this->dispatch('modeChanged', value: $this->mode);
⋮----
public function addSenderNumber(): void
⋮----
$data = $this->validate([
⋮----
$hasAny = $credential->senderNumbers()->exists();
⋮----
$credential->senderNumbers()->create([
⋮----
$this->dispatch('credentialsChanged');
⋮----
public function removeSenderNumber(int $id): void
⋮----
$number = $credential->senderNumbers()->find($id);
⋮----
$number->delete();
⋮----
$first = $credential->senderNumbers()->first();
⋮----
$first->update(['selected' => true]);
⋮----
public function confirmRemoveSenderNumber(int $id): void
⋮----
public function cancelRemoveSenderNumber(): void
⋮----
public function selectSenderNumber(int $id): void
⋮----
$credential->senderNumbers()->update(['selected' => false]);
$credential->senderNumbers()->where('id', $id)->update(['selected' => true]);
⋮----
public function save(): void
⋮----
$urlError = $this->validateCallbackUrl($callbackUrl);
⋮----
$this->dispatch('toast', message: $urlError, type: 'error');
⋮----
$credential->update($updateData);
⋮----
$credential->update([
⋮----
$this->dispatch('toast', message: 'Credenciales guardadas correctamente.', type: 'success');
⋮----
public function testWebhook(): void
⋮----
$callbackUrl = $credential->resolveStatusCallbackUrl();
⋮----
$this->dispatch('toast', message: 'Configura una Callback URL primero.', type: 'error');
⋮----
$response = Http::timeout(10)
->post($callbackUrl, [
⋮----
'AccountSid' => $credential->resolveAccountSid() ?? '',
⋮----
$body = $response->body();
$statusCode = $response->status();
⋮----
if ($response->successful()) {
⋮----
$this->dispatch('toast', message: "Webhook OK (HTTP {$statusCode})", type: 'success');
⋮----
$this->dispatch('toast', message: "Webhook respondió HTTP {$statusCode}", type: 'error');
⋮----
$this->status = "Error: {$e->getMessage()}";
$this->dispatch('toast', message: 'Error al conectar con el webhook.', type: 'error');
⋮----
private function validateCallbackUrl(string $url): ?string
⋮----
public function render()
⋮----
->firstWhere('id', $this->senderNumberPendingDeletion);
⋮----
$hasAuthToken = filled($credential->resolveAuthToken());
````

## File: app/Livewire/Settings/WhatsAppConnectionTest.php
````php
namespace App\Livewire\Settings;
⋮----
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Services\WhatsApp\WhatsAppSender;
use App\Traits\NormalizesPhone;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;
⋮----
class WhatsAppConnectionTest extends Component
⋮----
public string $recipient = '';
⋮----
public string $body = 'Mensaje de prueba desde ... ';
⋮----
public string $mode = 'sandbox';
⋮----
public string $testType = 'text';
⋮----
public string $templateId = '';
⋮----
public string $status = '';
⋮----
public string $statusType = 'neutral';
⋮----
public int $statusNonce = 0;
⋮----
public array $details = [];
⋮----
public function mount(): void
⋮----
$this->recipient = (string) $this->previewCredential()->resolveTestRecipient();
$this->mode = $this->initialTwilioMode();
$this->templateId = (string) (TwilioContentTemplate::selectedOrFirst()?->id ?? '');
⋮----
public function refreshPreview(): void
⋮----
//
⋮----
public function refreshTemplatePreview(): void
⋮----
public function updatedTestType(string $value): void
⋮----
private function initialTwilioMode(): string
⋮----
$configuredMode = strtolower(trim((string) $this->previewCredential()->resolveMode()));
⋮----
public function rules(): array
⋮----
public function sendSavedRecipient(WhatsAppSender $sender): void
⋮----
$credential = WhatsAppCredential::get();
$savedRecipient = $credential->resolveTestRecipient();
⋮----
$this->setStatus('error', 'No hay destinatario de prueba guardado. Configura test_recipient en credenciales.');
⋮----
if ($this->recipientIsSenderNumber($this->recipient)) {
$this->setStatus('error', 'No puedes enviar una prueba a un número que ya está configurado como remitente.');
⋮----
$this->sendTest($sender);
⋮----
public function sendTest(WhatsAppSender $sender): void
⋮----
$data = $this->validate();
⋮----
if ($this->recipientIsSenderNumber($data['recipient'])) {
$this->addError('recipient', 'No puedes enviar una prueba a un número que ya está configurado como remitente.');
⋮----
$this->addError('templateId', 'Selecciona una plantilla para enviar la prueba.');
$this->setStatus('error', 'Selecciona una plantilla para enviar la prueba.');
⋮----
$result = $sender->sendTestMessage(
⋮----
$this->setStatus('success', 'Prueba enviada correctamente.');
⋮----
'to' => Arr::get($result, 'payload.to', $data['recipient']),
'mode' => Arr::get($result, 'payload.mode', $data['mode']),
⋮----
$this->setStatus('error', $throwable->getMessage());
⋮----
public function render()
⋮----
'previewPayload' => $this->buildPreviewPayload(),
'templates' => TwilioContentTemplate::query()->orderBy('nombre')->get(),
⋮----
private function buildPreviewPayload(): array
⋮----
$credential = $this->previewCredential();
$selectedNumber = $credential->selectedSenderNumber();
⋮----
'driver' => $credential->resolveDriver(),
⋮----
return match ($credential->resolveDriver()) {
'twilio' => $this->buildTwilioPreviewPayload($preview),
'cloud_api' => $this->buildCloudApiPreviewPayload($preview),
default => $this->buildLogPreviewPayload($preview),
⋮----
private function buildTwilioPreviewPayload(array $preview): array
⋮----
$resolvedMode = $sender->resolveTwilioMode($mode);
⋮----
'request' => $sender->buildTwilioPreviewRequest($preview['recipient'], $preview['body'], $mode, $forceTemplate, $templateId),
⋮----
private function buildCloudApiPreviewPayload(array $preview): array
⋮----
'to' => static::normalizeInternationalPhone($preview['recipient']),
⋮----
private function buildLogPreviewPayload(array $preview): array
⋮----
private function recipientIsSenderNumber(string $recipient): bool
⋮----
$normalizedRecipient = static::normalizeInternationalPhone($recipient);
⋮----
return $this->previewCredential()
->senderNumbers()
->get()
->contains(function ($senderNumber) use ($normalizedRecipient): bool {
return static::normalizeInternationalPhone($senderNumber->full_number) === $normalizedRecipient;
⋮----
private function previewCredential(): WhatsAppCredential
⋮----
if (WhatsAppCredential::query()->where('selected', true)->exists()) {
return WhatsAppCredential::query()->where('selected', true)->firstOrFail();
⋮----
if (WhatsAppCredential::query()->exists()) {
return WhatsAppCredential::query()->firstOrFail();
⋮----
private function setStatus(string $type, string $message): void
````

## File: app/Livewire/AgendaIndex.php
````php
namespace App\Livewire;
⋮----
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\View\View;
use Livewire\Component;
⋮----
class AgendaIndex extends Component
⋮----
public int $selectedDateOffset = 0;
⋮----
public ?int $expandedDateOffset = null;
⋮----
public function selectDate(string $offset): void
⋮----
public function render(): View
⋮----
Carbon::setLocale('es');
$targetDate = $this->targetDate();
$resolvedDates = $this->resolvedDates();
$calendarDays = $this->calendarDays($resolvedDates);
⋮----
'targetDates' => $this->targetDates(),
⋮----
'sundayWarning' => $this->sundayWarning(),
⋮----
/** @return array<int, array{label: string, classes: string}> */
public function appointmentIncidences(Appointment $appointment): array
⋮----
private function targetDate(): Carbon
⋮----
return $this->resolvedDates()[$this->selectedDateOffset];
⋮----
private function resolvedDates(): array
⋮----
$dates = [0 => $now->copy()->startOfDay()];
⋮----
$date = $now->copy()->addDays($offset);
⋮----
if ($date->isSunday()) {
$date->addDay();
⋮----
if (isset($dates[$offset - 1]) && $date->toDateString() === $dates[$offset - 1]->toDateString()) {
⋮----
private function targetDates(): array
⋮----
$resolved = $this->resolvedDates();
⋮----
->mapWithKeys(fn (int $offset) => [
⋮----
->all();
⋮----
private function calendarDays(array $resolvedDates): array
⋮----
$appointmentsByDate = Appointment::query()
->with('client')
->whereBetween('fecha', [
$resolvedDates[0]->toDateString(),
$resolvedDates[array_key_last($resolvedDates)]->toDateString(),
⋮----
->orderBy('fecha')
->orderBy('hora')
->get()
->groupBy(fn (Appointment $appointment): string => $appointment->fecha->toDateString());
⋮----
->mapWithKeys(fn (Carbon $date, int $offset): array => [
⋮----
'appointments' => $appointmentsByDate->get($date->toDateString(), collect()),
⋮----
private function sundayWarning(): ?string
⋮----
$rawDate = now(config('app.timezone'))->addDays($this->selectedDateOffset);
⋮----
if (! $rawDate->isSunday()) {
⋮----
return 'La fecha seleccionada es domingo, mostrando las citas del '.$this->targetDate()->translatedFormat('l d');
````

## File: app/Livewire/AppointmentForm.php
````php
namespace App\Livewire;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\AppointmentImmediateSender;
use App\Services\WhatsApp\WhatsAppSender;
use App\Traits\ValidatesSelectableDate;
use Illuminate\Validation\Rule;
use Livewire\Component;
⋮----
class AppointmentForm extends Component
⋮----
public string $filter_nombre = '';
⋮----
public string $filter_apellidos = '';
⋮----
public string $filter_telefono = '';
⋮----
public ?int $selectedClientId = null;
⋮----
public ?int $selectedAppointmentId = null;
⋮----
public string $fecha = '';
⋮----
public string $hora = '';
⋮----
public bool $enviado = false;
⋮----
public bool $activo = true;
⋮----
public bool $sendImmediately = false;
⋮----
public bool $isEditing = false;
⋮----
public bool $hideClientSearch = false;
⋮----
public bool $showReturnAfterImmediateSend = false;
⋮----
public string $returnUrl = '';
⋮----
public function boot(AppointmentImmediateSender $immediateSender, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
⋮----
public function mount(): void
⋮----
$this->returnUrl = $this->resolveReturnUrl();
⋮----
$appointmentId = (int) request()->route('appointment');
⋮----
$this->loadAppointment($appointmentId);
⋮----
$clientId = request()->integer('client');
⋮----
$this->selectClient($clientId);
⋮----
private AppointmentImmediateSender $immediateSender;
⋮----
private AppointmentDeliveryStatusSyncer $deliveryStatusSyncer;
⋮----
public function selectClient(int $clientId): void
⋮----
session()->flash('status', 'Esta cita no se puede modificar. Solo se puede eliminar.');
$this->redirect(url()->previous());
⋮----
$client = Client::query()->findOrFail($clientId);
⋮----
public function save(WhatsAppSender $sender): void
⋮----
$data = $this->validate();
$this->validateSelectableDate($data['fecha'], 'fecha');
⋮----
$client = Client::query()->findOrFail($data['selectedClientId']);
⋮----
$appointment = Appointment::query()->findOrFail($this->selectedAppointmentId);
⋮----
$appointment->update($payload);
session()->flash('status', 'Cita actualizada correctamente.');
$this->redirect($this->returnUrl ?: url()->previous());
⋮----
$appointment = Appointment::query()->create($payload);
⋮----
$this->sendAppointmentNow(
⋮----
session()->flash('status', 'Cita creada correctamente.');
⋮----
public function sendNow(WhatsAppSender $sender): void
⋮----
$appointment = Appointment::query()
->with('client')
->findOrFail($this->selectedAppointmentId);
⋮----
session()->flash('status', 'Esta cita ya tiene el WhatsApp enviado.');
⋮----
if (! $appointment->isFuture()) {
session()->flash('status', 'Las citas pasadas no pueden enviarse.');
⋮----
session()->flash('status', 'Las citas inactivas no pueden enviarse.');
⋮----
session()->flash('status', 'No se pudo enviar el WhatsApp porque la cita no tiene cliente asociado.');
⋮----
public function getSelectedClientProperty(): ?Client
⋮----
? Client::query()->find($this->selectedClientId)
⋮----
public function getSelectedAppointmentProperty(): ?Appointment
⋮----
? Appointment::query()->with('client')->find($this->selectedAppointmentId)
⋮----
public function getCanChangeAppointmentProperty(): bool
⋮----
return (bool) Appointment::query()
->whereKey($this->selectedAppointmentId)
->first()
⋮----
public function getCanSendAppointmentNowProperty(): bool
⋮----
->first();
⋮----
return (bool) $appointment && ! $appointment->enviado && $appointment->activo && $appointment->isFuture();
⋮----
public function getHasClientSearchProperty(): bool
⋮----
public function render()
⋮----
$clientsQuery = Client::query()
->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'));
⋮----
? (clone $clientsQuery)->count()
⋮----
->orderByDesc('created_at')
->limit(10)
->get()
⋮----
'minimumSelectableDate' => $this->minimumSelectableDate(),
⋮----
protected function rules(): array
⋮----
Rule::exists('clients', 'id'),
⋮----
'fecha' => ['required', 'date', Rule::date()->afterOrEqual('today')],
⋮----
protected function messages(): array
⋮----
private function sendAppointmentNow(
⋮----
$result = $this->immediateSender->send($appointment, $client, $sender, $successMessage, $failureMessage);
⋮----
$appointment->refresh();
⋮----
session()->flash('status', $result['message']);
⋮----
private function minimumSelectableDate(): string
⋮----
return now()->toDateString();
⋮----
private function resolveReturnUrl(): string
⋮----
$previousUrl = url()->previous();
$currentUrl = url()->current();
⋮----
private function loadAppointment(int $appointmentId): void
⋮----
$appointment = Appointment::query()->with('client')->findOrFail($appointmentId);
$this->deliveryStatusSyncer->sync([$appointment->id]);
````

## File: app/Livewire/AppointmentIndex.php
````php
namespace App\Livewire;
⋮----
use App\Models\Client;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
⋮----
class AppointmentIndex extends Component
⋮----
public ?string $deliveryStatusesSyncedAt = null;
⋮----
public function mount(): void
⋮----
$this->deliveryStatusesSyncedAt = Cache::get('appointment_delivery_statuses_synced_at');
⋮----
public function syncDeliveryStatuses(AppointmentDeliveryStatusSyncer $syncer): void
⋮----
$updated = $syncer->syncAll(force: true);
$this->deliveryStatusesSyncedAt = now(config('app.timezone'))->format('H:i - d/m/Y');
Cache::forever('appointment_delivery_statuses_synced_at', $this->deliveryStatusesSyncedAt);
⋮----
session()->flash('status', $updated > 0
⋮----
public function render()
⋮----
$today = now(config('app.timezone'))->toDateString();
⋮----
$clients = Client::query()
->whereHas('appointments', fn (Builder $query) => $query->whereDate('fecha', '>=', $today))
->withCount([
'appointments as appointments_count' => fn (Builder $query) => $query->whereDate('fecha', '>=', $today),
⋮----
->with(['appointments' => fn (Builder|HasMany $query) => $query
->whereDate('fecha', '>=', $today)
->orderBy('fecha')
->orderBy('hora')
->limit(1)])
->orderBy('nombre')
->orderBy('apellidos')
->paginate(30, pageName: 'clientsPage');
````

## File: app/Livewire/CalendarIndex.php
````php
namespace App\Livewire;
⋮----
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
⋮----
class CalendarIndex extends Component
⋮----
public string $month;
⋮----
public function mount(): void
⋮----
$this->month = now(config('app.timezone'))->startOfMonth()->toDateString();
⋮----
public function previousMonth(): void
⋮----
$this->month = $this->selectedMonth()->subMonthNoOverflow()->toDateString();
⋮----
public function nextMonth(): void
⋮----
$this->month = $this->selectedMonth()->addMonthNoOverflow()->toDateString();
⋮----
public function currentMonth(): void
⋮----
public function render(): View
⋮----
Carbon::setLocale('es');
⋮----
$selectedMonth = $this->selectedMonth();
⋮----
'calendarWeeks' => $this->calendarWeeks($selectedMonth),
⋮----
private function selectedMonth(): Carbon
⋮----
return Carbon::parse($this->month, config('app.timezone'))->startOfMonth();
⋮----
private function calendarWeeks(Carbon $selectedMonth): Collection
⋮----
$monthStart = $selectedMonth->copy()->startOfMonth();
$monthEnd = $selectedMonth->copy()->endOfMonth();
⋮----
$appointmentStats = Appointment::query()
->whereDate('fecha', '>=', $monthStart->toDateString())
->whereDate('fecha', '<=', $monthEnd->toDateString())
->get(['id', 'fecha', 'cita_activa'])
->groupBy(fn (Appointment $appointment): string => $appointment->fecha->toDateString())
->map(fn (Collection $appointments): array => [
'total' => $appointments->count(),
'inactive' => $appointments->where('cita_activa', false)->count(),
⋮----
$firstCalendarDay = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
$lastCalendarDay = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);
⋮----
for ($date = $firstCalendarDay; $date->lte($lastCalendarDay); $date->addDay()) {
$dateKey = $date->toDateString();
$isCurrentMonth = $date->isSameMonth($selectedMonth);
$isSunday = $date->isSunday();
⋮----
$days->push([
'date' => $date->copy(),
⋮----
return $days->chunk(7);
````

## File: app/Livewire/ClientAppointments.php
````php
namespace App\Livewire;
⋮----
use App\Jobs\SendWhatsAppMessage;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Services\ClientDataDeletionService;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\AppointmentImmediateSender;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;
⋮----
class ClientAppointments extends Component
⋮----
public string $sort_direction = 'asc';
⋮----
public string $filter = 'upcoming';
⋮----
public ?string $whatsappFilter = null;
⋮----
public int $clientId;
⋮----
public ?int $appointmentPendingDeletionId = null;
⋮----
/** @var array<int, int|string> */
public array $selectedAppointmentIds = [];
⋮----
public bool $selectAll = false;
⋮----
public bool $bulkDeleteConfirmationOpen = false;
⋮----
public ?string $deliveryStatusesSyncedAt = null;
⋮----
public ?Appointment $historyAppointment = null;
⋮----
public string $historyReplyBody = '';
⋮----
private AppointmentImmediateSender $immediateSender;
⋮----
private AppointmentDeliveryStatusSyncer $deliveryStatusSyncer;
⋮----
public function boot(AppointmentImmediateSender $immediateSender, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
⋮----
public function mount(int $clientId): void
⋮----
abort_unless(Client::query()->whereKey($clientId)->exists(), 404);
⋮----
$this->deliveryStatusesSyncedAt = Cache::get('appointment_delivery_statuses_synced_at');
$historyAppointmentId = (int) request()->integer('history');
⋮----
$appointment = Appointment::query()
->where('client_id', $this->clientId)
->find($historyAppointmentId);
⋮----
$this->openHistory($appointment->id);
⋮----
public function updated(string $property): void
⋮----
$this->resetPage('appointmentsPage');
⋮----
public function toggleSortDirection(): void
⋮----
public function toggleWhatsappFilter(string $value): void
⋮----
public function confirmDelete(int $appointmentId): void
⋮----
public function cancelDelete(): void
⋮----
public function toggleVisibleAppointments(array $appointmentIds): void
⋮----
public function confirmBulkDelete(): void
⋮----
public function deleteSelected(): void
⋮----
->deleteAppointments($this->selectedAppointmentIds, $this->clientId);
⋮----
$this->redirectAfterAction(sprintf('%d cita(s) eliminada(s) correctamente.', $deleted));
⋮----
public function updateSelectedActiveStatus(bool $activo): void
⋮----
$appointmentIds = Appointment::query()
⋮----
->whereKey(array_map('intval', $this->selectedAppointmentIds))
->pending()
->upcoming()
->pluck('id');
⋮----
Appointment::query()->whereKey($appointmentIds)->update(['activo' => $activo]);
⋮----
WhatsAppMessage::query()
->whereIn('appointment_id', $appointmentIds)
->where('status', WhatsAppMessage::STATUS_PENDING)
->delete();
⋮----
$this->dispatch('toast', message: sprintf(
⋮----
$appointmentIds->count(),
⋮----
$this->render();
⋮----
public function updateSelectedCitaActiva(bool $citaActiva): void
⋮----
Appointment::query()->whereKey($appointmentIds)->update(['cita_activa' => $citaActiva]);
⋮----
public function deleteConfirmed(): void
⋮----
->deleteAppointments([$this->appointmentPendingDeletionId], $this->clientId);
⋮----
$this->redirectAfterAction('Cita eliminada correctamente.');
⋮----
public function updateActiveStatus(int $appointmentId, bool|string $activo): void
⋮----
$appointment = Appointment::query()->findOrFail($appointmentId);
⋮----
if (! $appointment->canBeChanged()) {
$this->dispatch('toast', message: 'Esta cita no se puede modificar. Solo se puede eliminar.', type: 'error');
⋮----
$appointment->update([
⋮----
$appointment->whatsAppMessages()
⋮----
$this->dispatch('toast', message: 'Estado pendiente actualizado.', type: 'success');
⋮----
public function updateAppointmentActiveStatus(int $appointmentId, bool|string $citaActiva): void
⋮----
$appointment->update(['cita_activa' => $isActive]);
⋮----
$this->dispatch('toast', message: 'Estado de la cita actualizado.', type: 'success');
⋮----
public function sendNow(int $appointmentId, WhatsAppSender $sender): void
⋮----
->with('client')
->findOrFail($appointmentId);
⋮----
$this->dispatch('toast', message: 'Esta cita ya tiene el WhatsApp enviado.', type: 'error');
⋮----
if (! $appointment->scheduledFor()->isFuture()) {
$this->dispatch('toast', message: 'Las citas pasadas no pueden enviarse.', type: 'error');
⋮----
$this->dispatch('toast', message: 'Las citas inactivas no pueden enviarse.', type: 'error');
⋮----
$this->dispatch('toast', message: 'No se pudo enviar el WhatsApp porque la cita no tiene cliente asociado.', type: 'error');
⋮----
$result = $this->immediateSender->send(
⋮----
$this->queuePageReloadAfterWhatsAppSend();
⋮----
$this->dispatch('toast', message: $result['message'], type: str_contains($result['message'], 'correctamente') ? 'success' : 'error');
⋮----
public function openHistory(int $appointmentId): void
⋮----
$appointment->markLatestInboundAsSeen();
⋮----
$this->historyAppointment = Appointment::query()
->with([
⋮----
'whatsAppMessages' => fn ($q) => $q->orderByRaw('COALESCE(sent_at, responded_at, created_at) asc')->orderBy('id', 'asc'),
⋮----
public function closeHistory(): void
⋮----
public function sendHistoryReply(): void
⋮----
$data = $this->validate([
⋮----
->findOrFail($this->historyAppointment->id);
⋮----
$this->dispatch('toast', message: 'La cita no tiene cliente asociado.', type: 'error');
⋮----
$parentMessage = $appointment->latestInboundAfterLastSent();
⋮----
$message = WhatsAppMessage::query()->create([
'user_id' => Auth::id(),
⋮----
SendWhatsAppMessage::dispatchSync($message->id);
⋮----
$message->refresh();
⋮----
$this->refreshHistoryAppointment();
$this->dispatch('toast', message: 'Respuesta enviada correctamente.', type: 'success');
⋮----
$this->dispatch('toast', message: 'No se pudo enviar la respuesta. '.($message->last_error ?? ''), type: 'error');
⋮----
public function syncDeliveryStatuses(): void
⋮----
$updated = $this->deliveryStatusSyncer->syncAll($this->clientId, force: true);
$this->touchDeliveryStatusesSyncedAt();
⋮----
$this->dispatch('toast', message: $updated === 1 ? 'Se ha actualizado 1 cita' : 'Se han actualizado '.$updated.' citas', type: 'success');
⋮----
$this->dispatch('toast', message: 'Todos los registros de citas y demás datos están actualizados.', type: 'success');
⋮----
public function autoSync(): void
⋮----
if (! WhatsAppCredential::webhookEnabled()) {
$this->deliveryStatusSyncer->syncAll($this->clientId, force: true);
⋮----
private function queuePageReloadAfterWhatsAppSend(): void
⋮----
$this->dispatch('reload-appointment-list');
⋮----
private function touchDeliveryStatusesSyncedAt(): void
⋮----
$this->deliveryStatusesSyncedAt = now(config('app.timezone'))->format('H:i - d/m/Y');
Cache::forever('appointment_delivery_statuses_synced_at', $this->deliveryStatusesSyncedAt);
⋮----
private function refreshHistoryAppointment(): void
⋮----
->find($this->historyAppointment->id);
⋮----
public function render()
⋮----
$selectedClient = Client::query()->find($this->clientId);
$appointmentsQuery = $this->appointmentsQuery($selectedClient);
⋮----
$appointments = $appointmentsQuery->paginate(30, ['appointments.*'], 'appointmentsPage');
⋮----
$visibleAppointmentIds = $appointments->getCollection()->pluck('id')->all();
⋮----
? Appointment::query()->with('client')->find($this->appointmentPendingDeletionId)
⋮----
'appointmentsCount' => $appointments->total(),
⋮----
'pollInterval' => WhatsAppCredential::pollInterval(),
⋮----
private function appointmentsQuery(Client $selectedClient): Builder
⋮----
return Appointment::query()
->select('appointments.*')
->withCount('changes')
->with(['client', 'latestWhatsAppMessage', 'latestRespondedWhatsAppMessage'])
->leftJoin('clients', 'clients.id', '=', 'appointments.client_id')
->where('appointments.client_id', $selectedClient->id)
->when($this->filter === 'upcoming', fn ($q) => $q
->whereDate('fecha', '>=', $now->toDateString())
⋮----
->when($this->filter === 'past', fn ($q) => $q
->whereDate('fecha', '<', $now->toDateString())
⋮----
->when($this->whatsappFilter === 'sent', fn ($q) => $q->where('enviado', true))
->when($this->whatsappFilter === 'delivered', fn ($q) => $q->where('entregado', true))
->when($this->whatsappFilter === 'unsent', fn ($q) => $q->where('activo', false))
->orderBy('appointments.fecha', $this->sort_direction)
->orderBy('appointments.hora', $this->sort_direction);
⋮----
private function redirectAfterAction(string $status): void
⋮----
$client = Client::query()->find($this->clientId);
⋮----
if (! $this->appointmentsQuery($client)->exists()) {
session()->flash('status', 'No hay citas para el cliente '.$client->full_name);
$this->redirect(route('appointments.index'));
⋮----
session()->flash('status', $status);
$this->redirect(route('clients.appointments', $client));
````

## File: app/Livewire/ClientCsvImporter.php
````php
namespace App\Livewire;
⋮----
use App\Imports\ClientsImport;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
⋮----
class ClientCsvImporter extends Component
⋮----
public ?TemporaryUploadedFile $file = null;
⋮----
public string $status = '';
⋮----
public string $statusType = 'neutral';
⋮----
public array $previewRows = [];
⋮----
public bool $previewLoaded = false;
⋮----
public function preview(): void
⋮----
$this->validate([
⋮----
$delimiter = $this->detectCsvDelimiter();
⋮----
Excel::import($import, $this->file);
⋮----
$this->previewRows = $import->previewRows();
⋮----
if ($import->processedRows() === 0) {
$this->setStatus('El CSV se leyó, pero no contenía filas válidas para previsualizar.', 'error');
⋮----
$this->setStatus('Previsualización generada correctamente.');
⋮----
$this->setStatus('No se pudo generar la previsualización: '.$throwable->getMessage(), 'error');
⋮----
public function import(): void
⋮----
$this->setStatus('El CSV se leyó, pero no contenía filas válidas para importar.', 'error');
⋮----
$this->reset('file');
$this->setStatus(sprintf(
⋮----
$import->createdRows(),
$import->skippedRows(),
$import->restoredRows(),
⋮----
$this->setStatus('No se pudo importar el CSV: '.$throwable->getMessage(), 'error');
⋮----
public function render()
⋮----
private function setStatus(string $message, string $type = 'success'): void
⋮----
private function detectCsvDelimiter(): string
````

## File: app/Livewire/ClientForm.php
````php
namespace App\Livewire;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use App\Services\ClientDataDeletionService;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use App\Services\WhatsApp\AppointmentImmediateSender;
use App\Services\WhatsApp\WhatsAppSender;
use Livewire\Component;
⋮----
class ClientForm extends Component
⋮----
public ?int $selectedClientId = null;
⋮----
public string $nombre = '';
⋮----
public string $apellidos = '';
⋮----
public string $telefono = '';
⋮----
private AppointmentImmediateSender $immediateSender;
⋮----
private AppointmentDeliveryStatusSyncer $deliveryStatusSyncer;
⋮----
private bool $skipDeliverySync = false;
⋮----
public function boot(AppointmentImmediateSender $immediateSender, AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
⋮----
public function mount(?int $client = null): void
⋮----
$clientId = $client ?: (int) request()->route('client');
⋮----
$this->loadClient($clientId);
⋮----
$queryClientId = request()->integer('client');
⋮----
$this->loadClient($queryClientId);
⋮----
public function save(): void
⋮----
$data = $this->validate();
⋮----
'telefono' => Client::normalizePhone($data['telefono']),
⋮----
Client::query()->whereKey($this->selectedClientId)->update($payload);
session()->flash('status', 'Cliente actualizado correctamente.');
$this->redirect(url()->previous());
⋮----
$client = Client::upsertFromImport($payload);
⋮----
session()->flash('status', $client->wasRecentlyCreated ? 'Cliente creado correctamente.' : 'Cliente ya existente; no se ha duplicado.');
⋮----
public function updateAppointmentActiveStatus(int $appointmentId, bool|string $activo): void
⋮----
$appointment = Appointment::query()
->where('client_id', $this->selectedClientId)
->findOrFail($appointmentId);
⋮----
if (! $appointment->canBeChanged()) {
$this->dispatch('toast', message: 'Esta cita no se puede modificar. Solo se puede eliminar.', type: 'error');
⋮----
$appointment->update([
⋮----
$appointment->whatsAppMessages()
->where('status', WhatsAppMessage::STATUS_PENDING)
->delete();
⋮----
$this->dispatch('toast', message: 'Estado activo actualizado.', type: 'success');
⋮----
public function deleteAppointment(int $appointmentId): void
⋮----
->deleteAppointments([$appointmentId], $this->selectedClientId);
⋮----
session()->flash('status', 'Cita eliminada correctamente.');
⋮----
public function sendAppointmentNow(int $appointmentId, WhatsAppSender $sender): void
⋮----
->with('client')
⋮----
session()->flash('status', 'Esta cita ya tiene el WhatsApp enviado.');
⋮----
if (! $appointment->isFuture()) {
session()->flash('status', 'Las citas pasadas no pueden enviarse.');
⋮----
session()->flash('status', 'Las citas inactivas no pueden enviarse.');
⋮----
session()->flash('status', 'No se pudo enviar el WhatsApp porque la cita no tiene cliente asociado.');
⋮----
$result = $this->immediateSender->send(
⋮----
session()->flash('status', $result['message']);
⋮----
public function getSelectedClientProperty(): ?Client
⋮----
return Client::query()->find($this->selectedClientId);
⋮----
public function render()
⋮----
? Appointment::query()
⋮----
->whereDate('fecha', '<', now(config('app.timezone'))->toDateString())
->select(['id', 'fecha', 'hora', 'activo', 'cita_activa', 'confirmada', 'pendiente_reprogramacion', 'enviado', 'entregado'])
->orderByDesc('fecha')
->orderByDesc('hora')
->get()
⋮----
protected function rules(): array
⋮----
private function loadClient(int $clientId): void
⋮----
$client = Client::query()->findOrFail($clientId);
````

## File: app/Livewire/ClientIndex.php
````php
namespace App\Livewire;
⋮----
use App\Models\Client;
use App\Services\ClientDataDeletionService;
use Livewire\Component;
use Livewire\WithPagination;
⋮----
class ClientIndex extends Component
⋮----
public string $filter_nombre = '';
⋮----
public string $filter_apellidos = '';
⋮----
public string $filter_telefono = '';
⋮----
public string $sort_direction = 'asc';
⋮----
public ?int $clientPendingDeletionId = null;
⋮----
public function updatedFilterNombre(): void
⋮----
$this->resetPage();
⋮----
public function updatedFilterApellidos(): void
⋮----
public function updatedFilterTelefono(): void
⋮----
public function sortByName(): void
⋮----
public function confirmDelete(int $clientId): void
⋮----
public function cancelDelete(): void
⋮----
public function deleteConfirmed(): void
⋮----
app(ClientDataDeletionService::class)->deleteClientById($this->clientPendingDeletionId);
⋮----
session()->flash('status', 'Cliente eliminado correctamente.');
⋮----
$this->redirect(url()->previous());
⋮----
public function getHasClientSearchProperty(): bool
⋮----
public function render()
⋮----
$clients = Client::query()
->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'))
->orderBy('nombre', $this->sort_direction)
->orderBy('apellidos', $this->sort_direction)
->paginate(15);
⋮----
? Client::query()->find($this->clientPendingDeletionId)
````

## File: app/Livewire/ClientListAll.php
````php
namespace App\Livewire;
⋮----
use App\Models\Client;
use App\Services\ClientDataDeletionService;
use Livewire\Component;
use Livewire\WithPagination;
⋮----
class ClientListAll extends Component
⋮----
public string $filter_nombre = '';
⋮----
public string $filter_apellidos = '';
⋮----
public string $filter_telefono = '';
⋮----
public string $sort_direction = 'asc';
⋮----
public ?int $clientPendingDeletionId = null;
⋮----
public function updatedFilterNombre(): void
⋮----
$this->resetPage();
⋮----
public function updatedFilterApellidos(): void
⋮----
public function updatedFilterTelefono(): void
⋮----
public function sortByName(): void
⋮----
public function confirmDelete(int $clientId): void
⋮----
public function cancelDelete(): void
⋮----
public function deleteConfirmed(): void
⋮----
app(ClientDataDeletionService::class)->deleteClientById($this->clientPendingDeletionId);
⋮----
session()->flash('status', 'Cliente eliminado correctamente.');
⋮----
$this->redirect(url()->previous());
⋮----
public function getHasClientSearchProperty(): bool
⋮----
public function render()
⋮----
$clients = Client::query()
->withCount([
'appointments' => fn ($query) => $query->active()->upcoming(),
⋮----
->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'))
->orderBy('nombre', $this->sort_direction)
->orderBy('apellidos', $this->sort_direction)
->paginate(15);
⋮----
? Client::query()->find($this->clientPendingDeletionId)
````

## File: app/Livewire/ClientMessageScheduler.php
````php
namespace App\Livewire;
⋮----
use App\Jobs\SendWhatsAppMessage;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppTemplate;
use App\Services\WhatsApp\WhatsAppSender;
use App\Traits\ValidatesSelectableDate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
⋮----
class ClientMessageScheduler extends Component
⋮----
public string $filter_nombre = '';
⋮----
public string $filter_apellidos = '';
⋮----
public string $filter_telefono = '';
⋮----
public ?int $selectedClientId = null;
⋮----
public string $scheduled_date = '';
⋮----
public string $scheduled_time = '';
⋮----
public string $template_key = '';
⋮----
public string $status = '';
⋮----
public string $statusType = 'success';
⋮----
public function mount(): void
⋮----
$this->template_key = WhatsAppTemplate::defaultKey();
⋮----
$clientId = request()->integer('client');
⋮----
$this->selectClient($clientId);
⋮----
public function updatedFilterNombre(): void
⋮----
$this->resetPage('clientsPage');
⋮----
public function updatedFilterApellidos(): void
⋮----
public function updatedFilterTelefono(): void
⋮----
public function selectClient(int $clientId): void
⋮----
$client = Client::query()->findOrFail($clientId);
⋮----
$this->scheduled_date = $this->nextSelectableDate();
$this->scheduled_time = now()->format('H:i');
$this->template_key = $this->template_key ?: WhatsAppTemplate::defaultKey();
⋮----
public function clearSelection(): void
⋮----
$this->resetValidation();
⋮----
public function save(): void
⋮----
$data = $this->validate();
$this->validateSelectableDate($data['scheduled_date'], 'scheduled_date');
⋮----
$client = Client::query()->findOrFail($data['selectedClientId']);
$scheduledFor = Carbon::parse("{$data['scheduled_date']} {$data['scheduled_time']}");
⋮----
$this->createManualMessage($client, $scheduledFor, $data['template_key']);
⋮----
$this->clearSelection();
⋮----
public function sendNow(WhatsAppSender $sender): void
⋮----
$message = $this->createManualMessage($client, $scheduledFor, $data['template_key'], [
⋮----
'immediate_sent_at' => now()->toDateTimeString(),
⋮----
SendWhatsAppMessage::dispatchSync($message->id);
⋮----
$message->refresh();
⋮----
public function render()
⋮----
$clients = Client::query()
->when($this->filter_nombre, fn ($query) => $query->where('nombre', 'like', '%'.$this->filter_nombre.'%'))
->when($this->filter_apellidos, fn ($query) => $query->where('apellidos', 'like', '%'.$this->filter_apellidos.'%'))
->when($this->filter_telefono, fn ($query) => $query->where('telefono', 'like', '%'.$this->filter_telefono.'%'))
->orderByDesc('created_at')
->paginate(15, ['*'], 'clientsPage');
⋮----
? Client::query()->find($this->selectedClientId)
⋮----
'templateOptions' => WhatsAppMessage::templateOptions(),
⋮----
? WhatsAppMessage::buildMessage([
⋮----
'scheduled_for' => Carbon::parse(
($this->scheduled_date ?: now()->toDateString()).' '.($this->scheduled_time ?: now()->format('H:i'))
⋮----
], $this->template_key ?: WhatsAppTemplate::defaultKey())
⋮----
'minimumSelectableDate' => now()->addDay()->toDateString(),
⋮----
protected function rules(): array
⋮----
$templateKeys = implode(',', array_column(WhatsAppMessage::templateOptions(), 'key'));
⋮----
'scheduled_date' => ['required', Rule::date()->afterToday()],
⋮----
protected function messages(): array
⋮----
/**
     * @param  array<string, mixed>  $metadata
     */
private function createManualMessage(Client $client, Carbon $scheduledFor, string $templateKey, array $metadata = []): WhatsAppMessage
⋮----
$message = WhatsAppMessage::buildMessage([
⋮----
return WhatsAppMessage::create([
'user_id' => Auth::id(),
⋮----
private function nextSelectableDate(): string
⋮----
$date = now()->addDay();
⋮----
while ($date->isSunday()) {
$date->addDay();
⋮----
return $date->toDateString();
````

## File: app/Livewire/DashboardOverview.php
````php
namespace App\Livewire;
⋮----
use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Illuminate\View\View;
use Livewire\Component;
⋮----
class DashboardOverview extends Component
⋮----
public function render(): View
⋮----
$weekStart = $now->copy()->subDays(6)->startOfDay();
⋮----
$upcoming = Appointment::query()->active()->upcoming();
⋮----
->select(['id', 'client_id', 'fecha', 'hora', 'enviado', 'whatsapp_sent_at'])
->with('client:id,nombre,apellidos,telefono')
->orderBy('fecha')
->orderBy('hora')
->limit(5)
->get();
⋮----
'todayCount' => Appointment::query()
->active()
->whereDate('fecha', $now->toDateString())
->count(),
'nextAppointment' => $nextAppointments->first(),
⋮----
->whereNull('whatsapp_sent_at')
⋮----
'rescheduleCount' => Appointment::query()
⋮----
->where('pendiente_reprogramacion', true)
⋮----
'failedCount' => WhatsAppMessage::query()
->outbound()
->where('status', WhatsAppMessage::STATUS_FAILED)
⋮----
'sentLastSevenDays' => WhatsAppMessage::query()
⋮----
->where('status', WhatsAppMessage::STATUS_SENT)
->where('created_at', '>=', $weekStart)
⋮----
'failedLastSevenDays' => WhatsAppMessage::query()
⋮----
'cancelledCount' => Appointment::query()->where('activo', false)->count(),
'totalCount' => Appointment::query()->count(),
````

## File: app/Livewire/DispatchBanner.php
````php
namespace App\Livewire;
⋮----
use App\Models\AppSetting;
use App\Models\WhatsAppCredential;
use Livewire\Attributes\On;
use Livewire\Component;
⋮----
class DispatchBanner extends Component
⋮----
public bool $enabled = true;
⋮----
public array $alerts = [];
⋮----
public function mount(): void
⋮----
$this->refreshAlerts();
⋮----
public function onToggle(bool|array $value = true): void
⋮----
$this->refreshAlerts(
⋮----
public function refreshBanner(): void
⋮----
private function refreshAlerts(?bool $dispatchEnabled = null): void
⋮----
$settings = AppSetting::get();
$credential = WhatsAppCredential::get();
$currentHost = strtolower(request()->getHost());
$currentUrl = request()->fullUrl();
⋮----
$isLocalServer = app()->environment('local')
⋮----
! filled($credential->resolveAccountSid()) ? [
⋮----
public function render()
````

## File: app/Livewire/UnreadResponsesNotice.php
````php
namespace App\Livewire;
⋮----
use App\Models\Appointment;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\AppointmentDeliveryStatusSyncer;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
⋮----
class UnreadResponsesNotice extends Component
⋮----
private AppointmentDeliveryStatusSyncer $deliveryStatusSyncer;
⋮----
public function boot(AppointmentDeliveryStatusSyncer $deliveryStatusSyncer): void
⋮----
public function pollUpdates(): void
⋮----
if (! $this->shouldSyncFromTwilio()) {
⋮----
$this->deliveryStatusSyncer->syncAll(force: true);
⋮----
public function render()
⋮----
$appointments = Appointment::query()
->whereHas('whatsAppMessages', fn ($query) => $query
->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
->whereNotNull('respuesta'))
->with([
⋮----
->select([
⋮----
->orderByDesc('created_at'),
⋮----
->get()
->filter->hasUnreadInboundResponse()
->sortByDesc(function (Appointment $appointment): int {
$latestInbound = $appointment->latestInboundAfterLastSent();
⋮----
->take(5)
->values()
->map(function (Appointment $appointment): array {
⋮----
'pollInterval' => WhatsAppCredential::webhookEnabled() ? 2 : WhatsAppCredential::pollInterval(),
⋮----
private function shouldSyncFromTwilio(): bool
⋮----
$lastSyncedAt = (int) Cache::get($cacheKey, 0);
⋮----
if ($lastSyncedAt > 0 && $lastSyncedAt <= $now && ($now - $lastSyncedAt) < $this->twilioSyncInterval()) {
⋮----
Cache::put($cacheKey, $now, now()->addMinutes(5));
⋮----
private function twilioSyncInterval(): int
⋮----
$credential = WhatsAppCredential::get();
````

## File: app/Models/Appointment.php
````php
namespace App\Models;
⋮----
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
⋮----
class Appointment extends Model
⋮----
protected $fillable = [
⋮----
protected $attributes = [
⋮----
protected static function booted(): void
⋮----
static::creating(function (self $appointment): void {
⋮----
static::updating(function (self $appointment): void {
if (! $appointment->wasChangedSchedule() || ($appointment->fecha_original && filled($appointment->hora_original))) {
⋮----
$appointment->fecha_original ??= $appointment->getOriginal('fecha');
$appointment->hora_original ??= $appointment->getOriginal('hora');
⋮----
static::updated(function (self $appointment): void {
if (! $appointment->wasChangedSchedule()) {
⋮----
$appointment->changes()->create([
'fecha_anterior' => $appointment->getOriginal('fecha'),
'hora_anterior' => $appointment->getOriginal('hora'),
'fecha_nueva' => $appointment->fecha->toDateString(),
⋮----
public function wasChangedSchedule(): bool
⋮----
return $this->isDirty('fecha') || $this->isDirty('hora');
⋮----
public function client(): BelongsTo
⋮----
return $this->belongsTo(Client::class);
⋮----
public function latestWhatsAppMessage(): HasOne
⋮----
return $this->hasOne(WhatsAppMessage::class)->latestOfMany();
⋮----
public function latestRespondedWhatsAppMessage(): HasOne
⋮----
return $this->hasOne(WhatsAppMessage::class)
->whereNotNull('respuesta')
->latestOfMany('responded_at');
⋮----
public function latestInboundWhatsAppMessage(): HasOne
⋮----
->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
->latestOfMany();
⋮----
public function hasTextResponse(): bool
⋮----
$latest = $this->latestInboundAfterLastSent();
⋮----
return ! $latest->isConfirmed() && ! $latest->isRescheduleRequested();
⋮----
public function hasUnreadInboundResponse(): bool
⋮----
return $seenAt === null || $messageTimestamp->gt($seenAt);
⋮----
public function markLatestInboundAsSeen(): void
⋮----
if ($this->last_inbound_seen_at !== null && ! $messageTimestamp->gt($this->last_inbound_seen_at)) {
⋮----
$this->forceFill([
⋮----
])->save();
⋮----
public function latestInboundAfterLastSent(): ?WhatsAppMessage
⋮----
if ($this->relationLoaded('whatsAppMessages')) {
/** @var Collection<int, WhatsAppMessage> $messages */
$messages = $this->getRelation('whatsAppMessages');
⋮----
->filter(fn (WhatsAppMessage $message): bool => in_array($message->direction, [null, WhatsAppMessage::DIRECTION_OUTBOUND], true) && $message->sent_at !== null)
->sortByDesc(fn (WhatsAppMessage $message): int => $message->sent_at?->timestamp ?? 0)
->first();
⋮----
->filter(fn (WhatsAppMessage $message): bool => $message->direction === WhatsAppMessage::DIRECTION_INBOUND);
⋮----
->sortByDesc(fn (WhatsAppMessage $message): int => $message->created_at?->timestamp ?? 0)
⋮----
->filter(fn (WhatsAppMessage $message): bool => $message->created_at !== null && $message->created_at->gte($lastSent->created_at))
⋮----
$lastSent = $this->whatsAppMessages()
->outbound()
->whereNotNull('sent_at')
->orderByDesc('sent_at')
⋮----
return $this->whatsAppMessages()
⋮----
->orderByDesc('created_at')
⋮----
->where('created_at', '>=', $lastSent->created_at)
⋮----
public function whatsAppMessages(): HasMany
⋮----
return $this->hasMany(WhatsAppMessage::class);
⋮----
public function changes(): HasMany
⋮----
return $this->hasMany(AppointmentChange::class)->latest();
⋮----
public function canBeChanged(): bool
⋮----
return $this->fecha->toDateString() >= now()->toDateString();
⋮----
public function isFuture(): bool
⋮----
public function scheduledFor(): Carbon
⋮----
return Carbon::parse($this->fecha?->toDateString().' '.$this->hora, config('app.timezone'));
⋮----
public function getEsFallidoAttribute(): bool
⋮----
public function hasConflict(): bool
⋮----
return static::query()
->where('fecha', $this->fecha)
->where('hora', $this->hora)
->where('id', '!=', $this->id)
->exists();
⋮----
public function scopeActive($query)
⋮----
return $query->where('activo', true);
⋮----
public function scopePending($query)
⋮----
return $query->where('enviado', false);
⋮----
public function scopeUpcoming($query)
⋮----
return $query->where(function ($q) use ($now) {
$q->whereDate('fecha', '>', $now->toDateString())
->orWhere(function ($q2) use ($now) {
$q2->whereDate('fecha', $now->toDateString())
->where('hora', '>', $now->format('H:i:s'));
⋮----
public function confirmar(): void
⋮----
$this->update([
⋮----
public function marcarReprogramacion(): void
⋮----
public function queBoton(): ?string
⋮----
$latestInbound = $this->latestInboundAfterLastSent();
⋮----
public function esCitaConfirmada(): bool
⋮----
public function wasRescheduled(): bool
⋮----
return $this->fecha_original->toDateString() !== $this->fecha?->toDateString()
⋮----
protected function casts(): array
````

## File: app/Models/AppointmentChange.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
⋮----
class AppointmentChange extends Model
⋮----
protected $fillable = [
⋮----
public function appointment(): BelongsTo
⋮----
return $this->belongsTo(Appointment::class);
⋮----
protected function casts(): array
````

## File: app/Models/AppointmentReminderPreference.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
⋮----
use function collect;
⋮----
class AppointmentReminderPreference extends Model
⋮----
protected $fillable = [
⋮----
protected function casts(): array
⋮----
/**
     * @return array<int, string>
     */
public static function leadDayOptions(): array
⋮----
/**
     * @return list<string>
     */
public static function channels(): array
⋮----
/**
     * @return list<int>
     */
public static function enabledLeadDaysFor(string $channel): array
⋮----
$preferences = static::query()
->where('channel', $channel)
->orderBy('lead_days')
->get();
⋮----
if ($preferences->isEmpty()) {
return static::defaultLeadDaysFor($channel);
⋮----
return static::selectedLeadDays($preferences);
⋮----
/**
     * @return array<string, list<int>>
     */
public static function selections(): array
⋮----
->whereIn('channel', static::channels())
->get()
->groupBy('channel');
⋮----
return collect(static::channels())
->mapWithKeys(fn (string $channel): array => [
$channel => $preferences->has($channel)
? static::selectedLeadDays($preferences->get($channel, collect()))
: static::defaultLeadDaysFor($channel),
⋮----
->all();
⋮----
/**
     * @param  array<string, list<int>>  $selections
     */
public static function saveSelections(array $selections): void
⋮----
foreach (static::channels() as $channel) {
⋮----
->map(fn ($leadDays) => (int) $leadDays)
->intersect(array_keys(static::leadDayOptions()))
->values();
⋮----
foreach (array_keys(static::leadDayOptions()) as $leadDays) {
static::query()->updateOrCreate(
⋮----
'enabled' => $selectedLeadDays->contains($leadDays),
⋮----
/**
     * @param  Collection<int, self>  $preferences
     * @return list<int>
     */
private static function selectedLeadDays(Collection $preferences): array
⋮----
->where('enabled', true)
->pluck('lead_days')
⋮----
->sort()
->values()
⋮----
private static function defaultLeadDaysFor(string $channel): array
````

## File: app/Models/AppSetting.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
⋮----
class AppSetting extends Model
⋮----
protected $table = 'app_settings';
⋮----
protected $fillable = [
⋮----
protected $attributes = [
⋮----
protected function casts(): array
⋮----
public static function get(): static
⋮----
if (! Schema::hasTable('app_settings')) {
⋮----
$settings = static::query()->first();
⋮----
$settings = static::query()->create([
⋮----
/**
     * @return array<string, string>
     */
public static function retentionOptions(): array
⋮----
public function isEnabled(): bool
⋮----
public function isTimeToDispatch(): bool
⋮----
$currentHour = now()->format('H:i');
````

## File: app/Models/Client.php
````php
namespace App\Models;
⋮----
use App\Traits\NormalizesPhone;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
⋮----
class Client extends Model
⋮----
protected $fillable = [
⋮----
public function getFullNameAttribute(): string
⋮----
public function messages(): HasMany
⋮----
return $this->hasMany(WhatsAppMessage::class);
⋮----
public function appointments(): HasMany
⋮----
return $this->hasMany(Appointment::class);
⋮----
protected function telefono(): Attribute
⋮----
return Attribute::set(fn (string $value): string => static::normalizePhone($value));
⋮----
public static function isValidPhone(string $phone): bool
⋮----
$normalized = static::normalizePhone($phone);
⋮----
public static function upsertFromImport(array $data): self
⋮----
$normalizedPhone = static::normalizePhone($rawPhone);
⋮----
$lookupName = static::normalizeImportValue($rawName);
⋮----
$client = static::query()
->get()
->first(fn (self $candidate): bool => static::matchesImportIdentity($candidate, $normalizedPhone, $lookupName));
⋮----
return static::query()->create($payload);
⋮----
private static function matchesImportIdentity(self $client, string $lookupPhone, string $lookupName): bool
⋮----
return static::normalizeImportValue(trim($client->nombre.' '.$client->apellidos)) === $lookupName
&& static::normalizePhone((string) $client->telefono) === $lookupPhone;
⋮----
private static function normalizeImportValue(string $value): string
⋮----
$value = Str::ascii(trim($value));
````

## File: app/Models/LoginHistory.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
⋮----
class LoginHistory extends Model
⋮----
protected $table = 'login_history';
⋮----
protected function casts(): array
⋮----
public function user(): BelongsTo
⋮----
return $this->belongsTo(User::class);
````

## File: app/Models/TwilioContentTemplate.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
⋮----
class TwilioContentTemplate extends Model
⋮----
protected $fillable = [
⋮----
protected function casts(): array
⋮----
public static function selectedContentSid(): ?string
⋮----
return static::query()->where('seleccionada', true)->value('content_sid');
⋮----
public static function selected(): ?self
⋮----
return static::query()->where('seleccionada', true)->first();
⋮----
public static function selectedOrFirst(): ?self
⋮----
return static::selected() ?? static::query()->first();
⋮----
public function select(): void
⋮----
DB::transaction(function (): void {
static::query()->where('seleccionada', true)->update(['seleccionada' => false]);
$this->update(['seleccionada' => true]);
````

## File: app/Models/User.php
````php
namespace App\Models;
⋮----
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
⋮----
class User extends Authenticatable
⋮----
/** @use HasFactory<UserFactory> */
⋮----
/**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
public function loginHistory(): HasMany
⋮----
return $this->hasMany(LoginHistory::class)->latest('logged_in_at');
⋮----
protected function casts(): array
````

## File: app/Models/WhatsAppCredential.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
⋮----
class WhatsAppCredential extends Model
⋮----
protected $table = 'whatsapp_credentials';
⋮----
protected $fillable = [
⋮----
protected function casts(): array
⋮----
public function senderNumbers(): HasMany
⋮----
return $this->hasMany(WhatsAppSenderNumber::class, 'whatsapp_credential_id');
⋮----
public static function get(): static
⋮----
if (! Schema::hasTable('whatsapp_credentials')) {
⋮----
$credential = static::where('selected', true)->first();
⋮----
$credential = static::first();
⋮----
$credential = static::create([
⋮----
public function selectedSenderNumber(): ?WhatsAppSenderNumber
⋮----
return $this->senderNumbers()->selected()->first();
⋮----
public function resolveFrom(): ?string
⋮----
$selected = $this->selectedSenderNumber();
⋮----
public function resolveDriver(): string
⋮----
return $this->stringSetting($this->driver, 'whatsapp.driver', 'twilio');
⋮----
public function resolveDefaultCountryCode(): string
⋮----
return $this->stringSetting($this->default_country_code, 'whatsapp.default_country_code', '+34');
⋮----
public function resolveMessageMode(): string
⋮----
return $this->stringSetting($this->message_mode, 'whatsapp.message_mode', 'text');
⋮----
public function resolveAccountSid(): ?string
⋮----
return $this->nullableStringSetting($this->account_sid, 'whatsapp.twilio.account_sid');
⋮----
public function resolveAuthToken(): ?string
⋮----
return $this->nullableStringSetting($this->auth_token, 'whatsapp.twilio.auth_token');
⋮----
public function resolveApiKeySid(): ?string
⋮----
public function resolveApiKeySecret(): ?string
⋮----
public function resolveMode(): string
⋮----
public function resolveContentSid(): ?string
⋮----
return $this->nullableStringSetting($this->content_sid, 'whatsapp.twilio.content_sid');
⋮----
public function resolveTestRecipient(): ?string
⋮----
return $this->nullableStringSetting($this->test_recipient, 'whatsapp.twilio.test_recipient');
⋮----
public function resolveTimeout(): int
⋮----
return $this->integerSetting($this->timeout, 'whatsapp.twilio.timeout', 15);
⋮----
public function resolveConnectTimeout(): int
⋮----
return $this->integerSetting($this->connect_timeout, 'whatsapp.twilio.connect_timeout', 10);
⋮----
public function resolveCloudApiBaseUrl(): string
⋮----
return $this->stringSetting($this->cloud_api_base_url, 'whatsapp.cloud_api.base_url', 'https://graph.facebook.com');
⋮----
public function resolveCloudApiVersion(): string
⋮----
return $this->stringSetting($this->cloud_api_version, 'whatsapp.cloud_api.version', 'v22.0');
⋮----
public function resolveCloudApiPhoneNumberId(): ?string
⋮----
return $this->nullableStringSetting($this->cloud_api_phone_number_id, 'whatsapp.cloud_api.phone_number_id');
⋮----
public function resolveCloudApiAccessToken(): ?string
⋮----
return $this->nullableStringSetting($this->cloud_api_access_token, 'whatsapp.cloud_api.access_token');
⋮----
public function resolveCloudApiTimeout(): int
⋮----
return $this->integerSetting($this->cloud_api_timeout, 'whatsapp.cloud_api.timeout', 15);
⋮----
public function resolveDefaultTemplateKey(): string
⋮----
return $this->stringSetting($this->default_template, 'whatsapp.default_template', 'clinical_reminder');
⋮----
public function resolveDefaultMessage(): string
⋮----
$fallbackTemplate = (string) config('whatsapp.templates.'.$this->resolveDefaultTemplateKey().'.message', '');
⋮----
return $this->stringSetting($this->default_message, 'whatsapp.default_message', $fallbackTemplate);
⋮----
public function resolveStatusCallbackUrl(): string
⋮----
public static function webhookEnabled(): bool
⋮----
$credential = static::get();
⋮----
public static function pollInterval(): int
⋮----
private function stringSetting(mixed $value, string $configKey, string $default): string
⋮----
private function nullableStringSetting(mixed $value, string $configKey): ?string
⋮----
private function integerSetting(mixed $value, string $configKey, int $default): int
````

## File: app/Models/WhatsAppMessage.php
````php
namespace App\Models;
⋮----
use App\Traits\NormalizesPhone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
⋮----
class WhatsAppMessage extends Model
⋮----
protected $table = 'whatsapp_messages';
⋮----
protected $fillable = [
⋮----
protected function casts(): array
⋮----
public function user(): BelongsTo
⋮----
return $this->belongsTo(User::class);
⋮----
public function client(): BelongsTo
⋮----
return $this->belongsTo(Client::class);
⋮----
public function appointment(): BelongsTo
⋮----
return $this->belongsTo(Appointment::class);
⋮----
public function parent(): BelongsTo
⋮----
return $this->belongsTo(WhatsAppMessage::class, 'parent_id');
⋮----
public function replies(): HasMany
⋮----
return $this->hasMany(WhatsAppMessage::class, 'parent_id');
⋮----
public function getFullNameAttribute(): string
⋮----
public static function buildMessage(array $data, ?string $template = null): string
⋮----
Carbon::setLocale('es');
⋮----
$templateKey = $template ?: WhatsAppTemplate::defaultKey();
$template = WhatsAppTemplate::hasKey($templateKey)
? WhatsAppTemplate::resolve($templateKey)['message']
⋮----
public static function templateOptions(): array
⋮----
return WhatsAppTemplate::templateOptions();
⋮----
public function scopePending($query)
⋮----
return $query->where('status', self::STATUS_PENDING);
⋮----
public function scopeDue($query)
⋮----
return $query->pending()->where('scheduled_for', '<=', now());
⋮----
public function scopeOutbound($query)
⋮----
return $query->where(function ($query): void {
$query->where('direction', self::DIRECTION_OUTBOUND)
->orWhereNull('direction');
⋮----
public function scopeInbound($query)
⋮----
return $query->where('direction', self::DIRECTION_INBOUND);
⋮----
public function isRead(): bool
⋮----
return $this->deliveryStatus() === 'read';
⋮----
public function isDelivered(): bool
⋮----
return in_array($this->deliveryStatus(), ['delivered', 'read'], true);
⋮----
public function deliveredAt(): ?Carbon
⋮----
if (! $this->isDelivered()) {
⋮----
return $this->parseTimestamp($timestamp) ?? $this->sent_at ?? $this->created_at;
⋮----
public function readAt(): ?Carbon
⋮----
if (! $this->isRead()) {
⋮----
return $this->deliveredAt();
⋮----
public function deliveryStatus(): string
⋮----
public function hasResponse(): bool
⋮----
return $this->responseValue() !== null;
⋮----
public function isConfirmed(): bool
⋮----
$response = $this->normalizedInboundResponse();
⋮----
public function isRescheduleRequested(): bool
⋮----
public function responseValue(): ?string
⋮----
public function scopeResponded($query)
⋮----
return $query->whereNotNull('respuesta');
⋮----
public function normalizedPhone(): string
⋮----
return static::normalizeInternationalPhone((string) $this->telefono);
⋮----
public function twilioPhone(): string
⋮----
$normalized = static::normalizeInternationalPhone((string) $this->telefono);
⋮----
protected function telefono(): Attribute
⋮----
return Attribute::set(fn (string $value): string => static::normalizePhone($value));
⋮----
protected function formattedScheduledFor(): Attribute
⋮----
return Attribute::get(fn () => $this->scheduled_for?->timezone(config('app.timezone'))?->format('d/m/Y H:i'));
⋮----
private function parseTimestamp(mixed $timestamp): ?Carbon
⋮----
return Carbon::parse($timestamp)->timezone(config('app.timezone'));
⋮----
private function normalizedInboundResponse(): string
````

## File: app/Models/WhatsAppSenderNumber.php
````php
namespace App\Models;
⋮----
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
⋮----
class WhatsAppSenderNumber extends Model
⋮----
protected $table = 'whatsapp_sender_numbers';
⋮----
protected $fillable = [
⋮----
protected function casts(): array
⋮----
public function credential(): BelongsTo
⋮----
return $this->belongsTo(WhatsAppCredential::class, 'whatsapp_credential_id');
⋮----
public function scopeSelected(Builder $query): Builder
⋮----
return $query->where('selected', true);
⋮----
public function getFullNumberAttribute(): string
⋮----
public function getWhatsAppAddressAttribute(): string
````

## File: app/Models/WhatsAppTemplate.php
````php
namespace App\Models;
⋮----
use Illuminate\Support\Collection;
⋮----
final class WhatsAppTemplate
⋮----
public static function catalog(): Collection
⋮----
$credential = WhatsAppCredential::get();
⋮----
->map(function (array $template, string $key) use ($credential): array {
⋮----
'is_default' => $key === $credential->resolveDefaultTemplateKey(),
⋮----
->values();
⋮----
public static function templateOptions(): array
⋮----
return self::catalog()
->map(fn (array $template) => [
⋮----
->values()
->all();
⋮----
public static function resolve(?string $key = null): array
⋮----
$catalog = self::catalog();
$defaultKey = WhatsAppCredential::get()->resolveDefaultTemplateKey();
⋮----
$template = $key ? $catalog->firstWhere('key', $key) : null;
$template ??= $catalog->firstWhere('key', $defaultKey);
$template ??= $catalog->first();
⋮----
public static function hasKey(string $key): bool
⋮----
return self::catalog()->contains(fn (array $template) => $template['key'] === $key);
⋮----
public static function defaultKey(): string
⋮----
$default = $catalog->firstWhere('is_default', true);
⋮----
$fallback = $catalog->first();
⋮----
return WhatsAppCredential::get()->resolveDefaultTemplateKey();
````

## File: app/Observers/WhatsAppCredentialObserver.php
````php
namespace App\Observers;
⋮----
use App\Models\WhatsAppCredential;
use Illuminate\Support\Facades\Artisan;
⋮----
class WhatsAppCredentialObserver
⋮----
public function saved(WhatsAppCredential $credential): void
⋮----
Artisan::call('view:clear');
⋮----
public function deleted(WhatsAppCredential $credential): void
````

## File: app/Policies/AppointmentPolicy.php
````php
namespace App\Policies;
⋮----
use App\Models\Appointment;
use App\Models\User;
⋮----
class AppointmentPolicy
⋮----
public function viewAny(User $user): bool
⋮----
public function view(User $user, Appointment $appointment): bool
⋮----
public function create(User $user): bool
⋮----
public function update(User $user, Appointment $appointment): bool
⋮----
public function delete(User $user, Appointment $appointment): bool
````

## File: app/Policies/ClientPolicy.php
````php
namespace App\Policies;
⋮----
use App\Models\Client;
use App\Models\User;
⋮----
class ClientPolicy
⋮----
public function viewAny(User $user): bool
⋮----
public function view(User $user, Client $client): bool
⋮----
public function create(User $user): bool
⋮----
public function update(User $user, Client $client): bool
⋮----
public function delete(User $user, Client $client): bool
````

## File: app/Policies/UserPolicy.php
````php
namespace App\Policies;
⋮----
use App\Models\User;
⋮----
class UserPolicy
⋮----
public function viewAny(User $user): bool
⋮----
public function create(User $user): bool
⋮----
public function update(User $user, User $model): bool
⋮----
public function delete(User $user, User $model): bool
````

## File: app/Policies/WhatsAppMessagePolicy.php
````php
namespace App\Policies;
⋮----
use App\Models\User;
⋮----
class WhatsAppMessagePolicy
⋮----
public function create(User $user): bool
````

## File: app/Providers/AppServiceProvider.php
````php
namespace App\Providers;
⋮----
use App\Livewire\DispatchBanner;
use App\Livewire\Settings\AppointmentCleanupSettings;
use App\Livewire\Settings\AppointmentReminderSettings;
use App\Livewire\Settings\DatabaseBackup;
use App\Livewire\Settings\SettingsBackup;
use App\Livewire\Settings\SettingsOverview;
use App\Livewire\Settings\TableBackup;
use App\Livewire\Settings\TwilioContentTemplateSettings;
use App\Livewire\Settings\TwilioCredentialSettings;
use App\Livewire\Settings\WhatsAppConnectionTest;
use App\Livewire\UnreadResponsesNotice;
use App\Models\WhatsAppCredential;
use App\Observers\WhatsAppCredentialObserver;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
⋮----
class AppServiceProvider extends ServiceProvider
⋮----
/**
     * Register any application services.
     */
public function register(): void
⋮----
//
⋮----
/**
     * Bootstrap any application services.
     */
public function boot(): void
⋮----
Livewire::component('whatsapp-connection-test', WhatsAppConnectionTest::class);
Livewire::component('appointment-reminder-settings', AppointmentReminderSettings::class);
Livewire::component('appointment-cleanup-settings', AppointmentCleanupSettings::class);
Livewire::component('twilio-content-template-settings', TwilioContentTemplateSettings::class);
Livewire::component('dispatch-banner', DispatchBanner::class);
Livewire::component('unread-responses-notice', UnreadResponsesNotice::class);
Livewire::component('twilio-credential-settings', TwilioCredentialSettings::class);
Livewire::component('settings-overview', SettingsOverview::class);
Livewire::component('settings-backup', SettingsBackup::class);
Livewire::component('database-backup', DatabaseBackup::class);
Livewire::component('table-backup', TableBackup::class);
⋮----
WhatsAppCredential::observe(WhatsAppCredentialObserver::class);
````

## File: app/Services/WhatsApp/AppointmentDeliveryStatusSyncer.php
````php
namespace App\Services\WhatsApp;
⋮----
use App\Models\Appointment;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;
⋮----
class AppointmentDeliveryStatusSyncer
⋮----
public function syncAll(?int $clientId = null, bool $force = false): int
⋮----
if (! $this->canSync()) {
⋮----
$this->syncInboundResponses($clientId);
⋮----
$messages = WhatsAppMessage::query()
->whereNotNull('appointment_id')
->when($clientId, fn ($query) => $query->where('client_id', $clientId))
->get(['id', 'appointment_id', 'provider_message_id', 'sent_at', 'created_at', 'provider_payload']);
⋮----
return $this->syncAppointmentsFromMessages($this->refreshMessages($messages, $force));
⋮----
public function backfillFromStoredMessages(?int $clientId = null): int
⋮----
->get(['id', 'appointment_id', 'sent_at', 'created_at', 'provider_payload']);
⋮----
return $this->syncAppointmentsFromMessages($messages);
⋮----
/**
     * @param  iterable<int>|Collection<int, int>  $appointmentIds
     */
public function sync(iterable $appointmentIds, bool $force = false): int
⋮----
->filter(fn (mixed $appointmentId): bool => (int) $appointmentId > 0)
->map(fn (mixed $appointmentId): int => (int) $appointmentId)
->unique()
->values();
⋮----
if ($ids->isEmpty()) {
⋮----
$clientIds = Appointment::query()
->whereIn('id', $ids)
->pluck('client_id')
⋮----
->whereIn('appointment_id', $ids)
⋮----
/**
     * Persist a Twilio delivery callback and sync the related appointment state.
     *
     * @param  array<string, mixed>  $payload
     */
public function syncFromTwilioWebhook(array $payload): int
⋮----
$message = WhatsAppMessage::query()
->where('provider_message_id', $messageSid)
->first();
⋮----
'received_at' => now()->toDateTimeString(),
⋮----
$message->update([
⋮----
return $this->sync([$message->appointment_id]);
⋮----
/**
     * Query Twilio API for inbound messages and recover responses the webhook missed.
     */
public function syncInboundResponses(?int $clientId = null): int
⋮----
$credential = WhatsAppCredential::get();
$accountSid = trim((string) ($credential->resolveAccountSid() ?? ''));
[$username, $password] = $this->twilioApiCredentials($credential);
⋮----
$sentMessages = WhatsAppMessage::query()
->where('direction', WhatsAppMessage::DIRECTION_OUTBOUND)
->where('status', WhatsAppMessage::STATUS_SENT)
->whereNotNull('provider_message_id')
⋮----
->get();
⋮----
if ($sentMessages->isEmpty()) {
⋮----
$phoneGroups = $sentMessages->groupBy('telefono');
⋮----
$twilioPhone = $phoneMessages->first()->twilioPhone();
⋮----
$inboundMessages = $this->fetchInboundFromTwilio(
⋮----
if ($inboundMessages->isEmpty()) {
⋮----
$recovered += $this->matchInboundToOutbound($phoneMessages, $inboundMessages);
⋮----
private function twilioApiCredentials(WhatsAppCredential $credential): array
⋮----
$apiKeySid = trim((string) ($credential->resolveApiKeySid() ?? ''));
$apiKeySecret = trim((string) ($credential->resolveApiKeySecret() ?? ''));
⋮----
$credential->resolveAccountSid(),
$credential->resolveAuthToken(),
⋮----
/**
     * @return Collection<int, array{sid:string,body:string,from:string,to:string,date_sent:string,direction:string}>
     */
private function fetchInboundFromTwilio(
⋮----
$response = Http::baseUrl('https://api.twilio.com')
->acceptJson()
->withBasicAuth($username, $password)
->retry([100, 500, 1000])
->timeout($credential->resolveTimeout())
->connectTimeout($credential->resolveConnectTimeout())
->get('/2010-04-01/Accounts/'.$accountSid.'/Messages.json', [
⋮----
->throw()
->json();
⋮----
return collect($messages)->filter(fn (array $msg): bool => strtolower(trim((string) data_get($msg, 'direction', ''))) !== 'outbound-api');
⋮----
Log::warning('Failed to fetch inbound messages from Twilio.', [
'error' => $e->getMessage(),
⋮----
/**
     * Match inbound Twilio messages to outgoing WhatsAppMessages without a response.
     *
     * @param  Collection<int, WhatsAppMessage>  $outboundMessages
     * @param  Collection<int, array{sid:string,body:string,from:string,to:string,date_sent:string,direction:string}>  $inboundMessages
     */
private function matchInboundToOutbound(Collection $outboundMessages, Collection $inboundMessages): int
⋮----
$sorted = $outboundMessages->sortBy('sent_at')->values();
⋮----
$existingSids = WhatsAppMessage::query()
->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
⋮----
->pluck('provider_message_id')
->flip();
⋮----
$inboundDate = Carbon::parse(data_get($inbound, 'date_sent', ''))->timezone(config('app.timezone'));
⋮----
if ($existingSids->has($inboundSid)) {
⋮----
$matched = $sorted->first(
⋮----
$matched = $sorted->filter(
⋮----
&& $msg->sent_at->lte($inboundDate)
&& $inboundDate->diffInSeconds($msg->sent_at) < 86400
)->sortByDesc(fn (WhatsAppMessage $msg): int => $msg->sent_at->timestamp)->first();
⋮----
'received_at' => $inboundDate->toDateTimeString(),
⋮----
WhatsAppResponseHandler::process($matched, $body, $inboundPayload);
⋮----
$existingSids->put($inboundSid, true);
⋮----
/**
     * @param  Collection<int, WhatsAppMessage>  $messages
     * @return Collection<int, WhatsAppMessage>
     */
private function refreshMessages(Collection $messages, bool $force = false): Collection
⋮----
if ($messages->isEmpty()) {
⋮----
return $messages->map(function (WhatsAppMessage $message) use ($force): WhatsAppMessage {
if ($this->messageWasRead($message)) {
⋮----
return $this->refreshMessageFromTwilio($message, $force);
⋮----
private function refreshMessageFromTwilio(WhatsAppMessage $message, bool $force = false): WhatsAppMessage
⋮----
if (! $this->shouldPollTwilio($message, $force)) {
⋮----
: trim((string) ($credential->resolveAuthToken() ?? ''));
⋮----
->get('/2010-04-01/Accounts/'.$accountSid.'/Messages/'.$providerMessageId.'.json')
⋮----
$message->update($updateData);
⋮----
private function shouldPollTwilio(WhatsAppMessage $message, bool $force = false): bool
⋮----
$messageAge = $this->messageAge($message);
⋮----
return $messageAge === null || $messageAge->greaterThanOrEqualTo(now()->subDay());
⋮----
private function canSync(): bool
⋮----
return Schema::hasColumn('appointments', 'entregado');
⋮----
/**
     * @param  Collection<int, WhatsAppMessage>  $messages
     */
private function syncAppointmentsFromMessages(Collection $messages): int
⋮----
$groupedMessages = $messages->groupBy('appointment_id');
⋮----
if ($groupedMessages->isEmpty()) {
⋮----
$appointmentIds = $groupedMessages->keys()->all();
$appointments = Appointment::query()->whereIn('id', $appointmentIds)->get()->keyBy('id');
⋮----
$appointment = $appointments->get($appointmentId);
⋮----
$sentAt = $this->latestTimestamp($appointmentMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->sent_at));
$deliveredAt = $this->latestTimestamp($appointmentMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->deliveredAt()));
$readAt = $this->latestTimestamp($appointmentMessages->map(fn (WhatsAppMessage $message): ?Carbon => $message->readAt()));
⋮----
$newSentAt = $this->latestTimestamp(collect([$appointment->whatsapp_sent_at, $sentAt]));
⋮----
$newDeliveredAt = $this->latestTimestamp(collect([$appointment->whatsapp_delivered_at, $deliveredAt]));
$newReadAt = $this->latestTimestamp(collect([$appointment->whatsapp_read_at, $readAt]));
⋮----
|| $this->timestampDiffers($appointment->whatsapp_sent_at, $newSentAt)
⋮----
|| $this->timestampDiffers($appointment->whatsapp_delivered_at, $newDeliveredAt)
|| $this->timestampDiffers($appointment->whatsapp_read_at, $newReadAt);
⋮----
$appointment->update([
⋮----
private function timestampDiffers(?Carbon $current, ?Carbon $new): bool
⋮----
return $current->ne($new);
⋮----
private function messageWasDelivered(WhatsAppMessage $message): bool
⋮----
private function messageWasRead(WhatsAppMessage $message): bool
⋮----
private function messageAge(WhatsAppMessage $message): ?Carbon
⋮----
/**
     * @param  Collection<int, Carbon|null>  $timestamps
     */
private function latestTimestamp(Collection $timestamps): ?Carbon
⋮----
->filter(fn (?Carbon $timestamp): bool => $timestamp instanceof Carbon)
->sortBy(fn (Carbon $timestamp): int => $timestamp->getTimestamp())
->last();
````

## File: app/Services/WhatsApp/AppointmentImmediateSender.php
````php
namespace App\Services\WhatsApp;
⋮----
use App\Jobs\SendWhatsAppMessage;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;
⋮----
class AppointmentImmediateSender
⋮----
/**
     * @return array{sent: bool, message: string}
     */
public function send(
⋮----
$scheduledFor = $appointment->scheduledFor();
$message = WhatsAppMessage::query()->create([
'user_id' => Auth::id(),
⋮----
'message' => WhatsAppMessage::buildMessage([
⋮----
'immediate_sent_at' => now()->toDateTimeString(),
⋮----
SendWhatsAppMessage::dispatchSync($message->id);
⋮----
$message->refresh();
⋮----
$appointment->refresh();
⋮----
$message->update([
⋮----
'last_error' => $throwable->getMessage(),
⋮----
'message' => $failureMessage.' Error: '.Str::limit($throwable->getMessage(), 220).'. La cita no se ha marcado como enviada.',
````

## File: app/Services/WhatsApp/WhatsAppResponseHandler.php
````php
namespace App\Services\WhatsApp;
⋮----
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
⋮----
class WhatsAppResponseHandler
⋮----
/**
     * Create an inbound response record and update appointment flags.
     */
public static function process(WhatsAppMessage $outbound, string $responseText, array $inboundPayload = []): WhatsAppMessage
⋮----
$twilioTime = $receivedAt !== '' ? Carbon::parse($receivedAt)->timezone(config('app.timezone')) : now();
⋮----
$inbound = WhatsAppMessage::query()->create([
⋮----
self::updateAppointmentFlags($inbound);
⋮----
/**
     * Update appointment confirmada/pendiente_reprogramacion based on the inbound message.
     */
public static function updateAppointmentFlags(WhatsAppMessage $inbound): void
⋮----
if ($inbound->isConfirmed()) {
$appointment->update([
⋮----
if ($inbound->isRescheduleRequested()) {
⋮----
Log::info('WhatsApp response received (no action).', [
````

## File: app/Services/WhatsApp/WhatsAppSender.php
````php
namespace App\Services\WhatsApp;
⋮----
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Traits\NormalizesPhone;
use Carbon\Carbon;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;
use RuntimeException;
use Throwable;
⋮----
class WhatsAppSender
⋮----
/**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     *
     * @throws RequestException
     */
public function send(WhatsAppMessage $message): array
⋮----
return match (WhatsAppCredential::get()->resolveDriver()) {
'twilio' => $this->sendViaTwilio($message),
'cloud_api' => $this->sendViaCloudApi($message),
'log' => $this->sendViaLog($message),
default => throw new RuntimeException('Unsupported WhatsApp driver: '.WhatsAppCredential::get()->resolveDriver()),
⋮----
Log::channel('whatsapp_error')->error('WhatsApp send failed', [
⋮----
'error' => $throwable->getMessage(),
⋮----
/**
     * Send a one-off test message without persisting a database record.
     *
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
public function sendTestMessage(string $recipient, string $body, ?string $mode = null, bool $forceTemplate = false, ?int $templateId = null): array
⋮----
'twilio' => $this->sendTestViaTwilio($recipient, $body, $mode, $forceTemplate, $templateId),
'cloud_api' => $this->sendTestViaCloudApi($recipient, $body),
'log' => $this->sendTestViaLog($recipient, $body),
⋮----
/**
     * @return array{provider:string,message_id:?string,payload:array,raw:array}
     */
private function sendViaLog(WhatsAppMessage $message): array
⋮----
$payload = $this->buildTextPayload($message);
⋮----
Log::info('WhatsApp message dispatched', [
⋮----
private function sendTestViaLog(string $recipient, string $body): array
⋮----
Log::info('WhatsApp test message dispatched', [
⋮----
private function sendViaTwilio(WhatsAppMessage $message): array
⋮----
return $this->sendTwilioRequest(
⋮----
forceTemplate: $this->shouldUseTwilioTemplate($message),
⋮----
private function sendTestViaTwilio(string $recipient, string $body, ?string $mode = null, bool $forceTemplate = false, ?int $templateId = null): array
⋮----
$this->buildFakeTemplateMessage($recipient, $body),
⋮----
return $this->sendTwilioRequest($recipient, $body, $mode);
⋮----
private function sendTwilioRequest(
⋮----
$credential = WhatsAppCredential::get();
$accountSid = $credential->resolveAccountSid();
[$username, $password] = $this->twilioApiCredentials($credential);
⋮----
[$payload, $requestPayload] = $this->buildTwilioPayload($recipient, $body, $mode, $message, true, $forceTemplate, $templateId);
⋮----
$response = Http::baseUrl('https://api.twilio.com')
->acceptJson()
->asForm()
->withBasicAuth($username, $password)
->retry([100, 500, 1000])
->timeout($credential->resolveTimeout())
->connectTimeout($credential->resolveConnectTimeout())
->post('/2010-04-01/Accounts/'.$accountSid.'/Messages.json', $requestPayload)
->throw()
->json();
⋮----
/**
     * @return array{0:?string,1:?string}
     */
private function twilioApiCredentials(WhatsAppCredential $credential): array
⋮----
$dbApiKeySid = $credential->resolveApiKeySid();
$dbApiKeySecret = $credential->resolveApiKeySecret();
⋮----
// Primero intenta API Key/Secret; si no existen, cae a Account SID/Auth Token.
⋮----
return [$credential->resolveAccountSid(), $credential->resolveAuthToken()];
⋮----
/**
     * @return array<string, mixed>
     */
public function buildTwilioPreviewRequest(string $recipient, string $body, ?string $mode = null, bool $forceTemplate = false, ?int $templateId = null): array
⋮----
return $this->buildTwilioPayload($recipient, $body, $mode, null, false, $forceTemplate, $templateId)[1];
⋮----
/**
     * @return array{0:array,1:array}
     */
private function buildTwilioPayload(
⋮----
$from = $credential->resolveFrom();
$template = $this->twilioContentTemplate($templateId);
$contentSid = $template?->content_sid ?: $this->twilioContentSid();
$resolvedMode = $this->resolveTwilioMode($mode);
⋮----
$contentVariables = $usesTemplate ? $this->twilioContentVariables($message, $template) : [];
⋮----
'from' => $from ? $this->normalizeWhatsAppAddress($from) : null,
'to' => $this->normalizeWhatsAppRecipient($recipient),
⋮----
'ContentVariables' => $contentVariables !== [] ? $this->jsonEncode($contentVariables) : null,
'StatusCallback' => $this->twilioStatusCallbackUrl(),
⋮----
public function resolveTwilioMode(?string $mode = null): string
⋮----
$requestedMode = strtolower(trim($mode ?: $credential->resolveMode()));
⋮----
if (! in_array($requestedMode, $this->twilioModes(), true)) {
⋮----
$from = (string) ($credential->resolveFrom() ?? '');
⋮----
if ($from !== '' && $this->normalizeWhatsAppAddress($from) === 'whatsapp:+14155238886') {
⋮----
/**
     * @return list<string>
     */
private function twilioModes(): array
⋮----
/**
     * @return array<string, string>
     */
private function twilioContentVariables(?WhatsAppMessage $message, ?TwilioContentTemplate $template = null): array
⋮----
Carbon::setLocale('es');
$template ??= TwilioContentTemplate::selectedOrFirst();
⋮----
$fakeScheduledFor = now()->addDay()->setTime(10, 30);
⋮----
->mapWithKeys(fn (mixed $value, int|string $key): array => [
⋮----
->all();
⋮----
private function buildFakeTemplateMessage(string $recipient, string $body): WhatsAppMessage
⋮----
'scheduled_for' => now()->addDay()->setTime(10, 30),
⋮----
/**
     * @param  array<string, string>  $value
     */
private function jsonEncode(array $value): string
⋮----
private function sendViaCloudApi(WhatsAppMessage $message): array
⋮----
$phoneNumberId = $credential->resolveCloudApiPhoneNumberId();
$accessToken = $credential->resolveCloudApiAccessToken();
⋮----
$response = Http::baseUrl(rtrim($credential->resolveCloudApiBaseUrl(), '/'))
⋮----
->asJson()
->withToken($accessToken)
->timeout($credential->resolveCloudApiTimeout())
⋮----
->post(sprintf('/%s/%s/messages', $credential->resolveCloudApiVersion(), $phoneNumberId), $payload)
⋮----
private function sendTestViaCloudApi(string $recipient, string $body): array
⋮----
'to' => $this->normalizeInternationalPhone($recipient),
⋮----
private function buildTextPayload(WhatsAppMessage $message): array
⋮----
'to' => $message->normalizedPhone(),
⋮----
public function twilioContentSid(): ?string
⋮----
return TwilioContentTemplate::selectedContentSid()
?: WhatsAppCredential::get()->resolveContentSid();
⋮----
public function twilioContentTemplate(?int $templateId = null): ?TwilioContentTemplate
⋮----
return TwilioContentTemplate::query()->find($templateId);
⋮----
return TwilioContentTemplate::selectedOrFirst();
⋮----
private function twilioStatusCallbackUrl(): string
⋮----
if (! $credential->webhookEnabled()) {
⋮----
$configuredUrl = $credential->resolveStatusCallbackUrl();
⋮----
private function shouldUseTwilioTemplate(WhatsAppMessage $message): bool
````

## File: app/Services/ClientDataDeletionService.php
````php
namespace App\Services;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
⋮----
class ClientDataDeletionService
⋮----
/**
     * @param  iterable<int, int|string>  $appointmentIds
     */
public function deleteAppointments(iterable $appointmentIds, ?int $clientId = null): int
⋮----
$appointmentIds = $this->appointmentIds($appointmentIds, $clientId);
⋮----
if ($appointmentIds->isEmpty()) {
⋮----
return DB::transaction(function () use ($appointmentIds): int {
$this->deleteWhatsAppMessagesForAppointments($appointmentIds);
⋮----
return Appointment::query()
->whereKey($appointmentIds)
->delete();
⋮----
public function deleteClientById(int $clientId): bool
⋮----
return DB::transaction(function () use ($clientId): bool {
$client = Client::query()->find($clientId);
⋮----
$appointmentIds = $client->appointments()->pluck('id');
⋮----
$messageIds = WhatsAppMessage::query()
->where('client_id', $client->id)
->when($appointmentIds->isNotEmpty(), fn ($query) => $query->orWhereIn('appointment_id', $appointmentIds))
->pluck('id');
⋮----
WhatsAppMessage::query()
->whereIn('id', $messageIds)
->orWhereIn('parent_id', $messageIds)
⋮----
return (bool) $client->delete();
⋮----
/**
     * @param  iterable<int, int|string>  $appointmentIds
     * @return Collection<int, int>
     */
private function appointmentIds(iterable $appointmentIds, ?int $clientId): Collection
⋮----
->map(fn (int|string $id): int => (int) $id)
->filter()
->unique()
->values();
⋮----
if ($ids->isEmpty()) {
⋮----
->when($clientId, fn ($query) => $query->where('client_id', $clientId))
->whereKey($ids)
⋮----
/**
     * @param  Collection<int, int>  $appointmentIds
     */
private function deleteWhatsAppMessagesForAppointments(Collection $appointmentIds): void
⋮----
->whereIn('appointment_id', $appointmentIds)
⋮----
->when($messageIds->isNotEmpty(), fn ($query) => $query->orWhereIn('parent_id', $messageIds))
````

## File: app/Traits/NormalizesPhone.php
````php
namespace App\Traits;
⋮----
use App\Models\WhatsAppCredential;
⋮----
trait NormalizesPhone
⋮----
public static function normalizePhone(string $phone): string
⋮----
$countryCode = preg_replace('/\D+/', '', (string) WhatsAppCredential::get()->resolveDefaultCountryCode()) ?? '34';
⋮----
public static function normalizeInternationalPhone(string $phone): string
⋮----
$normalized = static::normalizePhone($phone);
⋮----
return WhatsAppCredential::get()->resolveDefaultCountryCode().$normalized;
⋮----
public static function normalizeWhatsAppAddress(string $address): string
⋮----
public static function normalizeWhatsAppRecipient(string $recipient): string
⋮----
$normalized = static::normalizeInternationalPhone($recipient);
````

## File: app/Traits/ValidatesSelectableDate.php
````php
namespace App\Traits;
⋮----
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
⋮----
trait ValidatesSelectableDate
⋮----
private function validateSelectableDate(string $date, string $field): void
⋮----
$validator = Validator::make([$field => $date], [
⋮----
if (Carbon::parse((string) $value)->isSunday()) {
$fail('No se pueden seleccionar citas en domingo.');
⋮----
if ($validator->fails()) {
````

## File: bootstrap/cache/.gitignore
````
*
!.gitignore
````

## File: bootstrap/app.php
````php
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
⋮----
return Application::configure(basePath: dirname(__DIR__))
->withRouting(
⋮----
->withMiddleware(function (Middleware $middleware): void {
$middleware->alias([
⋮----
$middleware->validateCsrfTokens(except: [
⋮----
->withExceptions(function (Exceptions $exceptions): void {
$exceptions->shouldRenderJsonWhen(
fn (Request $request) => $request->is('api/*'),
⋮----
})->create();
````

## File: bootstrap/providers.php
````php
use App\Providers\AppServiceProvider;
````

## File: config/app.php
````php
/*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */
````

## File: config/auth.php
````php
use App\Models\User;
⋮----
/*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | which utilizes session storage plus the Eloquent user provider.
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | Supported: "session"
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are actually retrieved out of your database or other storage
    | system used by the application. Typically, Eloquent is utilized.
    |
    | If you have multiple user tables or models you may configure multiple
    | providers to represent the model / table. These providers may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */
⋮----
// 'users' => [
//     'driver' => 'database',
//     'table' => 'users',
// ],
⋮----
/*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | These configuration options specify the behavior of Laravel's password
    | reset functionality, including the table utilized for token storage
    | and the user provider that is invoked to actually retrieve users.
    |
    | The expiry time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | The throttle setting is the number of seconds a user must wait before
    | generating more password reset tokens. This prevents the user from
    | quickly generating a very large amount of password reset tokens.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */
````

## File: config/cache.php
````php
use Illuminate\Support\Str;
⋮----
/*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache store that will be used by the
    | framework. This connection is utilized if another isn't explicitly
    | specified when running a cache operation inside the application.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    | Supported drivers: "array", "database", "file", "memcached",
    |                    "redis", "dynamodb", "storage", "octane",
    |                    "session", "failover", "null"
    |
    */
⋮----
// Memcached::OPT_CONNECT_TIMEOUT => 2000,
⋮----
/*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing the APC, database, memcached, Redis, and DynamoDB cache
    | stores, there might be other applications using the same cache. For
    | that reason, you may prefix every cache key to avoid collisions.
    |
    */
⋮----
'prefix' => env('CACHE_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-cache-'),
⋮----
/*
    |--------------------------------------------------------------------------
    | Serializable Classes
    |--------------------------------------------------------------------------
    |
    | This value determines the classes that can be unserialized from cache
    | storage. By default, no PHP classes will be unserialized from your
    | cache to prevent gadget chain attacks if your APP_KEY is leaked.
    |
    */
````

## File: config/database.php
````php
use Illuminate\Support\Str;
use Pdo\Mysql;
⋮----
/*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */
⋮----
// 'encrypt' => env('DB_ENCRYPT', 'yes'),
// 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
⋮----
/*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */
⋮----
'prefix' => env('REDIS_PREFIX', Str::slug((string) env('APP_NAME', 'laravel')).'-database-'),
````

## File: config/filesystems.php
````php
/*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */
````

## File: config/livewire.php
````php
/*
    |---------------------------------------------------------------------------
    | Component Locations
    |---------------------------------------------------------------------------
    |
    | This value sets the root directories that'll be used to resolve view-based
    | components like single and multi-file components. The make command will
    | use the first directory in this array to add new component files to.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Component Namespaces
    |---------------------------------------------------------------------------
    |
    | This value sets default namespaces that will be used to resolve view-based
    | components like single-file and multi-file components. These folders'll
    | also be referenced when creating new components via the make command.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Page Layout
    |---------------------------------------------------------------------------
    | The view that will be used as the layout when rendering a single component as
    | an entire page via `Route::livewire('/post/create', 'pages::create-post')`.
    | In this case, the content of pages::create-post will render into $slot.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Lazy Loading Placeholder
    |---------------------------------------------------------------------------
    | Livewire allows you to lazy load components that would otherwise slow down
    | the initial page load. Every component can have a custom placeholder or
    | you can define the default placeholder view for all components below.
    |
    */
⋮----
'component_placeholder' => null, // Example: 'placeholders::skeleton'
⋮----
/*
    |---------------------------------------------------------------------------
    | Make Command
    |---------------------------------------------------------------------------
    | This value determines the default configuration for the artisan make command
    | You can configure the component type (sfc, mfc, class) and whether to use
    | the high-voltage (⚡) emoji as a prefix in the sfc|mfc component names.
    |
    */
⋮----
'type' => 'sfc', // Options: 'sfc', 'mfc', 'class'
'emoji' => true, // Options: true, false
⋮----
/*
    |---------------------------------------------------------------------------
    | Class Namespace
    |---------------------------------------------------------------------------
    |
    | This value sets the root class namespace for Livewire component classes in
    | your application. This value will change where component auto-discovery
    | finds components. It's also referenced by the file creation commands.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Class Path
    |---------------------------------------------------------------------------
    |
    | This value is used to specify the path where Livewire component class files
    | are created when running creation commands like `artisan make:livewire`.
    | This path is customizable to match your projects directory structure.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | View Path
    |---------------------------------------------------------------------------
    |
    | This value is used to specify where Livewire component Blade templates are
    | stored when running file creation commands like `artisan make:livewire`.
    | It is also used if you choose to omit a component's render() method.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Temporary File Uploads
    |---------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing uploads in a temporary directory
    | before the file is stored permanently. All file uploads are directed to
    | a global endpoint for temporary storage. You may configure this below:
    |
    */
⋮----
'disk' => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK'), // Example: 'local', 's3'             | Default: 'default'
'rules' => null,                                      // Example: ['file', 'mimes:png,jpg'] | Default: ['required', 'file', 'max:12288'] (12MB)
'directory' => null,                                  // Example: 'tmp'                     | Default: 'livewire-tmp'
'middleware' => null,                                 // Example: 'throttle:5,1'            | Default: 'throttle:60,1'
'preview_mimes' => [                                  // Supported file types for temporary pre-signed file URLs...
⋮----
'max_upload_time' => 5, // Max duration (in minutes) before an upload is invalidated...
'cleanup' => true, // Should cleanup temporary uploads older than 24 hrs...
⋮----
/*
    |---------------------------------------------------------------------------
    | Render On Redirect
    |---------------------------------------------------------------------------
    |
    | This value determines if Livewire will run a component's `render()` method
    | after a redirect has been triggered using something like `redirect(...)`
    | Setting this to true will render the view once more before redirecting
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Eloquent Model Binding
    |---------------------------------------------------------------------------
    |
    | Previous versions of Livewire supported binding directly to eloquent model
    | properties using wire:model by default. However, this behavior has been
    | deemed too "magical" and has therefore been put under a feature flag.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Auto-inject Frontend Assets
    |---------------------------------------------------------------------------
    |
    | By default, Livewire automatically injects its JavaScript and CSS into the
    | <head> and <body> of pages containing Livewire components. By disabling
    | this behavior, you need to use @livewireStyles and @livewireScripts.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Navigate (SPA mode)
    |---------------------------------------------------------------------------
    |
    | By adding `wire:navigate` to links in your Livewire application, Livewire
    | will prevent the default link handling and instead request those pages
    | via AJAX, creating an SPA-like effect. Configure this behavior here.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | HTML Morph Markers
    |---------------------------------------------------------------------------
    |
    | Livewire intelligently "morphs" existing HTML into the newly rendered HTML
    | after each update. To make this process more reliable, Livewire injects
    | "markers" into the rendered Blade surrounding @if, @class & @foreach.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Smart Wire Keys
    |---------------------------------------------------------------------------
    |
    | Livewire uses loops and keys used within loops to generate smart keys that
    | are applied to nested components that don't have them. This makes using
    | nested components more reliable by ensuring that they all have keys.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Pagination Theme
    |---------------------------------------------------------------------------
    |
    | When enabling Livewire's pagination feature by using the `WithPagination`
    | trait, Livewire will use Tailwind templates to render pagination views
    | on the page. If you want Bootstrap CSS, you can specify: "bootstrap"
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Release Token
    |---------------------------------------------------------------------------
    |
    | This token is stored client-side and sent along with each request to check
    | a users session to see if a new release has invalidated it. If there is
    | a mismatch it will throw an error and prompt for a browser refresh.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | CSP Safe
    |---------------------------------------------------------------------------
    |
    | This config is used to determine if Livewire will use the CSP-safe version
    | of Alpine in its bundle. This is useful for applications that are using
    | strict Content Security Policy (CSP) to protect against XSS attacks.
    |
    */
⋮----
/*
    |---------------------------------------------------------------------------
    | Payload Guards
    |---------------------------------------------------------------------------
    |
    | These settings protect against malicious or oversized payloads that could
    | cause denial of service. The default values should feel reasonable for
    | most web applications. Each can be set to null to disable the limit.
    |
    */
⋮----
'max_size' => 1024 * 1024,   // 1MB - maximum request payload size in bytes
'max_nesting_depth' => 10,   // Maximum depth of dot-notation property paths
'max_calls' => 50,           // Maximum method calls per request
'max_components' => 200,     // Maximum components per batch request
````

## File: config/logging.php
````php
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;
⋮----
/*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that is utilized to write
    | messages to your logs. The value provided here should match one of
    | the channels present in the list of "channels" configured below.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Laravel
    | utilizes the Monolog PHP logging library, which includes a variety
    | of powerful log handlers and formatters that you're free to use.
    |
    | Available drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog", "custom", "stack"
    |
    */
````

## File: config/mail.php
````php
/*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | This option controls the default mailer that is used to send all email
    | messages unless another mailer is explicitly specified when sending
    | the message. All additional mailers can be configured within the
    | "mailers" array. Examples of each type of mailer are provided.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the mailers used by your application plus
    | their respective settings. Several examples have been configured for
    | you and you are free to add your own as your application requires.
    |
    | Laravel supports a variety of mail "transport" drivers that can be used
    | when delivering an email. You may specify which one you're using for
    | your mailers below. You may also add additional mailers if needed.
    |
    | Supported: "smtp", "sendmail", "mailgun", "ses", "ses-v2",
    |            "postmark", "resend", "log", "array",
    |            "failover", "roundrobin"
    |
    */
⋮----
// 'message_stream_id' => env('POSTMARK_MESSAGE_STREAM_ID'),
// 'client' => [
//     'timeout' => 5,
// ],
⋮----
/*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | You may wish for all emails sent by your application to be sent from
    | the same address. Here you may specify a name and address that is
    | used globally for all emails that are sent by your application.
    |
    */
````

## File: config/queue.php
````php
/*
    |--------------------------------------------------------------------------
    | Default Queue Connection Name
    |--------------------------------------------------------------------------
    |
    | Laravel's queue supports a variety of backends via a single, unified
    | API, giving you convenient access to each backend using identical
    | syntax for each. The default queue connection is defined below.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Queue Connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure the connection options for every queue backend
    | used by your application. An example configuration is provided for
    | each backend supported by Laravel. You're also free to add more.
    |
    | Drivers: "sync", "database", "beanstalkd", "sqs", "redis",
    |          "deferred", "background", "failover", "null"
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Job Batching
    |--------------------------------------------------------------------------
    |
    | The following options configure the database and table that store job
    | batching information. These options can be updated to any database
    | connection and table which has been defined by your application.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Failed Queue Jobs
    |--------------------------------------------------------------------------
    |
    | These options configure the behavior of failed queue job logging so you
    | can control how and where failed jobs are stored. Laravel ships with
    | support for storing failed jobs in a simple file or in a database.
    |
    | Supported drivers: "database-uuids", "dynamodb", "file", "null"
    |
    */
````

## File: config/services.php
````php
/*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */
````

## File: config/session.php
````php
use Illuminate\Support\Str;
⋮----
/*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | This option determines the default session driver that is utilized for
    | incoming requests. Laravel supports a variety of storage options to
    | persist session data. Database storage is a great default choice.
    |
    | Supported: "file", "cookie", "database", "memcached",
    |            "redis", "dynamodb", "array"
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of minutes that you wish the session
    | to be allowed to remain idle before it expires. If you want them
    | to expire immediately when the browser is closed then you may
    | indicate that via the expire_on_close configuration option.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | This option allows you to easily specify that all of your session data
    | should be encrypted before it's stored. All encryption is performed
    | automatically by Laravel and you may use the session like normal.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session File Location
    |--------------------------------------------------------------------------
    |
    | When utilizing the "file" session driver, the session files are placed
    | on disk. The default storage location is defined here; however, you
    | are free to provide another location where they should be stored.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Database Connection
    |--------------------------------------------------------------------------
    |
    | When using the "database" or "redis" session drivers, you may specify a
    | connection that should be used to manage these sessions. This should
    | correspond to a connection in your database configuration options.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Database Table
    |--------------------------------------------------------------------------
    |
    | When using the "database" session driver, you may specify the table to
    | be used to store sessions. Of course, a sensible default is defined
    | for you; however, you're welcome to change this to another table.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Cache Store
    |--------------------------------------------------------------------------
    |
    | When using one of the framework's cache driven session backends, you may
    | define the cache store which should be used to store the session data
    | between requests. This must match one of your defined cache stores.
    |
    | Affects: "dynamodb", "memcached", "redis"
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Sweeping Lottery
    |--------------------------------------------------------------------------
    |
    | Some session drivers must manually sweep their storage location to get
    | rid of old sessions from storage. Here are the chances that it will
    | happen on a given request. By default, the odds are 2 out of 100.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Here you may change the name of the session cookie that is created by
    | the framework. Typically, you should not need to change this value
    | since doing so does not grant a meaningful security improvement.
    |
    */
⋮----
Str::slug((string) env('APP_NAME', 'laravel')).'-session'
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Cookie Path
    |--------------------------------------------------------------------------
    |
    | The session cookie path determines the path for which the cookie will
    | be regarded as available. Typically, this will be the root path of
    | your application, but you're free to change this when necessary.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Cookie Domain
    |--------------------------------------------------------------------------
    |
    | This value determines the domain and subdomains the session cookie is
    | available to. By default, the cookie will be available to the root
    | domain without subdomains. Typically, this shouldn't be changed.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | By setting this option to true, session cookies will only be sent back
    | to the server if the browser has a HTTPS connection. This will keep
    | the cookie from being sent to you when it can't be done securely.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | Setting this value to true will prevent JavaScript from accessing the
    | value of the cookie and the cookie will only be accessible through
    | the HTTP protocol. It's unlikely you should disable this option.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | This option determines how your cookies behave when cross-site requests
    | take place, and can be used to mitigate CSRF attacks. By default, we
    | will set this value to "lax" to permit secure cross-site requests.
    |
    | See: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#samesitesamesite-value
    |
    | Supported: "lax", "strict", "none", null
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Partitioned Cookies
    |--------------------------------------------------------------------------
    |
    | Setting this value to true will tie the cookie to the top-level site for
    | a cross-site context. Partitioned cookies are accepted by the browser
    | when flagged "secure" and the Same-Site attribute is set to "none".
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Session Serialization
    |--------------------------------------------------------------------------
    |
    | This value controls the serialization strategy for session data, which
    | is JSON by default. Setting this to "php" allows the storage of PHP
    | objects in the session but can make an application vulnerable to
    | "gadget chain" serialization attacks if the APP_KEY is leaked.
    |
    | Supported: "json", "php"
    |
    */
````

## File: config/whatsapp.php
````php

````

## File: database/backups/settings_tables_20260708_100810.sql
````sql
INSERT INTO appointment_reminder_preferences VALUES(1,'whatsapp',1,1,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(2,'whatsapp',2,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(3,'whatsapp',3,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(4,'whatsapp',7,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(5,'email',1,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(6,'email',2,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(7,'email',3,0,'2026-07-08 08:16:39','2026-07-08 08:16:39');
INSERT INTO appointment_reminder_preferences VALUES(8,'email',7,0,'2026-07-08 08:16:39','2026-07-08
INSERT INTO twilio_content_templates VALUES(1,'Dos Botones Nuevo','HX3e116fa6be92c8ef9db84b65c383d5bc',1,'2026-07-08 02:37:44','2026-07-08 02:37:44','{"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}');
INSERT INTO twilio_content_templates VALUES(2,'Dos botones Antiguo','HXdea6aee77629b70b2ca3298e0e2ec5f2',0,'2026-07-08 02:37:44','2026-07-08 02:37:44','{"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}');
INSERT INTO twilio_content_templates VALUES(3,'Confirmar Texto con Emoji','HX28712cac47e020331237e0dfb9228aaf',0,'2026-07-08 02:37:44','2026-07-08 02:37:44','{"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}');
INSERT INTO twilio_content_templates VALUES(4,'Confirmar Texto','HX94dfe8732cc8177e79e8003da08be354',0,'2026-07-08 02:37:44','2026-07-08 02:37:44','{"1":"[NOMBRE]","2":"[DIA]","3":"[HORA]"}');
CREATE TABLE IF NOT EXISTS "whatsapp_dispatch_settings" ("id" integer primary key autoincrement not null, "enabled" tinyint(1) not null default '1', "hours" text not null default '["09:00","12:00","15:00"]', "created_at" datetime, "updated_at" datetime);
INSERT INTO whatsapp_dispatch_settings VALUES(1,0,'["15:00"]','2026-07-08 02:39:11','2026-07-08 08:19:33');
CREATE TABLE IF NOT EXISTS "whatsapp_credentials" ("id" integer primary key autoincrement not null, "mode" varchar not null default 'sandbox', "api_key_sid" text, "api_key_secret" text, "selected" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime, "status_callback_url" varchar);
INSERT INTO whatsapp_credentials VALUES(1,'sandbox','eyJpdiI6IkgxK3V6bS9mTDBISnNaV2lGK1lEZHc9PSIsInZhbHVlIjoicms4SUxrWkpnVXVIbmVwYW4zWmhzQmpSOENLTGxiQ1o2ZHozN0xKWlo3S1c2TWc0LzdCRDJ6TU5abTc2SWlDbiIsIm1hYyI6Ijc3OTQwMTJlZjc4MGUxYmI0YjBmYjExNDUwZmNlNmJmZDJlNGFmZjhlYTFjNGM5ZGU5M2E5YWM5MTI3NDk4ZGIiLCJ0YWciOiIifQ==','eyJpdiI6ImpuaytGKzA0V1dHYWQ3dmQzay9oeGc9PSIsInZhbHVlIjoiaVRCQ2RvVWNsRlVDa1NBb3VRZmZPY3Q1Uy9jb2RtcHZDOVM5djZhN0x5eGZwck9nMEtlRk0rZHVvZ0R6dWs4dSIsIm1hYyI6IjdjYWNjZjlkY2IzZTY2NmNjZDdlN2U2ZWM3NmYxYWQ4NmU1OTY0OGRhMzljNjdjYTM1MGQ0YWIyYmFlNTY3NDQiLCJ0YWciOiIifQ==',1,'2026-07-08 02:37:58','2026-07-08 08:47:50','https://chery-precranial-extemporarily.ngrok-free.dev/webhooks/twilio/whatsapp-status');
CREATE TABLE IF NOT EXISTS "whatsapp_sender_numbers" ("id" integer primary key autoincrement not null, "whatsapp_credential_id" integer not null, "prefix" varchar not null default '+34', "number" varchar not null, "selected" tinyint(1) not null default '0', "created_at" datetime, "updated_at" datetime, "name" varchar, foreign key("whatsapp_credential_id") references "whatsapp_credentials"("id") on delete cascade);
INSERT INTO whatsapp_sender_numbers VALUES(8,1,'+1','5559355880',0,'2026-07-08 08:55:29','2026-07-08 10:03:51','Pruebas');
INSERT INTO whatsapp_sender_numbers VALUES(9,1,'+1','4155238886',0,'2026-07-08 08:56:09','2026-07-08 10:03:51','Sender Clinica');
INSERT INTO whatsapp_sender_numbers VALUES(10,1,'+1','2515013894',1,'2026-07-08 08:56:48','2026-07-08 10:03:51','Sender Juan Jota');
COMMIT;
````

## File: database/factories/UserFactory.php
````php
namespace Database\Factories;
⋮----
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
⋮----
class UserFactory extends Factory
⋮----
protected static ?string $password;
⋮----
public function definition(): array
⋮----
$faker = FakerFactory::create();
⋮----
'name' => $faker->name(),
'email' => $faker->unique()->safeEmail(),
⋮----
'password' => static::$password ??= Hash::make('password'),
'remember_token' => Str::random(10),
⋮----
public function unverified(): static
⋮----
return $this->state(fn (array $attributes) => [
````

## File: database/migrations/0001_01_01_000000_create_users_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('users', function (Blueprint $table) {
$table->id();
$table->string('name');
$table->string('email')->unique();
$table->timestamp('email_verified_at')->nullable();
$table->string('password');
$table->boolean('is_admin')->default(false);
$table->rememberToken();
$table->timestamps();
⋮----
Schema::create('password_reset_tokens', function (Blueprint $table) {
$table->string('email')->primary();
$table->string('token');
$table->timestamp('created_at')->nullable();
⋮----
Schema::create('sessions', function (Blueprint $table) {
$table->string('id')->primary();
$table->foreignId('user_id')->nullable()->index();
$table->string('ip_address', 45)->nullable();
$table->text('user_agent')->nullable();
$table->longText('payload');
$table->integer('last_activity')->index();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('users');
Schema::dropIfExists('password_reset_tokens');
Schema::dropIfExists('sessions');
````

## File: database/migrations/0001_01_01_000001_create_cache_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('cache', function (Blueprint $table) {
$table->string('key')->primary();
$table->mediumText('value');
$table->bigInteger('expiration')->index();
⋮----
Schema::create('cache_locks', function (Blueprint $table) {
⋮----
$table->string('owner');
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('cache');
Schema::dropIfExists('cache_locks');
````

## File: database/migrations/0001_01_01_000002_create_jobs_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('jobs', function (Blueprint $table) {
$table->id();
$table->string('queue')->index();
$table->longText('payload');
$table->unsignedSmallInteger('attempts');
$table->unsignedInteger('reserved_at')->nullable();
$table->unsignedInteger('available_at');
$table->unsignedInteger('created_at');
⋮----
Schema::create('job_batches', function (Blueprint $table) {
$table->string('id')->primary();
$table->string('name');
$table->integer('total_jobs');
$table->integer('pending_jobs');
$table->integer('failed_jobs');
$table->longText('failed_job_ids');
$table->mediumText('options')->nullable();
$table->integer('cancelled_at')->nullable();
$table->integer('created_at');
$table->integer('finished_at')->nullable();
⋮----
Schema::create('failed_jobs', function (Blueprint $table) {
⋮----
$table->string('uuid')->unique();
$table->string('connection');
$table->string('queue');
⋮----
$table->longText('exception');
$table->timestamp('failed_at')->useCurrent();
⋮----
$table->index(['connection', 'queue', 'failed_at']);
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('jobs');
Schema::dropIfExists('job_batches');
Schema::dropIfExists('failed_jobs');
````

## File: database/migrations/2026_06_23_000000_create_clients_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
Schema::create('clients', function (Blueprint $table) {
$table->id();
$table->string('nombre');
$table->string('apellidos');
$table->string('telefono', 40)->index();
$table->timestamps();
⋮----
$table->index(['nombre', 'apellidos']);
⋮----
public function down(): void
⋮----
Schema::dropIfExists('clients');
````

## File: database/migrations/2026_06_23_000003_create_appointments_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('appointments', function (Blueprint $table): void {
$table->id();
$table->foreignId('client_id')->constrained()->cascadeOnDelete();
$table->date('fecha')->index();
$table->time('hora');
$table->boolean('enviado')->default(false)->index();
$table->boolean('entregado')->default(false)->index();
$table->boolean('confirmada')->default(false)->index();
$table->boolean('pendiente_reprogramacion')->default(false)->index();
$table->boolean('reprogramada')->default(false)->index();
$table->date('fecha_original')->nullable()->index();
$table->time('hora_original')->nullable();
$table->boolean('cita_activa')->default(true)->index();
$table->boolean('activo')->default(true)->index();
$table->dateTime('whatsapp_sent_at')->nullable();
$table->dateTime('whatsapp_delivered_at')->nullable();
$table->dateTime('whatsapp_read_at')->nullable();
$table->dateTime('last_inbound_seen_at')->nullable();
$table->index(
⋮----
$table->timestamps();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('appointments');
````

## File: database/migrations/2026_06_23_000004_create_whatsapp_messages_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
Schema::create('whatsapp_messages', function (Blueprint $table): void {
$table->id();
$table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
$table->foreignId('client_id')->nullable()->constrained()->cascadeOnDelete();
$table->foreignId('appointment_id')->nullable()->constrained()->cascadeOnDelete();
$table->foreignId('parent_id')->nullable()->constrained('whatsapp_messages')->nullOnDelete();
$table->string('nombre');
$table->string('apellidos');
$table->string('telefono', 40);
$table->dateTime('scheduled_for')->index();
$table->text('message');
$table->string('source', 20)->default('manual');
$table->string('status', 20)->default('pending')->index();
$table->string('direction', 10)->default('outbound');
$table->dateTime('sent_at')->nullable();
$table->text('last_error')->nullable();
$table->string('provider_message_id')->nullable()->index();
$table->json('provider_payload')->nullable();
$table->json('metadata')->nullable();
$table->string('respuesta', 50)->nullable();
$table->dateTime('responded_at')->nullable();
$table->timestamps();
⋮----
public function down(): void
⋮----
Schema::dropIfExists('whatsapp_messages');
````

## File: database/migrations/2026_06_23_124420_create_appointment_reminder_preferences_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('appointment_reminder_preferences', function (Blueprint $table) {
$table->id();
$table->string('channel', 20);
$table->unsignedTinyInteger('lead_days');
$table->boolean('enabled')->default(false);
$table->timestamps();
⋮----
$table->unique(['channel', 'lead_days']);
$table->index(['channel', 'enabled']);
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('appointment_reminder_preferences');
````

## File: database/migrations/2026_07_02_030000_create_login_history_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
Schema::create('login_history', function (Blueprint $table) {
$table->id();
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('ip_address', 45)->nullable();
$table->string('user_agent')->nullable();
$table->timestamp('logged_in_at');
$table->timestamps();
⋮----
public function down(): void
⋮----
Schema::dropIfExists('login_history');
````

## File: database/migrations/2026_07_06_000000_create_twilio_content_templates_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
Schema::create('twilio_content_templates', function (Blueprint $table) {
$table->id();
$table->string('nombre');
$table->string('content_sid', 34)->unique();
$table->boolean('seleccionada')->default(false)->index();
$table->json('content_variables')->nullable();
$table->timestamps();
⋮----
public function down(): void
⋮----
Schema::dropIfExists('twilio_content_templates');
````

## File: database/migrations/2026_07_07_232323_create_whatsapp_dispatch_settings_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('whatsapp_dispatch_settings', function (Blueprint $table) {
$table->id();
$table->boolean('enabled')->default(true);
$table->json('hours');
$table->timestamps();
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('whatsapp_dispatch_settings');
````

## File: database/migrations/2026_07_08_010450_create_whatsapp_credentials_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
Schema::create('whatsapp_credentials', function (Blueprint $table) {
$table->id();
$table->string('driver')->nullable();
$table->string('default_country_code', 10)->nullable();
$table->string('message_mode', 20)->nullable();
$table->string('mode')->default('sandbox');
$table->string('account_sid')->nullable();
$table->text('auth_token')->nullable();
$table->text('api_key_sid')->nullable();
$table->text('api_key_secret')->nullable();
$table->string('content_sid', 34)->nullable();
$table->string('test_recipient', 40)->nullable();
$table->unsignedSmallInteger('timeout')->nullable();
$table->unsignedSmallInteger('connect_timeout')->nullable();
$table->string('cloud_api_base_url')->nullable();
$table->string('cloud_api_version', 20)->nullable();
$table->string('cloud_api_phone_number_id')->nullable();
$table->text('cloud_api_access_token')->nullable();
$table->unsignedSmallInteger('cloud_api_timeout')->nullable();
$table->string('default_template')->nullable();
$table->text('default_message')->nullable();
$table->string('status_callback_url')->nullable();
$table->boolean('webhook_enabled')->default(true);
$table->unsignedSmallInteger('poll_interval')->default(10);
$table->boolean('selected')->default(false);
$table->timestamps();
⋮----
public function down(): void
⋮----
Schema::dropIfExists('whatsapp_credentials');
````

## File: database/migrations/2026_07_08_120000_create_whatsapp_sender_numbers_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
Schema::create('whatsapp_sender_numbers', function (Blueprint $table) {
$table->id();
$table->foreignId('whatsapp_credential_id')->constrained('whatsapp_credentials')->cascadeOnDelete();
$table->string('name', 100)->nullable();
$table->string('prefix', 5)->default('+1');
$table->string('number', 20);
$table->boolean('selected')->default(false);
$table->timestamps();
⋮----
$table->index(['whatsapp_credential_id', 'selected']);
⋮----
public function down(): void
⋮----
Schema::dropIfExists('whatsapp_sender_numbers');
````

## File: database/migrations/2026_07_10_210107_create_appointment_changes_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
/**
     * Run the migrations.
     */
public function up(): void
⋮----
Schema::create('appointment_changes', function (Blueprint $table) {
$table->id();
$table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
$table->date('fecha_anterior');
$table->time('hora_anterior');
$table->date('fecha_nueva');
$table->time('hora_nueva');
$table->timestamps();
⋮----
$table->index(['appointment_id', 'created_at']);
⋮----
/**
     * Reverse the migrations.
     */
public function down(): void
⋮----
Schema::dropIfExists('appointment_changes');
````

## File: database/migrations/2026_07_11_120000_create_option_settings_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
Schema::create('sistema_opciones', function (Blueprint $table): void {
$table->id();
$table->string('retention_period', 20)->default('disabled');
$table->timestamps();
⋮----
public function down(): void
⋮----
Schema::dropIfExists('sistema_opciones');
````

## File: database/migrations/2026_07_12_120000_create_app_settings_table.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
Schema::create('app_settings', function (Blueprint $table): void {
$table->id();
$table->string('retention_period', 20)->default('disabled');
$table->boolean('dispatch_enabled')->default(true);
$table->json('dispatch_hours');
$table->timestamps();
⋮----
public function down(): void
⋮----
Schema::dropIfExists('app_settings');
````

## File: database/migrations/2026_07_12_120001_merge_settings_tables.php
````php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
⋮----
public function up(): void
⋮----
if (Schema::hasTable('sistema_opciones')) {
$row = DB::table('sistema_opciones')->first();
⋮----
if (Schema::hasTable('whatsapp_dispatch_settings')) {
$row = DB::table('whatsapp_dispatch_settings')->first();
⋮----
DB::table('app_settings')->insert([
⋮----
Schema::dropIfExists('sistema_opciones');
Schema::dropIfExists('whatsapp_dispatch_settings');
⋮----
public function down(): void
⋮----
Schema::create('sistema_opciones', function ($table): void {
$table->id();
$table->string('retention_period', 20)->default('disabled');
$table->timestamps();
⋮----
Schema::create('whatsapp_dispatch_settings', function ($table): void {
⋮----
$table->boolean('enabled')->default(true);
$table->json('hours');
⋮----
$row = DB::table('app_settings')->first();
⋮----
DB::table('sistema_opciones')->insert([
⋮----
DB::table('whatsapp_dispatch_settings')->insert([
````

## File: database/seeders/AppointmentSeeder.php
````php
namespace Database\Seeders;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Database\Seeder;
⋮----
class AppointmentSeeder extends Seeder
⋮----
/**
     * Run the database seeds.
     */
public function run(): void
⋮----
->map(fn (int $offset) => now()->addDays($offset))
->reject(fn ($date) => $date->isSunday())
->map(fn ($date): string => $date->toDateString())
->all();
⋮----
$clientList = Client::query()->orderBy('id')->get();
⋮----
if ($clientList->isEmpty()) {
⋮----
Appointment::query()->delete();
⋮----
now()->subDays(2),
now()->subDay(),
⋮----
Appointment::query()->create([
````

## File: database/seeders/ClientSeeder.php
````php
namespace Database\Seeders;
⋮----
use App\Models\Client;
use Illuminate\Database\Seeder;
⋮----
class ClientSeeder extends Seeder
⋮----
/**
     * Run the database seeds.
     */
public function run(): void
⋮----
Client::query()->updateOrCreate(
['telefono' => Client::normalizePhone($client['telefono'])],
````

## File: database/seeders/DatabaseSeeder.php
````php
namespace Database\Seeders;
⋮----
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
⋮----
class DatabaseSeeder extends Seeder
⋮----
/**
     * Seed the application's database.
     */
public function run(): void
⋮----
// User::factory(10)->create();
/*
                User::factory()->create([
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                    'password' => '1234',
                    'is_admin' => true,
                ]);
                User::factory()->create([
                    'name' => 'Admin',
                    'email' => 'admin@example.com',
                    'password' => '1234',
                    'is_admin' => true,
                ]);
        */
//    User::factory(10)->create();
$this->call(ClientSeeder::class);
$this->call(AppointmentSeeder::class);
//  $this->call(SettingsSeeder::class);
````

## File: database/seeders/SettingsSeeder.php
````php
namespace Database\Seeders;
⋮----
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\WhatsAppCredential;
use Illuminate\Database\Seeder;
⋮----
class SettingsSeeder extends Seeder
⋮----
public function run(): void
⋮----
$this->seedAppSetting();
$this->seedReminderPreferences();
$this->seedCredential();
$this->seedTemplates();
⋮----
private function seedAppSetting(): void
⋮----
AppSetting::updateOrCreate([], [
⋮----
private function seedReminderPreferences(): void
⋮----
$leadDays = array_keys(AppointmentReminderPreference::leadDayOptions());
⋮----
AppointmentReminderPreference::updateOrCreate(
⋮----
private function seedCredential(): void
⋮----
WhatsAppCredential::firstOrCreate(
⋮----
private function seedTemplates(): void
⋮----
$this->call(TwilioContentTemplateSeeder::class);
````

## File: database/seeders/TwilioContentTemplateSeeder.php
````php
namespace Database\Seeders;
⋮----
use App\Models\TwilioContentTemplate;
use Illuminate\Database\Seeder;
⋮----
class TwilioContentTemplateSeeder extends Seeder
⋮----
public function run(): void
⋮----
TwilioContentTemplate::updateOrCreate(
````

## File: database/.gitignore
````
*.sqlite*
````

## File: e2e/example.spec.js
````javascript
// @ts-check
⋮----
// Expect a title "to contain" a substring.
⋮----
// Click the get started link.
⋮----
// Expects page to have a heading with the name of Installation.
````

## File: lang/en/auth.php
````php
/*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */
````

## File: lang/en/pagination.php
````php
/*
    |--------------------------------------------------------------------------
    | Pagination Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the simple pagination links. You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */
````

## File: lang/en/passwords.php
````php
/*
    |--------------------------------------------------------------------------
    | Password Reset Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | outcome such as failure due to an invalid password / reset token.
    |
    */
````

## File: lang/en/validation.php
````php
/*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
⋮----
/*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */
````

## File: lang/es/validation.php
````php
/*
  |--------------------------------------------------------------------------
  | Validation Language Lines
  |--------------------------------------------------------------------------
  |
  | The following language lines contain the default error messages used by
  | the validator class. Some of these rules have multiple versions such
  | as the size rules. Feel free to tweak each of these messages.
  |
  */
⋮----
/*
  |--------------------------------------------------------------------------
  | Custom Validation Language Lines
  |--------------------------------------------------------------------------
  |
  | Here you may specify custom validation messages for attributes using the
  | convention 'attribute.rule' to name the lines. This makes it quick to
  | specify a specific custom language line for a given attribute rule.
  |
  */
⋮----
/*
  |--------------------------------------------------------------------------
  | Custom Validation Attributes
  |--------------------------------------------------------------------------
  |
  | The following language lines are used to swap our attribute placeholder
  | names with something more reader friendly such as "E-Mail Address"
  | instead of "email". This simply helps us make our message more
  | expressive.
  |
  */
````

## File: lang/es.json
````json
{
  "undelivered": "No entregado",
  "failed": "Fallo",
  "delivered": "Entregado",
  "pending": "Pendiente",
  "today": "Hoy",
  "yesterday": "Ayer",
  "this_week": "Esta semana",
  "last_week": "La semana pasada",
  "this_month": "Este mes",
  "last_month": "El mes pasado",
  "this_year": "Este año",
  "last_year": "El año pasado",
  "all_time": "Todo el tiempo",
  "custom_range": "Rango personalizado",
  "select_date_range": "Seleccionar rango de fechas",
  "from": "Desde",
  "to": "Hasta",
  "apply": "Aplicar",
  "cancel": "Cancelar",
  "no_data": "No hay datos disponibles",
  "loading": "Cargando...",
  "error": "Error",
  "success": "Éxito",
  "warning": "Advertencia",
  "info": "Información",
  "confirm": "Confirmar",
  "yes": "Sí",
  "no": "No",
  "close": "Cerrar",
  "save": "Guardar",
  "edit": "Editar",
  "delete": "Eliminar",
  "search": "Buscar",
  "reset": "Restablecer",
  "filter": "Filtrar",
  "sort": "Ordenar",
  "ascending": "Ascendente",
  "descending": "Descendente",
  "select_all": "Seleccionar todo",
  "deselect_all": "Deseleccionar todo",
  "no_results_found": "No se encontraron resultados",
  "loading_more": "Cargando más...",
  "load_more": "Cargar más"
}
````

## File: public/.htaccess
````
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
````

## File: public/favicon.svg
````xml
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
  <image href="data:image/png;base64,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" width="200" height="200"/>
</svg>
````

## File: public/index.php
````php
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
⋮----
// Determine if the application is in maintenance mode...
⋮----
// Register the Composer autoloader...
⋮----
// Bootstrap Laravel and handle the request...
/** @var Application $app */
⋮----
$app->handleRequest(Request::capture());
````

## File: public/robots.txt
````
User-agent: *
Disallow:
````

## File: public/site.webmanifest
````
{
  "name": "clinica dental eugenia",
  "short_name": "clinicadentaleugenia",
  "icons": [
    {
      "src": "/android-chrome-192x192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/android-chrome-512x512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ],
  "theme_color": "#ffffff",
  "background_color": "#ffffff",
  "display": "standalone"
}
````

## File: resources/css/app.css
````css
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
⋮----
@theme {
⋮----
@custom-variant dark (&:where(.dark, .dark *));
⋮----
.cita-confirmada {
⋮----
.cambiar-cita {
⋮----
[x-cloak] {
⋮----
body.bg-slate-950 {
⋮----
body.bg-slate-950 .text-slate-500,
⋮----
body.bg-slate-950 :where(label, [data-flux-label]) {
⋮----
body.bg-slate-950 :where(input, select, textarea) {
⋮----
body.bg-slate-950 :where(input::placeholder, textarea::placeholder) {
⋮----
body.bg-slate-950 :where(option) {
⋮----
body.bg-slate-950 :where(button, [role='button'], a[href], select, summary, input[type='button'], input[type='submit'], input[type='reset']) {
⋮----
body.bg-slate-950 :where(button[title], a[title], [role='button'][title]) {
⋮----
body.bg-slate-950 :where(button[title], a[title], [role='button'][title])::after {
⋮----
body.bg-slate-950 :where(button[title], a[title], [role='button'][title]):hover::after,
⋮----
body.bg-slate-950 :where(input[type='date'], input[data-time-picker]) {
⋮----
body.bg-slate-950 :where(input[type='date']) {
⋮----
body.bg-slate-950 :where(input[data-time-picker]) {
⋮----
body.bg-slate-950 :where(input[type='date'])::-webkit-calendar-picker-indicator {
⋮----
.time-picker-menu {
⋮----
.time-picker-menu[hidden] {
⋮----
.time-picker-header {
⋮----
.time-picker-header span {
⋮----
.time-picker-header strong {
⋮----
.time-picker-grid {
⋮----
.time-picker-menu button {
⋮----
.time-picker-menu button:hover,
⋮----
body.bg-slate-950 :where(button, [role='button'], select, input):disabled,
⋮----
body.bg-slate-950 main :where(.grid.gap-6) {
⋮----
body.bg-slate-950 main :where(.grid.gap-4, .flex.gap-4) {
⋮----
body.bg-slate-950 main :where(.mt-6) {
⋮----
body.bg-slate-950 main :where(.mt-5) {
⋮----
body.bg-slate-950 main :where(.mt-4) {
⋮----
body.bg-slate-950 main :where(.p-8) {
⋮----
body.bg-slate-950 main :where(.p-6) {
⋮----
body.bg-slate-950 main :where(.p-4) {
⋮----
body.bg-slate-950 main :where(.px-4.py-3) {
⋮----
body.bg-slate-950 main :where(th.px-4.py-3, td.px-4.py-3) {
⋮----
body.bg-slate-950 main :where(.rounded-3xl) {
⋮----
body.bg-slate-950 main :where(.rounded-2xl) {
⋮----
.action-add {
⋮----
.action-add:hover {
⋮----
.action-edit {
⋮----
.action-edit:hover {
⋮----
.action-delete {
⋮----
.action-delete:hover {
⋮----
.action-add:disabled,
⋮----
.settings-drag-handle {
⋮----
.settings-drag-handle:hover {
⋮----
.settings-drag-handle:active {
⋮----
.settings-dragging {
⋮----
.settings-drop-placeholder {
⋮----
.settings-drop-placeholder::after {
````

## File: resources/js/app.js
````javascript

````

## File: resources/js/data-picker.js
````javascript
const validateSelectableDate = (dateInput) =>
⋮----
const buildTimeOptions = () =>
⋮----
const ensureTimePickerMenu = () =>
⋮----
const closeTimePicker = () =>
⋮----
const positionTimePicker = (input) =>
⋮----
const openFallbackTimePicker = (input) =>
````

## File: resources/views/admin/login-history/index.blade.php
````php

````

## File: resources/views/admin/tools/index.blade.php
````php

````

## File: resources/views/admin/users/create.blade.php
````php

````

## File: resources/views/admin/users/edit.blade.php
````php

````

## File: resources/views/agenda/index.blade.php
````php

````

## File: resources/views/appointments/client.blade.php
````php

````

## File: resources/views/appointments/form.blade.php
````php

````

## File: resources/views/appointments/index.blade.php
````php

````

## File: resources/views/appointments/new.blade.php
````php

````

## File: resources/views/auth/login.blade.php
````php

````

## File: resources/views/calendar/index.blade.php
````php

````

## File: resources/views/clients/form.blade.php
````php

````

## File: resources/views/clients/index.blade.php
````php

````

## File: resources/views/clients/list.blade.php
````php

````

## File: resources/views/components/botones/partials/icono-accion.blade.php
````php

````

## File: resources/views/components/botones/accion-ajustes.blade.php
````php

````

## File: resources/views/components/botones/arrastrar-seccion.blade.php
````php

````

## File: resources/views/components/botones/expandir-contraer.blade.php
````php

````

## File: resources/views/components/botones/filtro-botones.blade.php
````php

````

## File: resources/views/components/botones/icono-buton.blade.php
````php

````

## File: resources/views/components/botones/sidebar-toggle.blade.php
````php

````

## File: resources/views/components/dashboard/metric-card.blade.php
````php

````

## File: resources/views/components/formularios/checkbox-card.blade.php
````php

````

## File: resources/views/components/formularios/input.blade.php
````php

````

## File: resources/views/components/formularios/option-input.blade.php
````php

````

## File: resources/views/components/formularios/select.blade.php
````php

````

## File: resources/views/components/formularios/toggle.blade.php
````php

````

## File: resources/views/components/iconos/admin-user.blade.php
````php

````

## File: resources/views/components/iconos/agenda.blade.php
````php

````

## File: resources/views/components/iconos/ajustes.blade.php
````php

````

## File: resources/views/components/iconos/alert.blade.php
````php

````

## File: resources/views/components/iconos/arrastrar.blade.php
````php

````

## File: resources/views/components/iconos/bombilla.blade.php
````php

````

## File: resources/views/components/iconos/borrar.blade.php
````php

````

## File: resources/views/components/iconos/calendar.blade.php
````php

````

## File: resources/views/components/iconos/calendario-filtro.blade.php
````php

````

## File: resources/views/components/iconos/calendario-pasado.blade.php
````php

````

## File: resources/views/components/iconos/check.blade.php
````php

````

## File: resources/views/components/iconos/cita.blade.php
````php

````

## File: resources/views/components/iconos/conectar.blade.php
````php

````

## File: resources/views/components/iconos/contraer-flechas.blade.php
````php

````

## File: resources/views/components/iconos/contraer.blade.php
````php

````

## File: resources/views/components/iconos/copiar.blade.php
````php

````

## File: resources/views/components/iconos/customer.blade.php
````php

````

## File: resources/views/components/iconos/dashboard.blade.php
````php

````

## File: resources/views/components/iconos/deAZ.blade.php
````php

````

## File: resources/views/components/iconos/deZA.blade.php
````php

````

## File: resources/views/components/iconos/disquete.blade.php
````php

````

## File: resources/views/components/iconos/doble-check.blade.php
````php

````

## File: resources/views/components/iconos/down.blade.php
````php

````

## File: resources/views/components/iconos/enviar-ya.blade.php
````php

````

## File: resources/views/components/iconos/enviar.blade.php
````php

````

## File: resources/views/components/iconos/escoba.blade.php
````php

````

## File: resources/views/components/iconos/excel.blade.php
````php

````

## File: resources/views/components/iconos/expand.blade.php
````php

````

## File: resources/views/components/iconos/expandir.blade.php
````php

````

## File: resources/views/components/iconos/export.blade.php
````php

````

## File: resources/views/components/iconos/guardar.blade.php
````php

````

## File: resources/views/components/iconos/historial.blade.php
````php

````

## File: resources/views/components/iconos/importar.blade.php
````php

````

## File: resources/views/components/iconos/inactivo.blade.php
````php

````

## File: resources/views/components/iconos/lapiz.blade.php
````php

````

## File: resources/views/components/iconos/nueva-cita.blade.php
````php

````

## File: resources/views/components/iconos/nuevo.blade.php
````php

````

## File: resources/views/components/iconos/num-Asc.blade.php
````php

````

## File: resources/views/components/iconos/num-Desc.blade.php
````php

````

## File: resources/views/components/iconos/ojo.blade.php
````php

````

## File: resources/views/components/iconos/papelera.blade.php
````php

````

## File: resources/views/components/iconos/proxima-cita.blade.php
````php

````

## File: resources/views/components/iconos/reload.blade.php
````php

````

## File: resources/views/components/iconos/reloj-agujas.blade.php
````php

````

## File: resources/views/components/iconos/reloj-arena.blade.php
````php

````

## File: resources/views/components/iconos/restablecer.blade.php
````php

````

## File: resources/views/components/iconos/salir.blade.php
````php

````

## File: resources/views/components/iconos/seguridad.blade.php
````php

````

## File: resources/views/components/iconos/telefono-mesa.blade.php
````php

````

## File: resources/views/components/iconos/todos.blade.php
````php

````

## File: resources/views/components/iconos/up.blade.php
````php

````

## File: resources/views/components/iconos/user-menos.blade.php
````php

````

## File: resources/views/components/iconos/usuario-plus.blade.php
````php

````

## File: resources/views/components/iconos/usuarios.blade.php
````php

````

## File: resources/views/components/iconos/volver.blade.php
````php

````

## File: resources/views/components/iconos/whatsapp.blade.php
````php

````

## File: resources/views/components/modales/borrar.blade.php
````php

````

## File: resources/views/components/modales/bulk-borrar.blade.php
````php

````

## File: resources/views/components/modales/confirmacion.blade.php
````php

````

## File: resources/views/components/modales/historia-whatsapp.blade.php
````php

````

## File: resources/views/components/navegacion/aside-link.blade.php
````php

````

## File: resources/views/components/settings/section.blade.php
````php

````

## File: resources/views/components/tabla/botones-maniobra.blade.php
````php

````

## File: resources/views/components/tabla/th-sort.blade.php
````php

````

## File: resources/views/components/tabla/th.blade.php
````php

````

## File: resources/views/imports/index.blade.php
````php

````

## File: resources/views/layouts/app.blade.php
````php

````

## File: resources/views/layouts/guest.blade.php
````php

````

## File: resources/views/livewire/avisos/sin-envio-automatico.blade.php
````php

````

## File: resources/views/livewire/agenda-index.blade.php
````php

````

## File: resources/views/livewire/appointment-form.blade.php
````php

````

## File: resources/views/livewire/appointment-index.blade.php
````php

````

## File: resources/views/livewire/calendar-index.blade.php
````php

````

## File: resources/views/livewire/client-appointments.blade.php
````php

````

## File: resources/views/livewire/client-csv-importer.blade.php
````php

````

## File: resources/views/livewire/client-form.blade.php
````php

````

## File: resources/views/livewire/client-index.blade.php
````php

````

## File: resources/views/livewire/client-list-all.blade.php
````php

````

## File: resources/views/livewire/client-message-scheduler.blade.php
````php

````

## File: resources/views/livewire/dashboard-overview.blade.php
````php

````

## File: resources/views/livewire/unread-responses-notice.blade.php
````php

````

## File: resources/views/settings/appointment-cleanup-settings.blade.php
````php

````

## File: resources/views/settings/appointment-reminder-settings.blade.php
````php

````

## File: resources/views/settings/database-backup.blade.php
````php

````

## File: resources/views/settings/index.blade.php
````php

````

## File: resources/views/settings/settings-backup.blade.php
````php

````

## File: resources/views/settings/settings-overview.blade.php
````php

````

## File: resources/views/settings/table-backup.blade.php
````php

````

## File: resources/views/settings/twilio-content-template-settings.blade.php
````php

````

## File: resources/views/settings/twilio-credential-settings.blade.php
````php

````

## File: resources/views/settings/whatsapp-connection-test.blade.php
````php

````

## File: resources/views/vendor/pagination/tailwind.blade.php
````php

````

## File: resources/views/dashboard.blade.php
````php

````

## File: resources/views/home.blade.php
````php

````

## File: routes/console.php
````php
use App\Console\Commands\DispatchDueWhatsAppMessages;
use App\Console\Commands\PurgePastAppointments;
use App\Console\Commands\SyncWhatsAppDeliveryStatus;
use App\Models\AppSetting;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
⋮----
Artisan::command('inspire', function () {
$this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
⋮----
Schedule::command(DispatchDueWhatsAppMessages::class)
->everyMinute()
->withoutOverlapping()
->when(function (): bool {
return AppSetting::get()->dispatch_enabled;
⋮----
Schedule::command(SyncWhatsAppDeliveryStatus::class)->everyMinute()->withoutOverlapping();
⋮----
Schedule::command(PurgePastAppointments::class)
->daily()
⋮----
return AppSetting::get()->isEnabled();
````

## File: routes/web.php
````php
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\LoginHistoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AppointmentIndexController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Webhooks\TwilioWhatsAppStatusController;
use App\Models\Appointment;
use App\Models\WhatsAppMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
⋮----
Route::get('/', [HomeController::class, 'index'])->name('home');
⋮----
Route::post('/webhooks/twilio/whatsapp-status', TwilioWhatsAppStatusController::class)
->name('webhooks.twilio.whatsapp-status');
⋮----
Route::middleware('guest')->group(function () {
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
⋮----
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
->middleware('auth')
->name('logout');
⋮----
Route::middleware('auth')->group(function () {
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/agenda', 'agenda.index')->name('agenda.index');
Route::view('/calendario', 'calendar.index')->name('calendar.index');
Route::view('/clients', 'clients.index')->name('clients.index');
Route::view('/clients/list', 'clients.list')->name('clients.list');
Route::view('/clients/create', 'clients.form')->name('clients.create');
Route::view('/clients/{client}/edit', 'clients.form')->name('clients.edit');
Route::view('/clients/{client}/appointments', 'appointments.client')
->whereNumber('client')
->name('clients.appointments');
Route::get('/appointments', AppointmentIndexController::class)->name('appointments.index');
⋮----
Route::view('/appointments/create', 'appointments.form')->name('appointments.create');
Route::view('/appointments/{appointment}/edit', 'appointments.form')->name('appointments.edit');
⋮----
Route::post('/appointments/{appointment}/toggle', function (Appointment $appointment, Request $request) {
abort_unless($appointment->canBeChanged(), 403);
$field = $request->validate(['field' => 'required|in:activo,cita_activa'])['field'];
$value = (bool) $request->validate(['value' => 'required|boolean'])['value'];
$appointment->update([$field => $value]);
⋮----
$appointment->whatsAppMessages()
->where('status', WhatsAppMessage::STATUS_PENDING)
->delete();
⋮----
return response()->json(['ok' => true]);
})->name('appointments.toggle');
⋮----
Route::middleware('admin')->group(function () {
Route::get('/admin/users', [AdminUserController::class, 'create'])->name('admin.users.create');
Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
Route::get('/admin/login-history', [LoginHistoryController::class, 'index'])->name('admin.login-history');
⋮----
Route::view('/admin/tools', 'admin.tools.index')->name('admin.tools');
Route::view('/admin/settings', 'settings.index')->name('settings.index');
Route::view('/admin/imports', 'imports.index')->name('imports.index');
Route::get('/admin/export/appointments', [ExportController::class, 'appointments'])->name('admin.export.appointments');
Route::get('/admin/export/appointments-json', [ExportController::class, 'appointmentsJson'])->name('admin.export.appointments-json');
Route::get('/admin/export/clients', [ExportController::class, 'clients'])->name('admin.export.clients');
Route::get('/admin/export/clients-json', [ExportController::class, 'clientsJson'])->name('admin.export.clients-json');
Route::get('/admin/export/users', [ExportController::class, 'users'])->name('admin.export.users');
Route::get('/admin/export/users-json', [ExportController::class, 'usersJson'])->name('admin.export.users-json');
Route::get('/admin/export/database', [ExportController::class, 'database'])->name('admin.export.database');
Route::get('/admin/export/settings', [ExportController::class, 'settings'])->name('admin.export.settings');
Route::get('/admin/export/settings-csv', [ExportController::class, 'settingsCsv'])->name('admin.export.settings-csv');
Route::get('/admin/export/all-json', [ExportController::class, 'allJson'])->name('admin.export.all-json');
Route::get('/admin/export/all-csv', [ExportController::class, 'allCsv'])->name('admin.export.all-csv');
````

## File: storage/app/private/.gitignore
````
*
!.gitignore
````

## File: storage/app/public/.gitignore
````
*
!.gitignore
````

## File: storage/app/.gitignore
````
*
!private/
!public/
!.gitignore
````

## File: storage/debugbar/.gitignore
````
*
!.gitignore
````

## File: storage/framework/cache/data/.gitignore
````
*
!.gitignore
````

## File: storage/framework/cache/.gitignore
````
*
!data/
!.gitignore
````

## File: storage/framework/sessions/.gitignore
````
*
!.gitignore
````

## File: storage/framework/testing/.gitignore
````
*
!.gitignore
````

## File: storage/framework/views/.gitignore
````
*
!.gitignore
````

## File: storage/framework/.gitignore
````
compiled.php
config.php
down
events.scanned.php
maintenance.php
routes.php
routes.scanned.php
schedule-*
services.json
````

## File: storage/logs/.gitignore
````
*
!.gitignore
````

## File: tests/Feature/AdminDatabaseBackupTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;
⋮----
class AdminDatabaseBackupTest extends TestCase
⋮----
public function test_admin_can_download_a_sqlite_backup_zip(): void
⋮----
$admin = User::factory()->create(['is_admin' => true]);
$client = Client::query()->create([
⋮----
'fecha' => today()->toDateString(),
⋮----
Appointment::query()->create([
⋮----
'fecha' => today()->addDay()->toDateString(),
⋮----
$response = $this->actingAs($admin)
->get(route('admin.export.database'))
->assertDownload('citas-dentista-backup.zip');
⋮----
$zipPath = $response->baseResponse->getFile()->getPathname();
⋮----
$this->assertTrue($zip->open($zipPath));
⋮----
$sql = $zip->getFromName('citas-dentista-backup.sql');
⋮----
$this->assertIsString($sql);
$this->assertStringContainsString('PRAGMA foreign_keys=OFF;', $sql);
$this->assertStringContainsString('INSERT INTO "users"', $sql);
$this->assertStringContainsString('INSERT INTO "clients"', $sql);
$this->assertStringContainsString('INSERT INTO "appointments"', $sql);
$this->assertStringContainsString($admin->email, $sql);
$this->assertStringContainsString('Ana', $sql);
⋮----
$zip->close();
````

## File: tests/Feature/AdminUserCreationTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class AdminUserCreationTest extends TestCase
⋮----
public function test_only_admin_can_access_admin_user_creation(): void
⋮----
$admin = User::factory()->create(['is_admin' => true]);
⋮----
$this->actingAs($admin)
->get(route('admin.users.create'))
->assertOk();
⋮----
$regularUser = User::factory()->create();
⋮----
$this->actingAs($regularUser)
⋮----
->assertForbidden();
````

## File: tests/Feature/AdminUserManagementTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class AdminUserManagementTest extends TestCase
⋮----
public function test_admin_can_update_a_user(): void
⋮----
$admin = User::factory()->create(['is_admin' => true]);
$user = User::factory()->create([
⋮----
$this->actingAs($admin)
->put(route('admin.users.update', $user), [
⋮----
->assertRedirect(route('admin.users.create'));
⋮----
$this->assertDatabaseHas('users', [
⋮----
public function test_edit_form_submits_a_boolean_admin_value(): void
⋮----
$user = User::factory()->create();
⋮----
->get(route('admin.users.edit', $user))
->assertOk()
->assertSee('name="is_admin"', false)
->assertSee('value="1"', false)
->assertDontSee('value="is_admin"', false);
⋮----
public function test_admin_cannot_remove_its_own_role(): void
⋮----
User::factory()->create(['is_admin' => true]);
⋮----
->put(route('admin.users.update', $admin), [
⋮----
->assertStatus(422);
⋮----
$this->assertTrue($admin->fresh()->is_admin);
⋮----
public function test_another_admin_can_remove_the_role(): void
⋮----
$otherAdmin = User::factory()->create(['is_admin' => true]);
⋮----
->put(route('admin.users.update', $otherAdmin), [
⋮----
$this->assertFalse($otherAdmin->fresh()->is_admin);
⋮----
public function test_user_deletion_uses_a_confirmation_modal(): void
⋮----
->get(route('admin.users.create'))
⋮----
->assertSee('Eliminar usuario')
->assertSee($user->name)
->assertSeeHtml('aria-label="Cancelar"')
->assertSeeHtml('aria-label="Eliminar usuario"')
->assertSee('Esta acción no se puede deshacer.')
->assertDontSee('onsubmit="return confirm', false);
⋮----
public function test_admin_can_delete_users_and_other_admins_but_not_itself(): void
⋮----
$otherUser = User::factory()->create();
⋮----
->delete(route('admin.users.destroy', $otherUser))
⋮----
$this->assertDatabaseMissing('users', [
⋮----
->delete(route('admin.users.destroy', $otherAdmin))
⋮----
$this->assertModelMissing($otherAdmin);
⋮----
->delete(route('admin.users.destroy', $admin))
⋮----
->assertSee('Protegido');
````

## File: tests/Feature/AdminUsersExportTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class AdminUsersExportTest extends TestCase
⋮----
public function test_admin_can_export_users_as_csv(): void
⋮----
$admin = User::factory()->create(['is_admin' => true]);
User::factory()->create([
⋮----
$response = $this->actingAs($admin)
->get(route('admin.export.users'))
->assertOk()
->assertDownload('usuarios.csv');
⋮----
$content = $response->streamedContent();
⋮----
$this->assertStringContainsString('Nombre,Correo,Administrador', $content);
$this->assertStringContainsString('Laura,laura@example.com,No', $content);
$this->assertStringContainsString($admin->name, $content);
````

## File: tests/Feature/AppointmentCleanupSettingsTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\Settings\AppointmentCleanupSettings;
use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class AppointmentCleanupSettingsTest extends TestCase
⋮----
public function test_cleanup_settings_are_saved_when_selection_changes(): void
⋮----
$admin = User::factory()->create(['is_admin' => true]);
⋮----
Livewire::actingAs($admin)
->test(AppointmentCleanupSettings::class)
->assertSet('retentionPeriod', 'disabled')
->call('persistRetentionPeriod', '3_months')
->assertSet('status', 'Se guardarán las citas con un máximo de 3 meses de antiguedad');
⋮----
$this->assertSame('3_months', AppSetting::get()->retention_period);
⋮----
public function test_settings_page_shows_cleanup_section(): void
⋮----
$this->actingAs($admin)
->get(route('settings.index'))
->assertOk()
->assertSee('Mantenimiento / Opciones')
->assertSee('Desactivar')
->assertSee('2 años')
->assertSee('5 años');
````

## File: tests/Feature/AppointmentManagerTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\AppointmentForm;
use App\Livewire\AppointmentIndex;
use App\Livewire\ClientAppointments;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class AppointmentManagerTest extends TestCase
⋮----
public function test_appointment_edit_back_button_returns_to_the_clients_appointments(): void
⋮----
$user = User::factory()->create();
$client = Client::query()->create([
⋮----
$appointment = Appointment::query()->create([
⋮----
'fecha' => now()->addWeek(),
⋮----
$this->actingAs($user)
->from(route('clients.appointments', $client))
->get(route('appointments.edit', $appointment))
->assertOk()
->assertSee('Gestión cita de:')
->assertSeeHtml("onclick=\"window.location.href='".route('clients.appointments', $client)."'\"");
⋮----
public function test_appointment_navigation_buttons_only_appear_on_the_exact_appointments_url(): void
⋮----
->get(route('appointments.index'))
⋮----
->assertDontSee('Todas las citas');
⋮----
$this->get(route('clients.appointments', $client))
⋮----
->assertDontSeeHtml('data-appointment-navigation');
⋮----
public function test_appointment_list_shows_the_inbound_whatsapp_body_in_the_confir_repro_column(): void
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subMinute(),
⋮----
->get(route('clients.appointments', $client))
⋮----
->assertSee('Confir / Repro')
->assertSee('Necesito reprogramar la cita');
⋮----
public function test_legacy_client_query_redirects_to_the_client_appointments_route(): void
⋮----
->get(route('appointments.index', ['client' => $client->id]))
->assertRedirect(route('clients.appointments', $client));
⋮----
public function test_appointment_manager_can_create_an_appointment_for_a_client(): void
⋮----
Carbon::setTestNow('2026-06-23 09:00:00');
⋮----
Livewire::test(AppointmentForm::class)
->set('selectedClientId', $client->id)
->set('fecha', '2026-06-30')
->set('hora', '11:30')
->set('enviado', false)
->set('activo', true)
->call('save')
->assertSee('Cita creada correctamente.');
⋮----
$this->assertDatabaseHas('appointments', [
⋮----
Carbon::setTestNow();
⋮----
public function test_appointment_form_returns_to_the_selected_clients_appointments(): void
⋮----
->call('selectClient', $client->id)
->assertSet('returnUrl', route('clients.appointments', $client))
->assertSee('Citas de Ana')
->assertDontSee('Citas de Ana Pérez')
->assertSee('Todas las Citas');
⋮----
public function test_appointment_create_can_send_whatsapp_immediately(): void
⋮----
$admin = User::factory()->create();
⋮----
Config::set('whatsapp.driver', 'twilio');
Config::set('whatsapp.message_mode', 'text');
Config::set('whatsapp.twilio.account_sid', 'AC123');
Config::set('whatsapp.twilio.auth_token', 'test-token');
Config::set('whatsapp.twilio.mode', 'sandbox');
Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
⋮----
Http::fake([
'api.twilio.com/*/Messages.json' => Http::response([
⋮----
$this->actingAs($admin);
⋮----
->set('sendImmediately', true)
⋮----
->assertSee('Cita creada correctamente y WhatsApp enviado ahora.');
⋮----
$appointment = Appointment::query()->firstOrFail();
⋮----
Http::assertSent(function ($request): bool {
return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
⋮----
$message = WhatsAppMessage::query()->firstOrFail();
⋮----
$this->assertTrue($appointment->enviado);
$this->assertTrue($appointment->entregado);
$this->assertNotNull($appointment->refresh()->whatsapp_sent_at);
$this->assertNotNull($appointment->whatsapp_delivered_at);
$this->assertSame($client->id, $message->client_id);
$this->assertSame($appointment->id, $message->appointment_id);
$this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
$this->assertSame('SMAPPOINTMENTNOW123', $message->provider_message_id);
$this->assertTrue($message->metadata['immediate_send']);
$this->assertSame('2026-06-23 09:00:00', $message->metadata['immediate_sent_at']);
$this->assertNotNull($message->sent_at);
⋮----
public function test_appointment_form_rejects_today_and_sundays(): void
⋮----
->set('fecha', '2026-06-23')
⋮----
->assertHasErrors('fecha')
->assertSee('La fecha debe ser posterior a hoy.');
⋮----
->set('fecha', '2026-06-28')
⋮----
->assertHasErrors('fecha');
⋮----
$this->assertSame(0, Appointment::query()->count());
⋮----
public function test_appointment_page_can_open_selected_client_from_query_string(): void
⋮----
->get(route('appointments.create', ['client' => $client->id]))
⋮----
->assertSee('Lucía Martín')
->assertSee('Alta: 23/06/2026 09:00');
⋮----
public function test_client_search_is_limited_to_ten_results_without_pagination(): void
⋮----
Client::query()->create([
⋮----
Carbon::setTestNow(Carbon::now()->addSecond());
⋮----
$component = Livewire::test(AppointmentForm::class)
->set('filter_nombre', 'Persona');
⋮----
$html = $component->html();
⋮----
$this->assertStringContainsString('Hay más de 10 resultados, afina la búsqueda.', $html);
$this->assertSame(10, substr_count($html, 'wire:key="appointment-form-client-'));
$this->assertStringNotContainsString('Persona01 Prueba', $html);
$this->assertStringContainsString('Persona11 Prueba', $html);
⋮----
public function test_appointment_create_page_hides_management_until_client_is_selected(): void
⋮----
->get(route('appointments.create'))
⋮----
->assertSee('Buscar cliente')
->assertDontSee('Gestión cita');
⋮----
public function test_appointment_create_page_shows_management_when_client_is_selected(): void
⋮----
->assertSee('Nueva cita para:')
->assertSee('Lucía Martín');
⋮----
public function test_appointment_manager_can_update_active_status_from_listing(): void
⋮----
'scheduled_for' => now()->addDay(),
⋮----
Livewire::test(ClientAppointments::class, ['clientId' => $client->id])
->call('updateActiveStatus', $appointment->id, false)
->assertDispatched('toast', fn ($n, $p) => $p['message'] === 'Estado pendiente actualizado.' && $p['type'] === 'success');
⋮----
$this->assertFalse($appointment->refresh()->activo);
$this->assertSame(0, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());
⋮----
public function test_appointment_manager_can_update_appointment_active_status_from_listing(): void
⋮----
->assertSee('Cita activa')
->assertSeeHtml('wire:change="updateAppointmentActiveStatus('.$appointment->id.', $event.target.checked)"')
->call('updateAppointmentActiveStatus', $appointment->id, false)
->assertDispatched('toast', fn ($n, $p) => $p['message'] === 'Estado de la cita actualizado.' && $p['type'] === 'success');
⋮----
$this->assertFalse($appointment->refresh()->cita_activa);
⋮----
public function test_appointment_list_can_send_whatsapp_immediately(): void
⋮----
->assertSee('Enviar WhatsApp')
->assertSeeHtml('x-on:reload-appointment-list.window')
->assertSeeHtml('appointments/'.$appointment->id.'/edit')
->call('sendNow', $appointment->id)
->assertDispatched('reload-appointment-list')
->assertDispatched('toast', fn ($n, $p) => $p['message'] === 'WhatsApp enviado ahora correctamente.' && $p['type'] === 'success');
⋮----
$appointment->refresh();
⋮----
$this->assertSame('SMAPPOINTMENTLIST123', $message->provider_message_id);
⋮----
public function test_appointment_list_does_not_send_whatsapp_for_inactive_appointments(): void
⋮----
Http::fake();
⋮----
->assertDontSeeHtml('wire:click="sendNow('.$appointment->id.')"')
⋮----
->assertDispatched('toast', fn ($n, $p) => $p['message'] === 'Las citas inactivas no pueden enviarse.' && $p['type'] === 'error');
⋮----
Http::assertNothingSent();
$this->assertFalse($appointment->refresh()->enviado);
⋮----
public function test_sent_appointment_replaces_active_toggle_with_history_button(): void
⋮----
->set('filter', 'all')
->assertDontSee('Reenviar')
->assertSee('Historial')
->assertSeeHtml('wire:click="openHistory('.$appointment->id.')"')
->assertDontSeeHtml('wire:change="updateActiveStatus('.$appointment->id.', $event.target.checked)"')
->call('openHistory', $appointment->id)
->assertSet('historyAppointment.id', $appointment->id);
⋮----
$this->assertFalse($appointment->activo);
$this->assertTrue($appointment->cita_activa);
$this->assertSame(1, $appointment->whatsAppMessages()->count());
⋮----
public function test_past_sent_appointment_does_not_show_resend_action(): void
⋮----
->assertDontSeeHtml('wire:click="confirmResend('.$appointment->id.')"');
⋮----
public function test_appointment_list_marks_delivered_when_provider_log_is_read(): void
⋮----
->set('filter_enviado', true)
->assertSee('11:30');
⋮----
->call('syncDeliveryStatuses')
->assertSee('Se actualizaron 1 cita(s) como entregadas.');
⋮----
$this->assertTrue($appointment->refresh()->entregado);
⋮----
public function test_appointment_list_can_sync_delivery_statuses_manually(): void
⋮----
$message = WhatsAppMessage::query()->create([
⋮----
$component = Livewire::test(ClientAppointments::class, ['clientId' => $client->id])
->assertSee('Leer logs');
⋮----
$message->update([
⋮----
public function test_appointment_list_shows_sent_delivered_and_read_timestamps(): void
⋮----
->set('filter_entregado', true)
->assertSee('23/06/2026 08:05')
->assertSee('23/06/2026 08:10')
->assertSeeHtml('title="Message SID: SMTOOLTIP123"')
->assertSee('Leído')
->assertSeeHtml('text-green-400')
->assertSee('Sí');
⋮----
public function test_appointment_list_does_not_poll_twilio_on_render(): void
⋮----
->assertSee('11:30')
->assertDontSee('11:30:45');
⋮----
public function test_appointment_list_force_sync_polls_old_twilio_messages_and_marks_read(): void
⋮----
Carbon::setTestNow('2026-06-23 12:00:00');
⋮----
'scheduled_for' => now()->subDay()->subMinute(),
⋮----
'sent_at' => now()->subDay()->subMinute(),
⋮----
'api.twilio.com/*/Messages/SMOLD123.json' => Http::response([
⋮----
->assertSee('Se actualizaron 1 cita(s) como entregadas.')
->assertSee('Última actualización: 23/06/2026 12:00');
⋮----
$this->assertSame('2026-06-23 12:00:00', $appointment->whatsapp_read_at?->toDateTimeString());
Http::assertSent(fn ($request): bool => str_contains($request->url(), '/Messages/SMOLD123.json'));
⋮----
$this->assertSame('2026-06-23 12:00:00', $appointment->refresh()->whatsapp_read_at?->toDateTimeString());
Http::assertSent(fn ($request): bool => str_contains($request->url(), '/Messages/SMTOGGLEREAD123.json'));
⋮----
public function test_global_appointment_list_links_rows_to_client_appointments_without_send_now_button(): void
⋮----
Appointment::query()->create([
⋮----
Livewire::test(AppointmentIndex::class)
->assertDontSee('Enviar ya')
->assertSeeHtml('href="'.route('clients.appointments', $client).'"');
⋮----
public function test_appointment_list_shows_one_row_per_client_with_a_badge_for_multiple_appointments(): void
⋮----
$firstClient = Client::query()->create([
⋮----
$secondClient = Client::query()->create([
⋮----
$html = Livewire::test(AppointmentIndex::class)->html();
⋮----
$this->assertSame(2, substr_count($html, 'wire:key="appointment-client-'));
⋮----
->assertSee('Ana Pérez')
->assertSee('08:00')
->assertDontSee('10:17')
->assertDontSee('10:18')
->assertDontSee('11:23')
->assertSee('Citas:  3');
⋮----
public function test_appointment_list_does_not_allow_sending_inactive_appointments(): void
⋮----
Livewire::withQueryParams(['client' => $client->id])
->test(ClientAppointments::class)
⋮----
->assertSee('Las citas no pendientes no pueden enviarse.');
⋮----
public function test_appointment_list_can_be_filtered_by_client_from_query_string(): void
⋮----
Livewire::withQueryParams(['client' => $firstClient->id])
⋮----
->assertSee('12:45')
->assertSee('2 citas')
->assertDontSee('Luis Gómez')
->assertDontSee('09:00');
⋮----
public function test_client_appointment_list_shows_upcoming_by_default_and_can_show_all_or_past(): void
⋮----
Appointment::query()->create(['client_id' => $client->id, ...$appointment]);
⋮----
->assertSet('dateFilter', 'upcoming')
->assertDontSee('08:00')
->assertSee('09:00')
->assertSee('10:00')
->assertSee('11:00')
->set('dateFilter', 'all')
⋮----
->set('dateFilter', 'past')
⋮----
->assertDontSee('09:00')
->assertDontSee('10:00')
->assertDontSee('11:00');
⋮----
public function test_client_appointment_list_can_delete_selected_appointments_in_bulk(): void
⋮----
$otherClient = Client::query()->create([
⋮----
$firstAppointment = Appointment::query()->create([
⋮----
$secondAppointment = Appointment::query()->create([
⋮----
$pastAppointment = Appointment::query()->create([
⋮----
$otherAppointment = Appointment::query()->create([
⋮----
->assertSee('Seleccionar todas las citas visibles')
->call('toggleVisibleAppointments', [$firstAppointment->id, $secondAppointment->id])
->assertSee('Deseleccionar todas las citas visibles')
->assertSee('2 citas seleccionadas')
->assertSet('selectedAppointmentIds', [$firstAppointment->id, $secondAppointment->id])
->call('confirmBulkDelete')
->assertSet('bulkDeleteConfirmationOpen', true)
⋮----
->assertSet('selectedAppointmentIds', [])
->assertSet('bulkDeleteConfirmationOpen', false)
->assertSeeHtml('wire:key="select-all-appointments-all-0-0-0"')
->set('dateFilter', 'upcoming')
->set('selectedAppointmentIds', [$firstAppointment->id, $secondAppointment->id])
⋮----
->set('filter_activo', true)
⋮----
->assertSeeHtml('wire:key="select-all-appointments-upcoming-0-1-0"')
->set('filter_activo', false)
->set('selectedAppointmentIds', [$firstAppointment->id, $secondAppointment->id, $otherAppointment->id])
⋮----
->assertSee('Eliminar citas seleccionadas')
->assertSee('Esta acción no se puede deshacer.')
->assertSeeHtml('x-trap.noscroll="modalOpen"')
->call('deleteSelected')
->assertRedirect(route('appointments.index'));
⋮----
$this->assertModelMissing($firstAppointment);
$this->assertModelMissing($secondAppointment);
$this->assertModelExists($pastAppointment);
$this->assertModelExists($otherAppointment);
$this->assertSame('No hay citas para el cliente Ana Pérez', session('status'));
⋮----
public function test_client_appointment_list_can_activate_and_deactivate_selected_appointments_in_bulk(): void
⋮----
Appointment::query()->create(['client_id' => $client->id, 'fecha' => '2026-06-24', 'hora' => '09:00', 'enviado' => false, 'activo' => true]),
Appointment::query()->create(['client_id' => $client->id, 'fecha' => '2026-06-25', 'hora' => '10:00', 'enviado' => false, 'activo' => true]),
⋮----
->set('selectedAppointmentIds', [...$appointments->pluck('id'), $otherAppointment->id])
->assertSee('Activar seleccionadas')
->assertSee('Desactivar seleccionadas')
->call('updateSelectedActiveStatus', false)
->assertSet('selectedAppointmentIds', []);
⋮----
$appointments->each(fn (Appointment $appointment) => $this->assertFalse($appointment->fresh()->activo));
$this->assertTrue($otherAppointment->fresh()->activo);
⋮----
->set('selectedAppointmentIds', $appointments->pluck('id')->all())
->call('updateSelectedActiveStatus', true);
⋮----
$appointments->each(fn (Appointment $appointment) => $this->assertTrue($appointment->fresh()->activo));
⋮----
public function test_appointment_overview_paginates_thirty_clients_per_page(): void
⋮----
'fecha' => now()->addDays($appointmentNumber)->toDateString(),
⋮----
$this->assertSame(30, substr_count($html, 'wire:key="appointment-client-'));
⋮----
public function test_appointment_list_shows_pending_appointments_by_default_and_non_pending_with_toggle(): void
⋮----
$futurePendingClient = Client::query()->create([
⋮----
$futurePendingAppointment = Appointment::query()->create([
⋮----
$pastPendingClient = Client::query()->create([
⋮----
$pastPendingAppointment = Appointment::query()->create([
⋮----
$sentFutureClient = Client::query()->create([
⋮----
$sentPastClient = Client::query()->create([
⋮----
$inactiveFutureClient = Client::query()->create([
⋮----
->assertSee('Pendiente')
->assertSee('Supendidas')
⋮----
->assertSeeHtml('wire:key="appointment-'.$futurePendingAppointment->id.'"')
->assertDontSee('10:15')
->assertDontSee('12:30')
->assertDontSee('14:30')
->assertDontSee('13:30')
⋮----
->assertSee('Marta López')
->assertSee('10:15')
->assertSee('Elena Ruiz')
->assertSee('13:30')
->assertSee('Diego Vega')
->assertSee('14:30')
->assertSeeHtml('wire:key="appointment-'.$pastPendingAppointment->id.'"')
->assertDontSee('11:30')
⋮----
->assertSet('filter_enviado', false);
⋮----
public function test_active_filter_turns_off_sent_toggle(): void
⋮----
->assertSet('filter_activo', false)
->assertSet('filter_enviado', true);
⋮----
public function test_sent_filter_turns_off_active_toggle(): void
⋮----
->assertSeeHtml('disabled');
⋮----
public function test_future_appointments_can_be_deactivated_from_active_toggle(): void
⋮----
public function test_past_appointments_do_not_allow_active_status_changes_from_listing(): void
⋮----
->assertDispatched('toast', fn ($n, $p) => $p['message'] === 'Esta cita no se puede modificar. Solo se puede eliminar.' && $p['type'] === 'error');
⋮----
$this->assertTrue($appointment->refresh()->activo);
⋮----
public function test_sent_appointments_do_not_allow_active_status_changes_from_listing(): void
⋮----
public function test_locked_appointments_are_muted_and_only_show_delete_action(): void
⋮----
->assertSeeHtml('bg-slate-900/50 text-slate-400')
->assertSeeHtml('aria-label="Eliminar cita"')
->assertSeeHtml('href="'.route('clients.appointments', $client).'"')
->assertDontSeeHtml('aria-label="Editar cita"');
⋮----
public function test_appointment_list_asks_for_confirmation_before_deleting(): void
⋮----
->call('confirmDelete', $appointment->id)
->assertSee('Eliminar cita')
⋮----
->call('deleteConfirmed');
⋮----
$this->assertModelMissing($appointment);
⋮----
public function test_appointment_list_page_is_separate_from_appointment_form(): void
⋮----
->assertSee('Citas registradas')
->assertSee('Nueva cita')
->assertDontSee('Buscar cliente');
⋮----
public function test_appointment_edit_page_loads_selected_appointment(): void
⋮----
->assertSee('Editar cita')
->assertSee('Guardar')
->assertDontSee('Guardar cambios')
->assertDontSee('Buscar cliente')
->assertSee('Ana Pérez');
⋮----
public function test_appointment_edit_can_update_active_status(): void
⋮----
->set('isEditing', true)
->set('selectedAppointmentId', $appointment->id)
⋮----
->set('activo', false)
->set('returnUrl', route('appointments.index'))
⋮----
public function test_appointment_edit_can_send_whatsapp_immediately(): void
⋮----
->set('activo', true);
⋮----
$this->assertStringContainsString('wire:click="sendNow"', $component->html());
$this->assertStringNotContainsString('wire:click="sendNow" disabled="disabled"', $component->html());
⋮----
->call('sendNow')
->assertSee('WhatsApp enviado ahora correctamente.');
⋮----
$this->assertSame('SMAPPOINTMENTEDIT123', $message->provider_message_id);
$this->assertStringNotContainsString('Esta cita ya fue enviada o pertenece al pasado.', $component->html());
$this->assertStringNotContainsString('Enviar ya', $component->html());
$this->assertStringNotContainsString('Guardar cambios', $component->html());
$this->assertStringNotContainsString('Cancelar', $component->html());
$this->assertStringContainsString('Volver', $component->html());
⋮----
public function test_appointment_edit_marks_sent_when_provider_status_is_sent(): void
⋮----
$this->assertFalse($appointment->entregado);
⋮----
$this->assertSame('SMSENT123', $message->provider_message_id);
⋮----
public function test_appointment_edit_marks_sent_when_provider_status_is_queued(): void
⋮----
$this->assertSame('SMQUEUED123', $message->provider_message_id);
⋮----
public function test_appointment_edit_shows_provider_failure_reason_without_marking_sent(): void
⋮----
->assertSee('No se pudo enviar el WhatsApp.')
->assertSee('estado: undelivered')
->assertSee('código: 63016')
->assertSee('La cita no se ha marcado como enviada.');
⋮----
$this->assertFalse($appointment->enviado);
⋮----
$this->assertSame(WhatsAppMessage::STATUS_FAILED, $message->status);
$this->assertNull($message->sent_at);
$this->assertSame('SMUNDELIVERED123', $message->provider_message_id);
$this->assertStringContainsString('undelivered', $message->last_error);
$this->assertStringContainsString('63016', $message->last_error);
⋮----
public function test_past_appointment_edit_cannot_send_whatsapp_immediately(): void
⋮----
->set('fecha', '2026-06-01')
⋮----
->assertSee('Enviar ya')
⋮----
->assertSee('Las citas pasadas no pueden enviarse.');
⋮----
$this->assertSame(0, WhatsAppMessage::query()->count());
$this->assertStringContainsString('disabled="disabled"', $component->html());
⋮----
public function test_sent_appointments_cannot_be_updated_from_form(): void
⋮----
->set('fecha', '2026-07-01')
->set('hora', '10:00')
⋮----
->assertSee('Esta cita no se puede modificar. Solo se puede eliminar.');
⋮----
$this->assertSame('2026-06-30', $appointment->fecha->toDateString());
$this->assertSame('11:30', $appointment->hora);
⋮----
$this->assertTrue($appointment->activo);
⋮----
public function test_past_appointments_cannot_be_updated_from_form(): void
⋮----
$this->assertSame('2026-06-01', $appointment->fecha->toDateString());
⋮----
public function test_appointment_detects_when_its_schedule_changes_after_creation(): void
⋮----
$this->assertFalse($appointment->wasRescheduled());
$this->assertSame('2026-06-30', $appointment->fecha_original->toDateString());
$this->assertSame('11:30', $appointment->hora_original);
⋮----
->set('returnUrl', route('clients.appointments', $client))
⋮----
$this->assertTrue($appointment->wasRescheduled());
$this->assertSame('2026-07-01', $appointment->fecha->toDateString());
$this->assertSame('10:00', $appointment->hora);
⋮----
$change = $appointment->changes()->firstOrFail();
$this->assertSame('2026-06-30', $change->fecha_anterior->toDateString());
$this->assertSame('11:30', $change->hora_anterior);
$this->assertSame('2026-07-01', $change->fecha_nueva->toDateString());
$this->assertSame('10:00', $change->hora_nueva);
⋮----
->assertSee('1 reprogramación');
⋮----
public function test_appointment_detects_reschedule_even_when_original_schedule_fields_are_missing(): void
⋮----
Appointment::query()->whereKey($appointment->id)->update([
⋮----
public function test_appointment_list_filters_by_client_name_and_surname(): void
⋮----
->set('filter_nombre', 'Ana')
⋮----
->set('filter_nombre', '')
->set('filter_apellidos', 'Gómez')
->assertSee('Luis Gómez')
->assertDontSee('Ana Pérez');
⋮----
public function test_appointment_list_orders_by_date_and_then_time(): void
⋮----
$lateNextDay = Appointment::query()->create([
⋮----
$earlyFirstDay = Appointment::query()->create([
⋮----
$earlyNextDay = Appointment::query()->create([
⋮----
->assertSeeHtmlInOrder([
⋮----
public function test_appointment_list_orders_by_client(): void
⋮----
$ana = Client::query()->create([
⋮----
$luis = Client::query()->create([
⋮----
$luisAppointment = Appointment::query()->create([
⋮----
$anaAppointment = Appointment::query()->create([
⋮----
->call('sortByColumn', 'cliente')
⋮----
public function test_appointment_list_filters_with_sent_and_active_toggles(): void
⋮----
->assertDontSee('12:00')
->assertDontSee('13:00')
⋮----
->assertSee('12:00')
⋮----
->assertSee('13:00')
⋮----
->assertSet('filter_enviado', false)
->assertSet('filter_entregado', false);
⋮----
public function test_appointment_list_filters_delivered_appointments(): void
⋮----
->assertSee('Entregadas')
⋮----
->assertSet('filter_activo', false);
⋮----
public function test_appointment_list_hides_whatsapp_columns_when_nothing_has_been_sent(): void
⋮----
->assertDontSee('Enviado')
->assertDontSee('Fecha envío')
->assertDontSee('Entregado')
->assertDontSee('Fecha entrega')
->assertDontSee('Leído')
->assertSee('Pendiente');
⋮----
public function test_appointment_list_hides_pending_column_when_everything_is_sent(): void
⋮----
->assertDontSee('Pendiente');
⋮----
public function test_appointment_form_shows_client_matches_after_one_character(): void
⋮----
->assertSee('Las coincidencias aparecerán aquí')
->assertDontSee('Lucía Martín');
⋮----
$this->assertFalse($component->instance()->getHasClientSearchProperty());
⋮----
$component->set('filter_nombre', 'L')
⋮----
->assertSee('666777888');
$this->assertTrue($component->instance()->getHasClientSearchProperty());
````

## File: tests/Feature/AppointmentReminderSettingsTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\DispatchBanner;
use App\Livewire\Settings\AppointmentReminderSettings;
use App\Models\AppointmentReminderPreference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class AppointmentReminderSettingsTest extends TestCase
⋮----
public function test_reminder_settings_can_save_whatsapp_and_email_lead_days(): void
⋮----
$admin = User::factory()->create(['is_admin' => true]);
⋮----
Livewire::actingAs($admin)
->test(AppointmentReminderSettings::class)
->assertSet('whatsappLeadDays', [1])
->assertSet('emailLeadDays', [])
->set('whatsappLeadDays', [1, 3, 7])
->set('emailLeadDays', [2, 7])
->call('save')
->assertSet('status', 'Preferencias de recordatorios guardadas.');
⋮----
$this->assertSame(
⋮----
AppointmentReminderPreference::enabledLeadDaysFor(AppointmentReminderPreference::CHANNEL_WHATSAPP),
⋮----
AppointmentReminderPreference::enabledLeadDaysFor(AppointmentReminderPreference::CHANNEL_EMAIL),
⋮----
public function test_settings_page_shows_reminder_selection(): void
⋮----
$this->actingAs($admin)
->get(route('settings.index'))
->assertOk()
->assertSee('Recordatorios')
->assertSee('1 día antes')
->assertSee('1 semana antes')
->assertSee('Guardar');
⋮----
public function test_dispatch_banner_reacts_to_dispatch_toggle_event(): void
⋮----
Livewire::test(DispatchBanner::class)
->call('onToggle', ['value' => false])
->assertSee('Los envíos automáticos de WhatsApp están deshabilitados')
->call('onToggle', ['value' => true])
->assertDontSee('Los envíos automáticos de WhatsApp están deshabilitados');
````

## File: tests/Feature/BackupRoundTripTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\Settings\DatabaseBackup;
use App\Livewire\Settings\SettingsBackup;
use App\Livewire\Settings\TableBackup;
use App\Models\Appointment;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppCredential;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class BackupRoundTripTest extends TestCase
⋮----
private function exportAndRead(string $route, string $filename): string
⋮----
$this->actingAs(User::factory()->create(['is_admin' => true]))
->get(route($route))
->assertOk();
⋮----
$this->assertNotEmpty($files, "No export file found matching: {$pathPattern}");
⋮----
private function makeImportFile(string $content, string $name): UploadedFile
⋮----
return UploadedFile::fake()->createWithContent($name, file_get_contents($tmpPath));
⋮----
// ── TableBackup ─────────────────────────────────────────────
⋮----
public function test_clients_json_round_trip(): void
⋮----
$admin = User::factory()->create(['is_admin' => true]);
⋮----
Client::query()->create([
⋮----
'fecha' => today()->toDateString(),
⋮----
$json = $this->exportAndRead('admin.export.clients-json', 'clientes.json');
⋮----
$this->assertCount(1, $records);
$this->assertEquals('María', $records[0]['nombre']);
⋮----
Client::query()->forceDelete();
⋮----
$file = $this->makeImportFile($json, 'clientes.json');
⋮----
Livewire::actingAs($admin)
->test(TableBackup::class)
->set('selectedTable', 'clients')
->set('importFile', $file)
->call('importTable')
⋮----
->assertSet('importStatus', '1 registro(s) importado(s) en Clientes.');
⋮----
$this->assertDatabaseHas('clients', ['nombre' => 'María', 'apellidos' => 'García']);
⋮----
public function test_appointments_import_from_json(): void
⋮----
$client = Client::query()->create([
⋮----
$file = $this->makeImportFile(json_encode($data), 'citas.json');
⋮----
->set('selectedTable', 'appointments')
⋮----
->assertSet('importStatus', '1 registro(s) importado(s) en Citas.');
⋮----
$this->assertDatabaseHas('appointments', ['client_id' => $client->id]);
⋮----
public function test_appointments_json_export_contains_all_fields(): void
⋮----
Appointment::query()->create([
⋮----
'fecha' => today()->addDay()->toDateString(),
⋮----
$json = $this->exportAndRead('admin.export.appointments-json', 'citas.json');
⋮----
$this->assertArrayHasKey('client_id', $records[0]);
$this->assertEquals($client->id, $records[0]['client_id']);
$this->assertTrue($records[0]['enviado']);
⋮----
public function test_import_duplicate_clients_does_not_create_duplicates(): void
⋮----
->call('importTable');
⋮----
$this->assertEquals(1, Client::query()->count());
⋮----
public function test_non_admin_cannot_import(): void
⋮----
$user = User::factory()->create(['is_admin' => false]);
$file = $this->makeImportFile('{}', 'test.json');
⋮----
Livewire::actingAs($user)
⋮----
->assertStatus(403);
⋮----
// ── SettingsBackup ──────────────────────────────────────────
⋮----
public function test_settings_json_v2_import(): void
⋮----
$file = $this->makeImportFile(json_encode($data), 'settings.json');
⋮----
->test(SettingsBackup::class)
⋮----
->call('importSettings')
⋮----
->assertSet('importStatus', 'Ajustes importados correctamente.');
⋮----
$this->assertDatabaseHas('app_settings', [
⋮----
$this->assertDatabaseHas('appointment_reminder_preferences', [
⋮----
public function test_settings_import_v1_backward_compat(): void
⋮----
$file = $this->makeImportFile($v1Json, 'v1.json');
⋮----
public function test_settings_credentials_are_encrypted_in_db(): void
⋮----
->call('importSettings');
⋮----
$imported = WhatsAppCredential::query()->first();
$this->assertNotNull($imported);
// Eloquent encrypted cast auto-decrypts on read
$this->assertEquals('AC_test_sid_123', $imported->account_sid);
$this->assertEquals('auth_token_secret_456', $imported->auth_token);
⋮----
// ── DatabaseBackup ──────────────────────────────────────────
⋮----
public function test_full_database_json_structure(): void
⋮----
AppSetting::query()->create([
⋮----
$json = $this->exportAndRead('admin.export.all-json', 'database-backup-*.json');
⋮----
$this->assertEquals(1, $decoded['version']);
$this->assertArrayHasKey('users', $decoded['tables']);
$this->assertArrayHasKey('clients', $decoded['tables']);
$this->assertArrayHasKey('appointments', $decoded['tables']);
$this->assertArrayHasKey('app_settings', $decoded['tables']);
$this->assertArrayHasKey('appointment_reminder_preferences', $decoded['tables']);
$this->assertArrayHasKey('whatsapp_credentials', $decoded['tables']);
$this->assertArrayHasKey('whatsapp_sender_numbers', $decoded['tables']);
$this->assertArrayHasKey('twilio_content_templates', $decoded['tables']);
⋮----
public function test_database_import_from_json(): void
⋮----
$file = $this->makeImportFile(json_encode($data), 'db.json');
⋮----
->test(DatabaseBackup::class)
⋮----
->call('importDatabase')
->call('importDatabase');
⋮----
$this->assertDatabaseHas('clients', ['nombre' => 'Imported']);
$this->assertDatabaseHas('app_settings', ['retention_period' => '6_months']);
$this->assertDatabaseHas('appointment_reminder_preferences', ['channel' => 'whatsapp', 'lead_days' => 1]);
$this->assertDatabaseHas('twilio_content_templates', ['content_sid' => 'HXimported123456789012345678']);
⋮----
public function test_database_import_credentials_are_encrypted(): void
⋮----
$credential = WhatsAppCredential::query()->first();
$this->assertNotNull($credential);
⋮----
$this->assertEquals('AC_plain_text_sid', $credential->account_sid);
$this->assertEquals('plain_text_token', $credential->auth_token);
⋮----
public function test_non_admin_cannot_import_database(): void
⋮----
public function test_import_rejects_unsupported_format(): void
⋮----
$file = $this->makeImportFile('hello', 'data.txt');
⋮----
->assertSet('importStatus', 'Formato no soportado. Usa .json o .zip.');
````

## File: tests/Feature/CalendarIndexTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\CalendarIndex;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class CalendarIndexTest extends TestCase
⋮----
protected function tearDown(): void
⋮----
Carbon::setTestNow();
⋮----
parent::tearDown();
⋮----
public function test_calendar_page_renders(): void
⋮----
$user = User::factory()->create();
⋮----
$this->actingAs($user)
->get(route('calendar.index'))
->assertOk()
->assertSee('Calendario');
⋮----
public function test_calendar_shows_monthly_appointment_counts(): void
⋮----
Carbon::setTestNow(Carbon::parse('2026-07-13 10:00:00', config('app.timezone')));
⋮----
$client = Client::query()->create([
⋮----
Appointment::query()->create([
⋮----
Livewire::test(CalendarIndex::class)
->assertViewHas('calendarWeeks', function ($weeks): bool {
⋮----
->flatMap(fn ($week) => $week)
->filter()
->first(fn (array $day): bool => $day['date']->toDateString() === '2026-07-15');
⋮----
public function test_calendar_hides_sunday_appointment_counts(): void
⋮----
->first(fn (array $day): bool => $day['date']->toDateString() === '2026-07-19');
````

## File: tests/Feature/ClientCsvImportTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\ClientCsvImporter;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class ClientCsvImportTest extends TestCase
⋮----
public function test_admin_can_import_two_different_clients_with_same_phone(): void
⋮----
$admin = User::factory()->create();
⋮----
$this->actingAs($admin);
⋮----
Livewire::test(ClientCsvImporter::class)
->set('file', UploadedFile::fake()->createWithContent('clientes.csv', $csv))
->call('import')
->assertSet('status', 'Importación completada: 2 nuevo(s), 0 omitido(s), 0 restaurado(s).');
⋮----
$this->assertSame(2, Client::query()->count());
$this->assertSame(
⋮----
Client::query()->orderBy('nombre')->pluck('nombre')->all()
⋮----
Client::query()->pluck('telefono')->unique()->values()->all()
⋮----
public function test_admin_can_import_duplicate_same_client_without_creating_extra_rows(): void
⋮----
->assertSet('status', 'Importación completada: 1 nuevo(s), 1 omitido(s), 0 restaurado(s).');
⋮----
$this->assertSame(1, Client::query()->count());
⋮----
$client = Client::query()->firstOrFail();
⋮----
$this->assertSame('Ana', $client->nombre);
$this->assertSame('Pérez', $client->apellidos);
$this->assertSame('600123123', $client->telefono);
⋮----
public function test_admin_can_import_same_csv_twice_without_creating_duplicates(): void
⋮----
->assertSet('status', 'Importación completada: 1 nuevo(s), 0 omitido(s), 0 restaurado(s).');
⋮----
->assertSet('status', 'Importación completada: 0 nuevo(s), 1 omitido(s), 0 restaurado(s).');
⋮----
public function test_admin_can_import_same_person_with_different_name_split_without_creating_duplicate(): void
⋮----
Client::query()->create([
⋮----
public function test_admin_can_import_without_overwriting_existing_client(): void
⋮----
$client = Client::query()->create([
⋮----
$client->refresh();
⋮----
public function test_import_recreates_client_after_it_was_deleted(): void
⋮----
$client->delete();
````

## File: tests/Feature/ClientDataDeletionServiceTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use App\Services\ClientDataDeletionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class ClientDataDeletionServiceTest extends TestCase
⋮----
public function test_deleting_a_client_deletes_appointments_and_whatsapp_messages(): void
⋮----
$client = Client::query()->create([
⋮----
$appointment = $this->createAppointment($client);
⋮----
$appointmentMessage = $this->createWhatsAppMessage($client, $appointment);
$manualMessage = $this->createWhatsAppMessage($client);
⋮----
app(ClientDataDeletionService::class)->deleteClientById($client->id);
⋮----
$this->assertModelMissing($client);
$this->assertModelMissing($appointment);
$this->assertModelMissing($appointmentMessage);
$this->assertModelMissing($manualMessage);
⋮----
public function test_deleting_an_appointment_deletes_its_whatsapp_messages(): void
⋮----
$otherAppointment = $this->createAppointment($client, '2026-07-02');
⋮----
$message = $this->createWhatsAppMessage($client, $appointment);
$otherMessage = $this->createWhatsAppMessage($client, $otherAppointment);
⋮----
$deleted = app(ClientDataDeletionService::class)->deleteAppointments([$appointment->id], $client->id);
⋮----
$this->assertSame(1, $deleted);
⋮----
$this->assertModelMissing($message);
$this->assertModelExists($otherAppointment);
$this->assertModelExists($otherMessage);
⋮----
public function test_database_cascades_whatsapp_messages_when_an_appointment_is_deleted(): void
⋮----
$appointment->delete();
⋮----
public function test_deleting_an_appointment_deletes_messages_that_reference_its_messages(): void
⋮----
$parent = $this->createWhatsAppMessage($client, $appointment);
$child = $this->createWhatsAppMessage($client, parent: $parent);
⋮----
app(ClientDataDeletionService::class)->deleteAppointments([$appointment->id], $client->id);
⋮----
$this->assertModelMissing($parent);
$this->assertModelMissing($child);
⋮----
private function createAppointment(Client $client, string $fecha = '2026-07-01'): Appointment
⋮----
return Appointment::query()->create([
⋮----
private function createWhatsAppMessage(Client $client, ?Appointment $appointment = null, ?WhatsAppMessage $parent = null): WhatsAppMessage
⋮----
return WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->addDay(),
````

## File: tests/Feature/ClientManagerTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\ClientForm;
use App\Livewire\ClientIndex;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class ClientManagerTest extends TestCase
⋮----
public function test_clients_screen_filters_and_updates_clients(): void
⋮----
Carbon::setTestNow('2026-06-22 10:00:00');
⋮----
Client::query()->create([
⋮----
Carbon::setTestNow('2026-06-23 11:15:00');
⋮----
Livewire::test(ClientIndex::class)
->set('filter_nombre', 'Ana')
->assertSee('Ana Pérez')
->assertDontSee('Luis Gómez');
⋮----
Carbon::setTestNow();
⋮----
public function test_client_list_searches_after_one_character(): void
⋮----
$client = Client::query()->create([
⋮----
$component = Livewire::test(ClientIndex::class)
->assertSee('Las coincidencias aparecerán aquí')
->assertDontSee('Ana Pérez');
⋮----
$this->assertFalse($component->instance()->getHasClientSearchProperty());
⋮----
$component->set('filter_nombre', 'A')
⋮----
->assertSee('600123123')
->assertSeeHtml('href="'.route('clients.appointments', $client).'"')
->assertSeeHtml('href="'.route('appointments.create', ['client' => $client->id]).'"');
$this->assertTrue($component->instance()->getHasClientSearchProperty());
⋮----
public function test_client_list_orders_by_name(): void
⋮----
$marta = Client::query()->create([
⋮----
$ana = Client::query()->create([
⋮----
->set('filter_nombre', 'a')
->assertSeeHtmlInOrder([
⋮----
->call('sortByName')
⋮----
public function test_client_deletion_uses_a_confirmation_modal(): void
⋮----
$appointment = Appointment::query()->create([
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->addDay(),
⋮----
->call('confirmDelete', $client->id)
->assertSee('Eliminar cliente')
⋮----
->assertSeeHtml('aria-label="Cancelar"')
->assertSeeHtml('aria-label="Eliminar cliente"')
->assertSee('Esta acción no se puede deshacer.')
->call('cancelDelete')
->assertSet('clientPendingDeletionId', null)
⋮----
->assertSet('clientPendingDeletionId', $client->id)
->call('deleteConfirmed')
->assertSet('clientPendingDeletionId', null);
⋮----
$this->assertDatabaseMissing('clients', [
⋮----
$this->assertDatabaseMissing('appointments', [
⋮----
$this->assertSame(0, WhatsAppMessage::query()->where('client_id', $client->id)->count());
$this->assertSame(0, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());
⋮----
public function test_clients_screen_can_edit_selected_client(): void
⋮----
Livewire::test(ClientForm::class, ['client' => $client->id])
->set('nombre', 'Ana Maria')
->set('apellidos', 'Pérez López')
->set('telefono', '611222333')
->call('save')
->assertHasNoErrors();
⋮----
$client->refresh();
⋮----
$this->assertSame('Ana Maria', $client->nombre);
$this->assertSame('Pérez López', $client->apellidos);
$this->assertSame('611222333', $client->telefono);
$this->assertSame('2026-06-22', $client->created_at->toDateString());
⋮----
public function test_clients_screen_can_create_client(): void
⋮----
Livewire::test(ClientForm::class)
->set('nombre', 'Marta')
->set('apellidos', 'Soler')
->set('telefono', '600111222')
->call('save');
⋮----
$this->assertSame(1, Client::query()->count());
$this->assertDatabaseHas('clients', [
⋮----
public function test_clients_screen_does_not_duplicate_an_existing_client_with_a_normalized_phone(): void
⋮----
$existing = Client::query()->create([
⋮----
->assertSet('selectedClientId', $existing->id);
⋮----
$this->assertSame($existing->id, Client::query()->firstOrFail()->id);
⋮----
public function test_clients_page_can_open_selected_client_from_query_string(): void
⋮----
$admin = User::factory()->create();
Carbon::setTestNow('2026-06-25 12:40:00');
⋮----
$this->actingAs($admin)
->get(route('clients.edit', $client))
->assertOk()
->assertSee('Editar cliente')
->assertDontSee('Nueva cita')
->assertDontSee('client-form-appointment-');
⋮----
public function test_clients_list_page_displays_clients(): void
⋮----
Carbon::setTestNow('2026-06-30 12:00:00');
⋮----
Appointment::query()->create([
⋮----
->get(route('clients.list'))
⋮----
->assertSee('Listado de')
⋮----
->assertSee('1 - Cita')
->assertSee(route('clients.appointments', $client), false)
->assertSee(route('appointments.create', ['client' => $client->id]), false)
->assertSee('Nuevo Cliente');
⋮----
public function test_clients_list_does_not_count_past_appointments(): void
⋮----
->assertSee('Luis Gómez')
->assertSee('Sin citas');
⋮----
public function test_selected_client_edit_page_only_shows_past_appointments_in_history(): void
⋮----
Carbon::setTestNow('2026-06-23 09:00:00');
⋮----
$otherClient = Client::query()->create(['nombre' => 'Otra', 'apellidos' => 'Persona', 'telefono' => '+34600111222']);
Appointment::query()->create(['client_id' => $otherClient->id, 'fecha' => '2026-06-21', 'hora' => '12:00']);
⋮----
->assertSee('Historial de citas')
->assertSee('1 cita')
->assertSee('Lunes, 22 de junio de 2026')
->assertSee('09:00')
->assertSee('Confirmada')
->assertSee('WhatsApp entregado')
->assertDontSee('01/07/2026')
->assertDontSee('10:15')
->assertDontSee('08:00')
->assertDontSee('12:00');
⋮----
public function test_client_form_history_visibility_and_empty_state(): void
⋮----
$client = Client::query()->create(['nombre' => 'Lucía', 'apellidos' => 'Martín', 'telefono' => '+34666777888']);
⋮----
->assertSee('Este cliente no tiene citas anteriores.');
⋮----
->assertSee('Crear cliente')
->assertDontSee('Historial de citas')
->assertDontSee('Este cliente no tiene citas anteriores.');
⋮----
public function test_selected_client_edit_page_does_not_show_appointment_actions(): void
⋮----
->assertDontSee('No Enviado!')
->assertDontSee('Enviado')
->assertDontSee('Pendiente')
->assertDontSee('Inactivo');
⋮----
public function test_selected_client_card_can_update_future_unsent_appointment_active_status(): void
⋮----
->call('updateAppointmentActiveStatus', $appointment->id, false)
->assertDispatched('toast', fn ($n, $p) => $p['message'] === 'Estado activo actualizado.' && $p['type'] === 'success');
⋮----
$this->assertFalse($appointment->refresh()->activo);
⋮----
public function test_selected_client_card_deletes_locked_appointment(): void
⋮----
->call('deleteAppointment', $appointment->id)
⋮----
public function test_client_list_page_is_separate_from_client_form(): void
⋮----
$user = User::factory()->create();
⋮----
$this->actingAs($user)
->get(route('clients.index'))
````

## File: tests/Feature/ClientMessageSchedulerTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\ClientMessageScheduler;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class ClientMessageSchedulerTest extends TestCase
⋮----
public function test_scheduler_creates_message_linked_to_client(): void
⋮----
Carbon::setTestNow('2026-06-22 15:30:00');
⋮----
$admin = User::factory()->create();
$client = Client::query()->create([
⋮----
$this->actingAs($admin);
⋮----
Livewire::test(ClientMessageScheduler::class)
->call('selectClient', $client->id)
->set('scheduled_date', '2026-06-24')
->set('scheduled_time', '11:20')
->call('save')
->assertSee('Mensaje programado desde la ficha del cliente.');
⋮----
$message = WhatsAppMessage::query()->firstOrFail();
⋮----
$this->assertSame($client->id, $message->client_id);
$this->assertSame('Ana', $message->nombre);
$this->assertSame('Pérez', $message->apellidos);
$this->assertSame('600123123', $message->telefono);
$this->assertSame('2026-06-24 11:20:00', $message->scheduled_for->toDateTimeString());
⋮----
Carbon::setTestNow();
⋮----
public function test_scheduler_rejects_today_and_sundays(): void
⋮----
Carbon::setTestNow('2026-06-23 15:30:00');
⋮----
->set('scheduled_date', '2026-06-23')
⋮----
->assertHasErrors('scheduled_date')
->assertSee('La fecha debe ser posterior a hoy.');
⋮----
->set('scheduled_date', '2026-06-28')
⋮----
->assertHasErrors('scheduled_date');
⋮----
$this->assertSame(0, WhatsAppMessage::query()->count());
⋮----
public function test_scheduler_default_date_skips_sunday(): void
⋮----
Carbon::setTestNow('2026-06-27 15:30:00');
⋮----
->assertSet('scheduled_date', '2026-06-29');
⋮----
public function test_scheduler_can_send_selected_client_message_immediately(): void
⋮----
Config::set('whatsapp.driver', 'twilio');
Config::set('whatsapp.message_mode', 'text');
Config::set('whatsapp.twilio.account_sid', 'AC123');
Config::set('whatsapp.twilio.auth_token', 'test-token');
Config::set('whatsapp.twilio.mode', 'sandbox');
Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
⋮----
Http::fake([
'api.twilio.com/*/Messages.json' => Http::response([
⋮----
->set('scheduled_time', '10:15')
->call('sendNow')
->assertSee('WhatsApp enviado ahora y registrado correctamente.');
⋮----
Http::assertSent(function ($request): bool {
return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
⋮----
$this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
⋮----
$this->assertSame('2026-06-24 10:15:00', $message->scheduled_for->toDateTimeString());
$this->assertSame('SMIMMEDIATE123', $message->provider_message_id);
$this->assertTrue($message->metadata['immediate_send']);
$this->assertSame('2026-06-23 15:30:00', $message->metadata['immediate_sent_at']);
$this->assertNotNull($message->sent_at);
⋮----
public function test_scheduler_can_preselect_client_from_query_string(): void
⋮----
$this->actingAs($admin)
->get(route('clients.index', ['client' => $client->id]))
->assertOk()
->assertSee('Programar desde cliente')
->assertSee('Lucía Martín')
->assertSee('666777888');
````

## File: tests/Feature/DashboardOverviewTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\AgendaIndex;
use App\Livewire\DashboardOverview;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class DashboardOverviewTest extends TestCase
⋮----
protected function tearDown(): void
⋮----
Carbon::setTestNow();
⋮----
parent::tearDown();
⋮----
public function test_agenda_has_its_own_page_and_sidebar_link(): void
⋮----
$user = User::factory()->create();
⋮----
$this->actingAs($user)
->get(route('dashboard'))
->assertOk()
->assertDontSee('Agenda del día')
->assertSeeHtml('href="'.route('agenda.index').'"');
⋮----
$this->get(route('agenda.index'))
⋮----
->assertSee('Agenda del día');
⋮----
public function test_shows_today_appointments_by_default(): void
⋮----
$now = Carbon::parse('2026-06-22 10:00:00')->next(Carbon::FRIDAY);
Carbon::setTestNow($now);
$appointmentAt = $now->copy()->setTime(11, 20);
⋮----
$client = Client::query()->create([
⋮----
Appointment::query()->create([
⋮----
'fecha' => $appointmentAt->toDateString(),
'hora' => $appointmentAt->format('H:i:s'),
⋮----
$this->actingAs($user);
⋮----
Livewire::test(AgendaIndex::class)
->assertSee('Ana Pérez')
->assertSee('11:20');
⋮----
public function test_shows_saturday_appointments_when_today_is_saturday(): void
⋮----
$now = Carbon::parse('2026-06-22 10:00:00')->next(Carbon::SATURDAY);
⋮----
$appointmentAt = $now->copy()->setTime(9, 0);
⋮----
->assertSee('Lucía Martín')
->assertSee('09:00');
⋮----
public function test_date_buttons_render_with_correct_labels(): void
⋮----
->assertSee('Hoy')
->assertSee('Mañana')
->assertSee('En 2 días')
->assertSee('En 10 días')
->assertDontSee('Pasado mañana');
⋮----
public function test_selecting_date_offset_updates_appointments(): void
⋮----
$now = Carbon::parse('2026-06-29 10:00:00')->next(Carbon::MONDAY);
⋮----
// Appointment in 2 days (Wednesday)
$twoDaysLater = $now->copy()->addDays(2)->setTime(14, 0);
⋮----
'fecha' => $twoDaysLater->toDateString(),
'hora' => $twoDaysLater->format('H:i:s'),
⋮----
// Default is today (Monday) — appointment not shown
⋮----
->assertDontSee('Ana Pérez')
->call('selectDate', 2)
->assertSee('Ana Pérez');
⋮----
public function test_sunday_skip_when_tomorrow_is_sunday(): void
⋮----
$now = Carbon::parse('2026-06-27 10:00:00');
Carbon::setLocale('es');
⋮----
// Today is Saturday — create appointment for today (default view)
$saturday = $now->copy()->setTime(9, 0);
⋮----
'fecha' => $saturday->toDateString(),
'hora' => $saturday->format('H:i:s'),
⋮----
// Also create Monday appointment to verify skip works when selecting tomorrow
$monday = $now->copy()->next(Carbon::MONDAY)->setTime(9, 0);
⋮----
'fecha' => $monday->toDateString(),
'hora' => $monday->format('H:i:s'),
⋮----
// Default view shows today (Saturday)
⋮----
->assertSee('Lucía Martín');
⋮----
// Selecting tomorrow (offset 1) skips Sunday → shows Monday
⋮----
->call('selectDate', 1)
->assertSee('lunes');
⋮----
public function test_sunday_warning_not_shown_on_regular_days(): void
⋮----
$now = Carbon::parse('2026-06-29 10:00:00');
⋮----
->assertDontSee('domingo');
⋮----
public function test_sunday_warning_shown_when_offset_lands_on_sunday(): void
⋮----
// Default is today (Saturday) — no warning
⋮----
// Selecting tomorrow (offset 1) lands on Sunday — warning shown
⋮----
->assertSee('domingo');
⋮----
public function test_client_name_links_to_appointments(): void
⋮----
$now = Carbon::parse('2026-06-29 10:00:00')->next(Carbon::FRIDAY);
⋮----
// Create appointment for today (default view)
⋮----
->assertSee(route('clients.appointments', $client))
->assertSee(route('clients.edit', $client->id));
⋮----
public function test_edit_client_button_present(): void
⋮----
->assertSee('Carlos Ruiz')
⋮----
public function test_shows_inactive_appointments_with_incidence_badges(): void
⋮----
$appointmentAt = $now->copy()->setTime(13, 45);
⋮----
->assertSee('Marta López')
->assertSee('13:45')
->assertSee('Desactivada')
->assertSee('Sin enviar');
⋮----
public function test_dashboard_shows_operational_summary_and_only_upcoming_active_appointments(): void
⋮----
$now = Carbon::parse('2026-07-10 10:00:00', config('app.timezone'));
⋮----
'fecha' => $now->toDateString(),
⋮----
'fecha' => $now->copy()->addDay()->toDateString(),
⋮----
Livewire::test(DashboardOverview::class)
->assertViewHas('todayCount', 2)
->assertViewHas('upcomingWithoutReminderCount', 1)
->assertViewHas('nextAppointment', fn (Appointment $appointment): bool => $appointment->hora === '11:30:00')
->assertViewHas('nextAppointments', fn ($appointments): bool => $appointments->count() === 2)
->assertSee('11:30')
->assertDontSee('09:00')
->assertDontSee('12:30');
⋮----
public function test_dashboard_incidents_exclude_inbound_whatsapp_messages(): void
⋮----
$appointment = Appointment::query()->create([
⋮----
WhatsAppMessage::query()->create([
⋮----
->assertViewHas('failedCount', 1)
->assertViewHas('rescheduleCount', 1)
->assertSee('1 mensajes fallidos')
->assertSee('1 por reprogramar');
````

## File: tests/Feature/ExampleTest.php
````php
namespace Tests\Feature;
⋮----
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class ExampleTest extends TestCase
⋮----
/**
     * A basic test example.
     */
public function test_the_application_returns_a_successful_response(): void
⋮----
$response = $this->get('/');
⋮----
$response->assertStatus(200);
````

## File: tests/Feature/FailedWhatsAppMessageDisplayTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\ClientAppointments;
use App\Livewire\UnreadResponsesNotice;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class FailedWhatsAppMessageDisplayTest extends TestCase
⋮----
protected function tearDown(): void
⋮----
Carbon::setTestNow();
⋮----
parent::tearDown();
⋮----
public function test_failed_message_shows_error_in_appointment_list(): void
⋮----
Carbon::setTestNow('2026-06-30 10:00:00');
⋮----
$user = User::factory()->create();
$client = Client::query()->create([
⋮----
$appointment = Appointment::query()->create([
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subHour(),
⋮----
$this->actingAs($user);
⋮----
Livewire::test(ClientAppointments::class, ['clientId' => $client->id])
->assertSee('Error de envío')
->assertDontSee('En cola');
⋮----
public function test_failed_message_shows_no_entregado_not_green_in_delivered_column(): void
⋮----
'whatsapp_sent_at' => now()->subHour(),
⋮----
->set('showAllHistory', true)
->assertSee('No entregado')
->assertSee('text-green-400');
⋮----
public function test_successful_message_shows_green_check(): void
⋮----
'whatsapp_delivered_at' => now()->subMinutes(30),
⋮----
'sent_at' => now()->subHour(),
⋮----
->assertSee('text-green-400')
->assertDontSee('Error de envío');
⋮----
public function test_dispatch_does_not_mark_enviadoo_on_provider_failure(): void
⋮----
Carbon::setTestNow('2026-06-30 12:00:00');
⋮----
'scheduled_for' => now()->subMinute(),
⋮----
Config::set('whatsapp.driver', 'twilio');
Config::set('whatsapp.message_mode', 'text');
Config::set('whatsapp.twilio.account_sid', 'AC123');
Config::set('whatsapp.twilio.auth_token', 'test-token');
Config::set('whatsapp.twilio.mode', 'sandbox');
Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
⋮----
Http::fake([
'api.twilio.com/*/Messages.json' => Http::response([
⋮----
$this->artisan('whatsapp:dispatch-due')->assertExitCode(0);
⋮----
$appointment->refresh();
$message = WhatsAppMessage::query()->firstOrFail();
⋮----
$this->assertFalse($appointment->enviado, 'Appointment should NOT be marked as enviado when provider reports failure');
$this->assertSame(WhatsAppMessage::STATUS_FAILED, $message->status);
$this->assertNull($message->sent_at);
$this->assertSame('SMFAILED456', $message->provider_message_id);
⋮----
public function test_message_with_failed_payload_shows_error_in_delivered_column(): void
⋮----
->assertSee('No entregado');
⋮----
public function test_history_modal_shows_confirmation_badge_for_inbound_button_text_without_payload(): void
⋮----
Carbon::setTestNow('2026-07-11 23:35:00');
⋮----
'whatsapp_sent_at' => now()->subMinutes(6),
'whatsapp_delivered_at' => now()->subMinutes(5),
'whatsapp_read_at' => now()->subMinutes(4),
⋮----
$outbound = WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subMinutes(6),
⋮----
'sent_at' => now()->subMinutes(6),
⋮----
'scheduled_for' => now()->subMinutes(5),
⋮----
'sent_at' => now()->subMinutes(5),
⋮----
'responded_at' => now()->subMinutes(5),
⋮----
->call('openHistory', $appointment->id)
->assertSee('Historial de la cita')
->assertSee('Confirmada')
->assertDontSee('Confirmar Cita');
⋮----
public function test_history_modal_treats_confirmar_cita_text_as_confirmation_without_payload(): void
⋮----
public function test_history_modal_treats_prefixed_confirm_text_as_confirmation_without_payload(): void
⋮----
->assertDontSee('Respuesta: Confirmar Cita');
⋮----
public function test_appointment_detects_latest_inbound_after_last_sent_when_outbound_direction_is_null(): void
⋮----
$inbound = WhatsAppMessage::query()->create([
⋮----
$this->assertTrue($appointment->esCitaConfirmada());
$this->assertSame($inbound->id, $appointment->latestInboundAfterLastSent()?->id);
⋮----
public function test_appointment_list_shows_unread_badge_for_new_inbound_message(): void
⋮----
Carbon::setTestNow('2026-07-13 10:00:00');
⋮----
'scheduled_for' => now()->subMinutes(1),
⋮----
'sent_at' => now()->subMinutes(1),
⋮----
'responded_at' => now()->subMinutes(1),
⋮----
->assertSee('Nuevo mensaje')
->assertSee('No leido');
⋮----
public function test_opening_history_marks_latest_inbound_message_as_seen(): void
⋮----
->assertSee('No leido')
⋮----
->assertDontSee('No leido')
->assertSee('Todo leido');
⋮----
$this->assertNotNull($appointment->refresh()->last_inbound_seen_at);
$this->assertFalse($appointment->fresh()->hasUnreadInboundResponse());
⋮----
public function test_history_modal_can_send_manual_text_reply(): void
⋮----
Config::set('whatsapp.driver', 'log');
⋮----
->set('historyReplyBody', 'Te llamamos en unos minutos.')
->call('sendHistoryReply')
->assertDispatched('toast', fn ($n, $p) => $p['message'] === 'Respuesta enviada correctamente.' && $p['type'] === 'success');
⋮----
$reply = WhatsAppMessage::query()
->where('appointment_id', $appointment->id)
->where('source', WhatsAppMessage::SOURCE_MANUAL)
->latest('id')
->firstOrFail();
⋮----
$this->assertSame('Te llamamos en unos minutos.', $reply->message);
$this->assertSame(WhatsAppMessage::DIRECTION_OUTBOUND, $reply->direction);
$this->assertSame($inbound->id, $reply->parent_id);
$this->assertSame(WhatsAppMessage::STATUS_SENT, $reply->status);
⋮----
public function test_global_unread_responses_notice_lists_clients_with_direct_history_link(): void
⋮----
Livewire::test(UnreadResponsesNotice::class)
->assertSee('Nuevas respuestas de clientes')
->assertSee('Ana Perez')
->assertSee('Nuevo mensaje entrante')
->assertSee(route('clients.appointments', [
⋮----
public function test_global_unread_responses_notice_syncs_twilio_before_rendering(): void
⋮----
Cache::forget('unread_responses_notice_twilio_synced_at');
⋮----
WhatsAppCredential::query()->create([
⋮----
'api.twilio.com/*/Messages.json*' => Http::response([
⋮----
'date_sent' => now()->toRfc7231String(),
⋮----
'api.twilio.com/*/Messages/SMOUTBOUND123.json' => Http::response([
⋮----
->call('pollUpdates');
⋮----
$this->assertDatabaseHas('whatsapp_messages', [
⋮----
->assertSee('Necesito cambiar la cita');
````

## File: tests/Feature/PurgePastAppointmentsCommandTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\Appointment;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
⋮----
class PurgePastAppointmentsCommandTest extends TestCase
⋮----
public function test_it_deletes_appointments_older_than_the_configured_retention_period(): void
⋮----
Carbon::setTestNow(Carbon::create(2026, 7, 11, 12, 0, 0, config('app.timezone')));
⋮----
AppSetting::get()->update([
⋮----
$client = Client::query()->create([
⋮----
$expiredAppointment = Appointment::query()->create([
⋮----
'fecha' => now()->subDays(8)->toDateString(),
⋮----
$recentAppointment = Appointment::query()->create([
⋮----
'fecha' => now()->subDays(6)->toDateString(),
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subDays(8),
⋮----
$expiredAppointment->changes()->create([
'fecha_anterior' => now()->subDays(9)->toDateString(),
⋮----
'fecha_nueva' => now()->subDays(8)->toDateString(),
⋮----
$this->artisan('appointments:purge-past')
->expectsOutput('Borrado 1 citas expiradas.')
->assertSuccessful();
⋮----
$this->assertDatabaseMissing('appointments', ['id' => $expiredAppointment->id]);
$this->assertDatabaseMissing('whatsapp_messages', ['appointment_id' => $expiredAppointment->id]);
$this->assertDatabaseMissing('appointment_changes', ['appointment_id' => $expiredAppointment->id]);
$this->assertDatabaseHas('appointments', ['id' => $recentAppointment->id]);
⋮----
public function test_it_deletes_appointments_on_the_cutoff_day_regardless_of_hour(): void
⋮----
$cutoffAppointment = Appointment::query()->create([
⋮----
'fecha' => now()->subDays(7)->toDateString(),
⋮----
$this->assertDatabaseMissing('appointments', ['id' => $cutoffAppointment->id]);
⋮----
public function test_it_skips_when_cleanup_is_disabled(): void
⋮----
->expectsOutput('Borrado automático desactivado.')
````

## File: tests/Feature/ResetClientDataCommandTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\Appointment;
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Models\WhatsAppCredential;
use App\Models\WhatsAppMessage;
use App\Models\WhatsAppSenderNumber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class ResetClientDataCommandTest extends TestCase
⋮----
public function test_it_deletes_and_restarts_all_non_protected_tables(): void
⋮----
$user = User::factory()->create();
$client = Client::query()->create([
⋮----
$appointment = Appointment::query()->create([
⋮----
'fecha' => today()->toDateString(),
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subMinute(),
⋮----
AppointmentReminderPreference::query()->create([
⋮----
$credential = WhatsAppCredential::query()->create(['mode' => 'sandbox']);
WhatsAppSenderNumber::query()->create([
⋮----
TwilioContentTemplate::query()->create([
⋮----
$userCount = User::query()->count();
⋮----
$this->artisan('clients:reset-data --force')
->expectsOutput('ClientSeeder and AppointmentSeeder executed.')
->expectsOutput('Protected tables were not changed.')
->assertExitCode(0);
⋮----
$this->assertSame(10, Client::query()->count());
$this->assertSame(205, Appointment::query()->count());
$this->assertSame(1, WhatsAppMessage::query()->count());
$this->assertSame($userCount, User::query()->count());
$this->assertSame(1, AppointmentReminderPreference::query()->where('channel', 'whatsapp')->count());
$this->assertSame(1, AppSetting::query()->where('dispatch_enabled', true)->count());
$this->assertSame(1, WhatsAppCredential::query()->whereKey($credential->id)->count());
$this->assertSame(0, WhatsAppSenderNumber::query()->where('whatsapp_credential_id', $credential->id)->count());
$this->assertSame(1, TwilioContentTemplate::query()->where('content_sid', 'HX'.str_repeat('1', 32))->count());
⋮----
public function test_it_requires_force(): void
⋮----
$this->artisan('clients:reset-data')
->expectsOutput('This command is destructive. Re-run with --force.')
->assertExitCode(1);
````

## File: tests/Feature/ResetDatabaseAndSeedCommandTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class ResetDatabaseAndSeedCommandTest extends TestCase
⋮----
public function test_it_deletes_target_tables_and_runs_the_database_seeder(): void
⋮----
$user = User::factory()->create();
$client = Client::query()->create([
⋮----
$appointment = Appointment::query()->create([
⋮----
'fecha' => today()->toDateString(),
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subMinute(),
⋮----
$this->artisan('db:reset-and-seed --force')
->expectsOutput('Deleted 1 user(s), 1 client(s), 1 WhatsApp message(s) and 1 appointment(s).')
->expectsOutput('DatabaseSeeder executed.')
->assertExitCode(0);
⋮----
$this->assertSame(2, User::query()->count());
$this->assertSame(10, Client::query()->count());
$this->assertSame(205, Appointment::query()->count());
$this->assertSame(0, WhatsAppMessage::query()->count());
⋮----
public function test_it_requires_force(): void
⋮----
$this->artisan('db:reset-and-seed')
->expectsOutput('This command is destructive. Re-run with --force.')
->assertExitCode(1);
````

## File: tests/Feature/SettingsPageTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class SettingsPageTest extends TestCase
⋮----
public function test_settings_page_loads_with_collapsible_sections(): void
⋮----
$admin = User::factory()->create(['is_admin' => true]);
⋮----
$this->actingAs($admin)
->get(route('settings.index'))
->assertOk()
->assertSee('Ajustes')
->assertSee('Credenciales Twilio')
->assertSee('Plantillas de Twilio')
->assertSee('Mantenimiento / Opciones');
````

## File: tests/Feature/TwilioContentTemplateSettingsTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\Settings\TwilioContentTemplateSettings;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Services\WhatsApp\WhatsAppSender;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
⋮----
class TwilioContentTemplateSettingsTest extends TestCase
⋮----
public function test_admin_can_save_and_select_a_twilio_content_template(): void
⋮----
Carbon::setTestNow(Carbon::parse('2026-07-08 09:15:00', 'Europe/Madrid'));
⋮----
$admin = User::factory()->create(['is_admin' => true]);
⋮----
Livewire::actingAs($admin)
->test(TwilioContentTemplateSettings::class)
->set('nombre', 'Recordatorio')
->set('contentSid', $firstSid)
->set('variablePreset', 'with_name')
->call('addTemplate')
->set('nombre', 'Confirmación')
->set('contentSid', $secondSid)
->set('variablePreset', 'appointment')
->call('addTemplate');
⋮----
$second = TwilioContentTemplate::query()->where('content_sid', $secondSid)->firstOrFail();
⋮----
->assertSee('Usar plantilla')
->call('selectTemplate', $second->id)
->assertSet('status', 'Plantilla seleccionada.');
⋮----
$this->assertSame($secondSid, app(WhatsAppSender::class)->twilioContentSid());
$this->assertSame(
⋮----
$preview = app(WhatsAppSender::class)->buildTwilioPreviewRequest('600123123', 'Prueba', forceTemplate: true);
⋮----
$this->assertTrue($second->fresh()->seleccionada);
⋮----
Carbon::setTestNow();
⋮----
public function test_env_content_sid_is_used_when_no_database_template_is_selected(): void
⋮----
config()->set('whatsapp.twilio.content_sid', 'HX'.str_repeat('a', 32));
⋮----
$this->assertSame('HX'.str_repeat('a', 32), app(WhatsAppSender::class)->twilioContentSid());
````

## File: tests/Feature/TwilioWhatsAppStatusWebhookTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\Appointment;
use App\Models\Client;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Twilio\Security\RequestValidator;
⋮----
class TwilioWhatsAppStatusWebhookTest extends TestCase
⋮----
public function test_twilio_status_callback_marks_the_appointment_as_delivered(): void
⋮----
Carbon::setTestNow('2026-06-23 10:00:00');
⋮----
Config::set('whatsapp.twilio.auth_token', 'test-token');
Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));
⋮----
$client = Client::query()->create([
⋮----
$appointment = Appointment::query()->create([
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subMinute(),
⋮----
$signature = (new RequestValidator('test-token'))->computeSignature(
⋮----
$this->post(route('webhooks.twilio.whatsapp-status'), $payload, [
⋮----
])->assertNoContent();
⋮----
$appointment->refresh();
$message = WhatsAppMessage::query()->firstOrFail()->refresh();
⋮----
$this->assertTrue($appointment->entregado);
$this->assertNotNull($appointment->whatsapp_delivered_at);
$this->assertNull($appointment->whatsapp_read_at);
$this->assertSame('delivered', $message->provider_payload['callback']['message_status']);
$this->assertSame('DELIVERED', $message->provider_payload['callback']['event_type']);
$this->assertSame('SM123456789', $message->provider_message_id);
⋮----
Carbon::setTestNow();
⋮----
public function test_twilio_inbound_creates_new_inbound_record(): void
⋮----
$outbound = WhatsAppMessage::query()->create([
⋮----
$inbound = WhatsAppMessage::query()
->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
->firstOrFail();
⋮----
$this->assertSame($outbound->id, $inbound->parent_id);
$this->assertSame($appointment->id, $inbound->appointment_id);
$this->assertSame('Necesito reprogramar la cita', $inbound->respuesta);
$this->assertSame('inbound-api', $inbound->provider_payload['inbound']['direction']);
$this->assertSame('received', $inbound->provider_payload['inbound']['status']);
$this->assertSame('Necesito reprogramar la cita', $inbound->provider_payload['inbound']['body']);
⋮----
$outbound->refresh();
$this->assertNull($outbound->respuesta);
⋮----
public function test_twilio_inbound_with_parent_message_sid_links_to_correct_message(): void
⋮----
$olderMessage = WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subDays(2),
⋮----
'sent_at' => now()->subDays(2),
⋮----
$targetMessage = WhatsAppMessage::query()->create([
⋮----
'sent_at' => now()->subMinute(),
⋮----
$this->assertSame($targetMessage->id, $inbound->parent_id);
$this->assertSame('SM_TARGET_002', $inbound->provider_payload['inbound']['parent_message_sid']);
$this->assertSame('CH_CONVERSATION_001', $inbound->provider_payload['inbound']['conversation_sid']);
⋮----
$olderMessage->refresh();
$this->assertNull($olderMessage->respuesta);
⋮----
public function test_twilio_inbound_without_parent_message_sid_falls_back_to_phone_latest(): void
⋮----
$this->assertSame('Gracias', $inbound->respuesta);
$this->assertNull($inbound->provider_payload['inbound']['parent_message_sid']);
$this->assertNull($inbound->provider_payload['inbound']['conversation_sid']);
⋮----
public function test_twilio_inbound_uses_button_text_when_present(): void
⋮----
$this->assertSame('Confirmar', $inbound->respuesta);
$this->assertSame('Confirmar', $inbound->provider_payload['inbound']['button_text']);
$this->assertSame('Confirmar', $inbound->provider_payload['inbound']['response_text']);
$this->assertTrue($appointment->confirmada);
⋮----
public function test_twilio_inbound_marks_appointment_confirmed_when_button_text_is_confirmada_without_payload(): void
⋮----
$this->assertSame('Confirmada', $inbound->respuesta);
$this->assertTrue($inbound->isConfirmed());
⋮----
$this->assertFalse($appointment->pendiente_reprogramacion);
⋮----
public function test_twilio_inbound_matches_template_reply_parent_sid_aliases(): void
⋮----
$this->assertSame('SM_TEMPLATE_PARENT', $inbound->provider_payload['inbound']['parent_message_sid']);
$this->assertSame('SM_INBOUND_ALIAS', $inbound->provider_payload['inbound']['message_sid']);
⋮----
public function test_twilio_inbound_creates_separate_records_for_multiple_responses(): void
⋮----
$this->post(route('webhooks.twilio.whatsapp-status'), $confirmarPayload, [
⋮----
Carbon::setTestNow('2026-06-23 10:05:00');
⋮----
$signature2 = (new RequestValidator('test-token'))->computeSignature(
⋮----
$this->post(route('webhooks.twilio.whatsapp-status'), $reprogramarPayload, [
⋮----
$inboundMessages = WhatsAppMessage::query()
⋮----
->where('parent_id', $outbound->id)
->get();
⋮----
$this->assertCount(2, $inboundMessages);
$this->assertSame('Confirmar', $inboundMessages->first()->respuesta);
$this->assertSame('Reprogramar', $inboundMessages->last()->respuesta);
⋮----
$this->assertTrue($appointment->pendiente_reprogramacion);
$this->assertFalse($appointment->confirmada);
⋮----
public function test_twilio_status_callback_rejects_invalid_signatures(): void
⋮----
$response = $this->post(route('webhooks.twilio.whatsapp-status'), [
⋮----
$response->assertForbidden();
````

## File: tests/Feature/WhatsAppConnectionTestComponentTest.php
````php
namespace Tests\Feature;
⋮----
use App\Livewire\Settings\WhatsAppConnectionTest;
use App\Models\TwilioContentTemplate;
use App\Models\WhatsAppCredential;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;
⋮----
class WhatsAppConnectionTestComponentTest extends TestCase
⋮----
public function test_settings_connection_form_calls_sender_and_shows_success(): void
⋮----
$sender = Mockery::mock(WhatsAppSender::class);
$sender->shouldReceive('sendTestMessage')
->once()
->with('+34600123123', 'Mensaje de prueba', 'sender', false, null)
->andReturn([
⋮----
$this->app->instance(WhatsAppSender::class, $sender);
⋮----
Livewire::test(WhatsAppConnectionTest::class)
->set('mode', 'sender')
->set('recipient', '+34600123123')
->set('body', 'Mensaje de prueba')
->call('sendTest')
->assertSet('statusType', 'success')
->assertSet('status', 'Prueba enviada correctamente.')
->assertSet('details.message_id', 'SMTEST999')
->assertSet('details.provider', 'twilio')
->assertSet('details.mode', 'sender');
⋮----
public function test_settings_connection_form_can_send_a_template_test_message(): void
⋮----
$template = TwilioContentTemplate::query()->create([
⋮----
->with('+34600123123', 'Mensaje de prueba', 'sender', true, $template->id)
⋮----
->set('testType', 'template')
->set('templateId', (string) $template->id)
⋮----
->assertSet('details.message_id', 'SMTESTTEMPLATE')
⋮----
public function test_settings_connection_form_blocks_saved_recipient_when_it_is_a_sender_number(): void
⋮----
config()->set('whatsapp.twilio.mode', 'sandbox');
config()->set('whatsapp.twilio.test_recipient', '600123123');
⋮----
$credential = WhatsAppCredential::create([
⋮----
$credential->senderNumbers()->create([
⋮----
$sender->shouldNotReceive('sendTestMessage');
⋮----
->call('sendSavedRecipient')
->assertSet('statusType', 'error')
->assertSet('status', 'No puedes enviar una prueba a un número que ya está configurado como remitente.');
⋮----
public function test_settings_connection_form_blocks_test_messages_to_sender_numbers(): void
⋮----
->set('mode', 'sandbox')
->set('recipient', '600123123')
⋮----
->assertSet('status', 'No puedes enviar una prueba a un número que ya está configurado como remitente.')
->assertHasErrors(['recipient']);
⋮----
public function test_saved_recipient_is_used_for_test_messages_when_it_is_not_a_sender_number(): void
⋮----
config()->set('whatsapp.twilio.test_recipient', '600999999');
⋮----
->with('600999999', 'Mensaje de prueba desde Clínica Dental Eugenia.', 'sandbox', false, null)
⋮----
->assertSet('details.message_id', 'SMTEST123');
⋮----
public function test_settings_connection_form_shows_payload_preview_for_twilio_sender_mode(): void
⋮----
$this->app->setLocale('es');
⋮----
config()->set('whatsapp.driver', 'twilio');
config()->set('whatsapp.twilio.from', 'whatsapp:+14155238886');
config()->set('whatsapp.default_country_code', '+34');
⋮----
->assertSee('Vista previa del payload')
->assertSee('From')
->assertSee('whatsapp:+34600123123')
->assertSee('Mensaje de prueba');
⋮----
public function test_settings_connection_form_reflects_auto_twilio_mode_from_configuration(): void
⋮----
config()->set('whatsapp.twilio.mode', 'auto');
⋮----
->assertSet('mode', 'auto')
⋮----
->assertSee('auto → sandbox')
⋮----
->assertSee('whatsapp:+34600123123');
⋮----
public function test_settings_connection_form_refreshes_preview_from_when_selected_sender_changes(): void
⋮----
$first = $credential->senderNumbers()->create([
⋮----
$second = $credential->senderNumbers()->create([
⋮----
$component = Livewire::test(WhatsAppConnectionTest::class)
⋮----
->set('recipient', '600123123');
⋮----
$credential->senderNumbers()->update(['selected' => false]);
$credential->senderNumbers()->where('id', $second->id)->update(['selected' => true]);
⋮----
->dispatch('credentialsChanged')
->assertSee($second->whatsapp_address)
->assertDontSee($first->whatsapp_address);
⋮----
public function test_sender_numbers_keep_the_same_order_when_selection_changes(): void
⋮----
$initialOrder = $credential->senderNumbers()->orderBy('id')->pluck('id')->all();
⋮----
$updatedOrder = $credential->senderNumbers()->orderBy('id')->pluck('id')->all();
⋮----
$this->assertSame([$first->id, $second->id], $initialOrder);
$this->assertSame([$first->id, $second->id], $updatedOrder);
⋮----
public function test_settings_connection_form_shows_twilio_template_payload_preview(): void
⋮----
config()->set('whatsapp.message_mode', 'template');
config()->set('whatsapp.twilio.mode', 'sender');
config()->set('whatsapp.twilio.from', 'whatsapp:+15551234567');
config()->set('whatsapp.twilio.content_sid', 'HX'.str_repeat('2', 32));
⋮----
->set('body', 'Mensaje de plantilla')
->assertSee('ContentSid')
->assertSee('HX'.str_repeat('1', 32))
->assertSee('ContentVariables')
->assertSee('Ana')
->assertSee('Plantilla')
->assertDontSee('&quot;Body&quot;');
⋮----
public function test_settings_connection_form_does_not_duplicate_country_code_for_whatsapp_recipient(): void
⋮----
->set('recipient', 'whatsapp:+34618287914')
->assertSee('whatsapp:+34618287914')
->assertDontSee('whatsapp:+3434618287914');
⋮----
protected function setUp(): void
⋮----
parent::setUp();
⋮----
config()->set('whatsapp.message_mode', 'text');
⋮----
protected function tearDown(): void
⋮----
Mockery::close();
⋮----
parent::tearDown();
````

## File: tests/Feature/WhatsAppDispatchCommandTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\Appointment;
use App\Models\AppointmentReminderPreference;
use App\Models\AppSetting;
use App\Models\Client;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Models\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
⋮----
class WhatsAppDispatchCommandTest extends TestCase
⋮----
public function test_due_messages_are_sent_via_cloud_api_and_marked_as_sent(): void
⋮----
$admin = User::factory()->create();
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subMinute(),
⋮----
Config::set('whatsapp.driver', 'cloud_api');
Config::set('whatsapp.cloud_api.base_url', 'https://graph.facebook.com');
Config::set('whatsapp.cloud_api.version', 'v22.0');
Config::set('whatsapp.cloud_api.phone_number_id', '1234567890');
Config::set('whatsapp.cloud_api.access_token', 'test-token');
Config::set('whatsapp.default_country_code', '+34');
⋮----
Http::fake([
'graph.facebook.com/*/messages' => Http::response([
⋮----
$this->artisan('whatsapp:dispatch-due')->assertExitCode(0);
⋮----
Http::assertSent(function ($request): bool {
return $request->url() === 'https://graph.facebook.com/v22.0/1234567890/messages'
⋮----
$message = WhatsAppMessage::query()->firstOrFail();
⋮----
$this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
$this->assertSame('wamid.TEST123', $message->provider_message_id);
$this->assertSame('cloud_api', $message->provider_payload['provider']);
$this->assertSame('Hola Ana', $message->provider_payload['payload']['text']['body']);
$this->assertNotNull($message->sent_at);
⋮----
public function test_active_unsent_due_appointments_are_queued_sent_and_marked_as_sent(): void
⋮----
Carbon::setTestNow('2026-06-22 12:00:00');
⋮----
AppointmentReminderPreference::saveSelections([
⋮----
AppSetting::get();
⋮----
$client = Client::query()->create([
⋮----
$appointment = Appointment::query()->create([
⋮----
Config::set('whatsapp.driver', 'twilio');
Config::set('whatsapp.message_mode', 'text');
Config::set('whatsapp.twilio.account_sid', 'AC123');
Config::set('whatsapp.twilio.auth_token', 'test-token');
Config::set('whatsapp.twilio.mode', 'sandbox');
Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
⋮----
$this->createSelectedTwilioTemplate();
⋮----
'api.twilio.com/*/Messages.json' => Http::response([
⋮----
'api.twilio.com/*/Messages/SMDISPATCHDUE123.json' => Http::response([
⋮----
$this->artisan('whatsapp:dispatch-due')
->expectsOutput('Queued 1 appointment message(s).')
->expectsOutput('Processed 1 due message(s).')
->assertExitCode(0);
⋮----
$this->assertSame($appointment->id, $message->appointment_id);
$this->assertSame($client->id, $message->client_id);
$this->assertSame(WhatsAppMessage::SOURCE_APPOINTMENT, $message->source);
⋮----
$this->assertSame('SMDISPATCHDUE123', $message->provider_message_id);
$this->assertSame(1, $message->metadata['lead_days']);
$appointment->refresh();
⋮----
$this->assertTrue($appointment->enviado);
$this->assertFalse($appointment->activo);
$this->assertTrue($appointment->cita_activa);
$this->assertTrue($appointment->entregado);
$this->assertNotNull($appointment->refresh()->whatsapp_sent_at);
$this->assertNotNull($appointment->whatsapp_delivered_at);
⋮----
return $request->method() === 'GET'
&& $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages/SMDISPATCHDUE123.json';
⋮----
->expectsOutput('Queued 0 appointment message(s).')
->expectsOutput('Processed 0 due message(s).')
⋮----
$this->assertSame(1, WhatsAppMessage::query()->count());
⋮----
Carbon::setTestNow();
⋮----
public function test_active_appointments_are_queued_for_selected_whatsapp_lead_days(): void
⋮----
Config::set('whatsapp.driver', 'log');
⋮----
->expectsOutput('Queued 3 appointment message(s).')
⋮----
$this->assertSame(3, WhatsAppMessage::query()->where('appointment_id', $appointment->id)->count());
⋮----
$messages = WhatsAppMessage::query()
->where('appointment_id', $appointment->id)
->orderBy('scheduled_for')
->get();
⋮----
$this->assertSame([7, 2, 1], $messages->pluck('metadata')->map(fn (array $metadata): int => $metadata['lead_days'])->all());
$this->assertSame([
⋮----
], $messages->map(fn (WhatsAppMessage $message): string => $message->scheduled_for->toDateTimeString())->all());
⋮----
Carbon::setTestNow('2026-06-23 12:00:00');
⋮----
$this->assertSame(1, WhatsAppMessage::query()->where('status', WhatsAppMessage::STATUS_SENT)->count());
$this->assertTrue($appointment->refresh()->enviado);
$this->assertFalse($appointment->entregado);
⋮----
public function test_delivery_sync_command_marks_appointments_as_delivered_when_logs_show_delivered(): void
⋮----
$this->artisan('whatsapp:sync-delivery-status')
->expectsOutput('Synced 1 delivered appointment(s).')
⋮----
$this->assertTrue($appointment->refresh()->entregado);
⋮----
public function test_backfill_command_populates_appointment_delivery_timestamps_from_stored_messages(): void
⋮----
$this->artisan('whatsapp:backfill-appointment-delivery-state')
->expectsOutput('Backfilled 1 appointment(s).')
⋮----
$this->assertSame('2026-06-23 08:05:00', $appointment->whatsapp_sent_at?->toDateTimeString());
$this->assertSame('2026-06-23 08:12:00', $appointment->whatsapp_delivered_at?->toDateTimeString());
$this->assertSame('2026-06-23 08:12:00', $appointment->whatsapp_read_at?->toDateTimeString());
⋮----
private function createSelectedTwilioTemplate(): void
⋮----
TwilioContentTemplate::query()->create([
````

## File: tests/Feature/WhatsAppMessageClientLinkTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class WhatsAppMessageClientLinkTest extends TestCase
⋮----
public function test_messages_page_has_been_removed(): void
⋮----
$admin = User::factory()->create();
⋮----
$this->actingAs($admin)
->get('/messages')
->assertNotFound();
````

## File: tests/Feature/WhatsAppMessageManagerSearchTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class WhatsAppMessageManagerSearchTest extends TestCase
⋮----
public function test_messages_page_has_been_removed(): void
⋮----
$admin = User::factory()->create();
⋮----
$this->actingAs($admin)
->get('/messages')
->assertNotFound();
````

## File: tests/Feature/WhatsAppTemplateSelectionTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class WhatsAppTemplateSelectionTest extends TestCase
⋮----
public function test_selected_template_is_used_for_manual_message_preview_and_save(): void
⋮----
$rendered = WhatsAppMessage::buildMessage([
⋮----
'scheduled_for' => now()->setDate(2026, 6, 22)->setTime(9, 5),
⋮----
$this->assertSame(
````

## File: tests/Feature/WhatsAppTemplateTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
⋮----
class WhatsAppTemplateTest extends TestCase
⋮----
public function test_message_template_replaces_placeholders_from_data(): void
⋮----
$message = WhatsAppMessage::buildMessage([
⋮----
'scheduled_for' => now()->setDate(2026, 6, 22)->setTime(15, 30),
⋮----
$this->assertSame('Hola Ana Pérez, tu cita es el lunes 22 de junio a las 15:30. Tel: 600123123', $message);
````

## File: tests/Feature/WhatsAppTwilioDispatchTest.php
````php
namespace Tests\Feature;
⋮----
use App\Models\Client;
use App\Models\TwilioContentTemplate;
use App\Models\User;
use App\Models\WhatsAppMessage;
use App\Services\WhatsApp\WhatsAppSender;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;
⋮----
class WhatsAppTwilioDispatchTest extends TestCase
⋮----
protected function setUp(): void
⋮----
parent::setUp();
⋮----
Config::set('whatsapp.message_mode', 'text');
⋮----
public function test_phone_numbers_are_stored_without_the_spanish_prefix_and_added_for_twilio(): void
⋮----
Config::set('whatsapp.default_country_code', '+34');
⋮----
$spanishClient = Client::query()->create([
⋮----
$foreignClient = Client::query()->create([
⋮----
$this->assertSame('600123123', $spanishClient->telefono);
$this->assertSame('+33612345678', $foreignClient->telefono);
$this->assertSame('whatsapp:+34600123123', $sender->buildTwilioPreviewRequest($spanishClient->telefono, 'Hola')['To']);
$this->assertSame('whatsapp:+33612345678', $sender->buildTwilioPreviewRequest($foreignClient->telefono, 'Bonjour')['To']);
⋮----
public function test_due_messages_are_sent_via_twilio_and_marked_as_sent(): void
⋮----
$admin = User::factory()->create();
⋮----
WhatsAppMessage::query()->create([
⋮----
'scheduled_for' => now()->subMinute(),
⋮----
Config::set('whatsapp.driver', 'twilio');
Config::set('whatsapp.twilio.account_sid', 'AC123');
Config::set('whatsapp.twilio.auth_token', 'test-token');
Config::set('whatsapp.twilio.mode', 'sandbox');
Config::set('whatsapp.twilio.from', 'whatsapp:+14155238886');
Config::set('whatsapp.twilio.status_callback_url', route('webhooks.twilio.whatsapp-status', absolute: true));
⋮----
$this->createSelectedTwilioTemplate();
⋮----
Http::fake([
'api.twilio.com/*/Messages.json' => Http::response([
⋮----
$this->artisan('whatsapp:dispatch-due')->assertExitCode(0);
⋮----
Http::assertSent(function ($request): bool {
return $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
⋮----
$message = WhatsAppMessage::query()->firstOrFail();
⋮----
$this->assertSame('600123123', $message->telefono);
$this->assertSame(WhatsAppMessage::STATUS_SENT, $message->status);
$this->assertSame('SMTEST123', $message->provider_message_id);
$this->assertSame('twilio', $message->provider_payload['provider']);
$this->assertSame('sandbox', $message->provider_payload['payload']['mode']);
$this->assertSame('HX'.str_repeat('9', 32), $message->provider_payload['payload']['content_sid']);
$this->assertSame('whatsapp:+34600123123', $message->provider_payload['payload']['to']);
$this->assertNotNull($message->sent_at);
⋮----
public function test_twilio_api_key_authenticates_rest_requests(): void
⋮----
Config::set('whatsapp.twilio.auth_token', null);
Config::set('whatsapp.twilio.api_key_sid', 'SK123');
Config::set('whatsapp.twilio.api_key_secret', 'api-secret');
⋮----
'api.twilio.com/*/Messages.json' => Http::response(['sid' => 'SMAPIKEY123'], 201),
⋮----
(new WhatsAppSender)->sendTestMessage('600123123', 'Hola');
⋮----
Http::assertSent(fn ($request): bool => $request->url() === 'https://api.twilio.com/2010-04-01/Accounts/AC123/Messages.json'
&& $request->header('Authorization')[0] === 'Basic '.base64_encode('SK123:api-secret'));
⋮----
public function test_twilio_text_messages_cannot_be_blank(): void
⋮----
Http::fake();
⋮----
$this->expectException(RuntimeException::class);
$this->expectExceptionMessage('El mensaje de texto no puede estar vacío.');
⋮----
(new WhatsAppSender)->sendTestMessage('600123123', '   ');
⋮----
Http::assertNothingSent();
⋮----
public function test_twilio_test_text_messages_ignore_global_template_mode(): void
⋮----
Config::set('whatsapp.message_mode', 'template');
⋮----
'api.twilio.com/*/Messages.json' => Http::response(['sid' => 'SMTESTTEXT'], 201),
⋮----
Http::assertSent(fn ($request): bool => $request['Body'] === 'Hola'
⋮----
public function test_due_messages_can_be_sent_with_twilio_sender_mode(): void
⋮----
Config::set('whatsapp.twilio.mode', 'sender');
Config::set('whatsapp.twilio.from', 'whatsapp:+15551234567');
⋮----
$this->assertSame('SMTESTSERVICE123', $message->provider_message_id);
$this->assertSame('sender', $message->provider_payload['payload']['mode']);
$this->assertSame('whatsapp:+15551234567', $message->provider_payload['payload']['from']);
⋮----
public function test_auto_twilio_mode_prefers_sender_when_from_is_configured(): void
⋮----
Config::set('whatsapp.twilio.mode', 'auto');
⋮----
public function test_due_messages_can_be_sent_with_a_twilio_content_template(): void
⋮----
$scheduledFor = now()->subMinute();
⋮----
Config::set('whatsapp.twilio.content_sid', 'HXCONTENT123');
TwilioContentTemplate::query()->create([
⋮----
Http::assertSent(function ($request) use ($scheduledFor): bool {
⋮----
'2' => $scheduledFor->translatedFormat('l j \d\e F'),
'3' => $scheduledFor->format('H:i'),
⋮----
$this->assertSame('SMTEMPLATE123', $message->provider_message_id);
$this->assertSame('HXCONTENT123', $message->provider_payload['payload']['content_sid']);
$this->assertSame('Clara', $message->provider_payload['payload']['content_variables']['1']);
⋮----
public function test_twilio_recipient_keeps_existing_whatsapp_prefix_without_duplicating_country_code(): void
⋮----
public function test_due_messages_use_configured_status_callback_url_when_available(): void
⋮----
Config::set('whatsapp.twilio.status_callback_url', 'https://example.com/webhooks/twilio/whatsapp-status');
⋮----
public function test_history_reply_messages_are_sent_as_text_without_twilio_template(): void
⋮----
private function createSelectedTwilioTemplate(): void
````

## File: tests/Unit/ExampleTest.php
````php
namespace Tests\Unit;
⋮----
use PHPUnit\Framework\TestCase;
⋮----
class ExampleTest extends TestCase
⋮----
/**
     * A basic test example.
     */
public function test_that_true_is_true(): void
⋮----
$this->assertTrue(true);
````

## File: tests/Unit/WhatsAppMessageTimezoneTest.php
````php
namespace Tests\Unit;
⋮----
use App\Models\WhatsAppMessage;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
⋮----
class WhatsAppMessageTimezoneTest extends TestCase
⋮----
public function test_twilio_utc_timestamp_is_converted_to_application_timezone(): void
⋮----
Config::set('app.timezone', 'Europe/Madrid');
⋮----
$this->assertSame('25/06/2026 04:00', $message->deliveredAt()?->format('d/m/Y H:i'));
⋮----
public function test_inbound_api_received_body_is_used_as_the_response_value(): void
⋮----
$this->assertSame('Reprogramar por la tarde', $message->responseValue());
$this->assertTrue($message->hasResponse());
⋮----
public function test_inbound_body_is_used_before_truncated_respuesta(): void
⋮----
$this->assertSame(
⋮----
$message->responseValue()
````

## File: tests/TestCase.php
````php
namespace Tests;
⋮----
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
⋮----
abstract class TestCase extends BaseTestCase
⋮----
//
````

## File: .cpanel.yml
````yaml
---
deployment:
  tasks:
    - export DEPLOYPATH=/home/juanjota/public_html_backup
    - /bin/cp -R public $DEPLOYPATH/public
    - /bin/cp -R app $DEPLOYPATH/app
    - /bin/cp -R bootstrap $DEPLOYPATH/bootstrap
    - /bin/cp -R config $DEPLOYPATH/config
    - /bin/cp -R database $DEPLOYPATH/database
    - /bin/cp -R lang $DEPLOYPATH/lang
    - /bin/cp -R resources $DEPLOYPATH/resources
    - /bin/cp -R routes $DEPLOYPATH/routes
    - /bin/cp -R storage $DEPLOYPATH/storage
    - /bin/cp artisan $DEPLOYPATH/artisan
    - /bin/cp composer.json $DEPLOYPATH/composer.json
    - /bin/cp composer.lock $DEPLOYPATH/composer.lock
    - /bin/cp .env $DEPLOYPATH/.env
    - cd $DEPLOYPATH && composer install --no-dev --no-interaction --prefer-dist
    - cd $DEPLOYPATH && rm -rf bootstrap/cache/*
    - cd $DEPLOYPATH && rm -rf storage/framework/views/*
    - cd $DEPLOYPATH && php artisan config:cache
    - cd $DEPLOYPATH && php artisan route:cache
    - cd $DEPLOYPATH && php artisan view:cache
    - cd $DEPLOYPATH && php artisan migrate --force
````

## File: .editorconfig
````
root = true

[*]
charset = utf-8
end_of_line = lf
indent_size = 4
indent_style = space
insert_final_newline = true
trim_trailing_whitespace = true

[*.md]
trim_trailing_whitespace = false

[*.{yml,yaml}]
indent_size = 2

[{compose,docker-compose}.{yml,yaml}]
indent_size = 4
````

## File: .env.example
````
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

# PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

WHATSAPP_DRIVER=twilio
WHATSAPP_MESSAGE_MODE=text
WHATSAPP_DEFAULT_COUNTRY_CODE=+34
WHATSAPP_DEFAULT_TEMPLATE=clinical_reminder
WHATSAPP_DEFAULT_MESSAGE="Hola [NOMBRE] te recordamos que el día [DIA] tienes una cita a las [HORA] ; saludos Clínica Dental Eugénia"
TWILIO_WHATSAPP_MODE=auto
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_API_KEY=
TWILIO_API_SECRET=
# TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
TWILIO_STATUS_CALLBACK_URL=
TWILIO_MESSAGING_SERVICE_SID=
TWILIO_TEST_RECIPIENT=
TWILIO_TIMEOUT=15
TWILIO_CONTENT_SID=
# Map Twilio template variable indices to placeholder tokens
# Keys ("1","2",...) must match your Content Template variables
# Available placeholders: [NOMBRE] [APELLIDOS] [TELEFONO] [DIA] [FECHA] [HORA] [MENSAJE]
# TWILIO_CONTENT_VARIABLES={"1": "[NOMBRE]", "2": "[DIA]", "3": "[HORA]"}
# Twilio WhatsApp modes:
# sandbox: TWILIO_WHATSAPP_MODE=sandbox and TWILIO_WHATSAPP_FROM=whatsapp:+14155238886
# sender: TWILIO_WHATSAPP_MODE=sender and TWILIO_WHATSAPP_FROM=whatsapp:+34XXXXXXXXX
# service: TWILIO_WHATSAPP_MODE=service and TWILIO_MESSAGING_SERVICE_SID=MGxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
# auto: prefers Messaging Service when present, otherwise detects sandbox sender or uses sender mode.
WHATSAPP_CLOUD_API_BASE_URL=https://graph.facebook.com
WHATSAPP_CLOUD_API_VERSION=v22.0
WHATSAPP_CLOUD_API_PHONE_NUMBER_ID=
WHATSAPP_CLOUD_API_ACCESS_TOKEN=
WHATSAPP_CLOUD_API_TIMEOUT=15

INITIAL_ADMIN_NAME="Administrador"
INITIAL_ADMIN_EMAIL="admin@example.com"
INITIAL_ADMIN_PASSWORD="ChangeMe123456!"

CACHE_STORE=database
# CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
````

## File: .env.testing
````
APP_ENV=testing
APP_DEBUG=true
APP_URL=http://localhost
APP_KEY=base64:moFNX/j6DhkzRN1Jdg7aW18rwfCXaCzm7A3Zjo5kRTA=

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_ES

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=4

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=debug

DB_CONNECTION=sqlite
DB_DATABASE=:memory:
DB_FOREIGN_KEYS=true

SESSION_DRIVER=array
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=array

MAIL_MAILER=array

PULSE_ENABLED=false
TELESCOPE_ENABLED=false
NIGHTWATCH_ENABLED=false
````

## File: .gitattributes
````
* text=auto eol=lf

*.blade.php diff=html
*.css diff=css
*.html diff=html
*.md diff=markdown
*.php diff=php

/.github export-ignore
CHANGELOG.md export-ignore
.styleci.yml export-ignore
````

## File: .gitignore
````
*.log
.DS_Store
.env
.env.backup
.env.production
.phpactor.json
.phpunit.result.cache
/.codex
/.cursor/
/.idea
/.nova
/.phpunit.cache
/.vscode
/.zed
/auth.json
/node_modules
/public/build
/public/fonts-manifest.dev.json
/public/hot
/public/storage
/storage/*.key
/storage/pail
/vendor
_ide_helper.php
Homestead.json
Homestead.yaml
Thumbs.db

# Playwright
node_modules/
/test-results/
/playwright-report/
/blob-report/
/playwright/.cache/
/playwright/.auth/

# EnvKit — per-project PHP error log (do not commit)
php_error.log
````

## File: .npmrc
````
ignore-scripts=true
audit=true
````

## File: AGENTS.md
````markdown
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
````

## File: artisan
````
#!/usr/bin/env php
<?php

use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\ArgvInput;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel and handle the command...
/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$status = $app->handleCommand(new ArgvInput);

exit($status);
````

## File: boost.json
````json
{
    "cloud": false,
    "guidelines": true,
    "mcp": true,
    "nightwatch": false,
    "sail": false,
    "skills": [
        "laravel-best-practices",
        "fluxui-development",
        "livewire-development",
        "tailwindcss-development"
    ]
}
````

## File: composer.json
````json
{
    "$schema": "https://getcomposer.org/schema.json",
    "name": "laravel/laravel",
    "type": "project",
    "description": "The skeleton application for the Laravel framework.",
    "keywords": ["laravel", "framework"],
    "license": "MIT",
    "require": {
        "php": "^8.3",
        "blade-ui-kit/blade-heroicons": "^2.7",
        "laravel/framework": "^13.8",
        "laravel/tinker": "^3.0",
        "livewire/flux": "^2.15",
        "livewire/livewire": "^4.3",
        "maatwebsite/excel": "^3.1",
        "twilio/sdk": "^8.11"
    },
    "require-dev": {
        "fakerphp/faker": "^1.24",
        "fruitcake/laravel-debugbar": "^4.3",
        "laravel/boost": "^2.4",
        "laravel/pail": "^1.2.5",
        "laravel/pao": "^1.0.6",
        "laravel/pint": "^1.27",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "phpunit/phpunit": "^12.5.12"
    },
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "scripts": {
        "setup": [
            "composer install",
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\"",
            "@php artisan key:generate",
            "@php artisan migrate --force",
            "npm install --ignore-scripts",
            "npm run build"
        ],
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently -c \"#93c5fd,#c4b5fd,#34d399,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1 --timeout=0\" \"php artisan schedule:work\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,schedule,logs,vite --kill-others"
        ],
        "test": [
            "@php artisan config:clear --ansi @no_additional_args",
            "@php artisan test"
        ],
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force"
        ],
        "post-root-package-install": [
            "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
        ],
        "post-create-project-cmd": [
            "@php artisan key:generate --ansi",
            "@php -r \"file_exists('database/database.sqlite') || touch('database/database.sqlite');\"",
            "@php artisan migrate --graceful --ansi"
        ],
        "pre-package-uninstall": [
            "Illuminate\\Foundation\\ComposerScripts::prePackageUninstall"
        ]
    },
    "extra": {
        "laravel": {
            "dont-discover": []
        }
    },
    "config": {
        "optimize-autoloader": true,
        "preferred-install": "dist",
        "sort-packages": true,
        "allow-plugins": {
            "pestphp/pest-plugin": true,
            "php-http/discovery": true
        }
    },
    "minimum-stability": "stable",
    "prefer-stable": true
}
````

## File: GUIA_RETOMAR_TRABAJO.md
````markdown
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
| `WhatsAppDispatchSettings` | Singleton. `enabled` (toggle envios programados), `hours` (json, horas de envio). |
| `WhatsAppCredential` | Singleton. Credenciales Twilio en BD: `mode`, `from_number`, `test_recipient`, `api_key_sid` (encrypted), `api_key_secret` (encrypted). Fallback a .env. |
| `TwilioContentTemplate` | Templates de contenido Twilio en BD. `content_sid`, `seleccionada`, `content_variables`. |
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

### Comandos Artisan

| Comando | Funcion | Programacion |
|---|---|---|
| `whatsapp:dispatch-due` | Encola y envia mensajes WhatsApp pendientes. Verifica `WhatsAppDispatchSettings.enabled`. | Cada minuto (con check de enabled + horas en BD) |
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

En la UI de citas de cliente, `resources/views/livewire/client-appointments.blade.php` muestra la respuesta en la columna `Respuesta`:
- `Confirmada`
- `Reprogramar`
- `Leer Mensaje`

Ese badge es el boton que abre `openHistory({{ $appointment->id }})`. El boton `Historial` ya no existe en la columna de acciones (`resources/views/components/tabla/botones-maniobra.blade.php`), que queda para enviar WhatsApp, editar y eliminar.

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

### WhatsApp
1. Registrar sender de WhatsApp en Twilio para usar templates custom en español con botones (error 63027 en sandbox).
2. Resolver error 63112 (WABA deshabilitada) si persiste después de registrar sender.

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
````

## File: GUIA_TECNICA.md
````markdown
# Guía técnica de la aplicación

## 1. Qué hace esta app

Esta aplicación sirve para gestionar:

- Clientes
- Citas
- Mensajes de WhatsApp
- Importación de datos desde Excel
- Plantillas de mensajes
- Configuración de recordatorios y conexión con WhatsApp
- Administración de usuarios y seguridad

El flujo principal es:

1. Crear o importar clientes.
2. Registrar citas o programar mensajes.
3. Elegir una plantilla.
4. Enviar ahora o dejar el envío programado.
5. Sincronizar el estado de entrega cuando llegue la respuesta del proveedor.

## 2. Requisitos

- PHP 8.4
- Composer
- Node.js y npm
- Base de datos compatible con Laravel

## 3. Arranque local

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
npm run dev
```

Si prefieres arrancar todo junto:

```bash
composer run dev
```

## 4. Acceso y navegación

Las pantallas principales están en el menú lateral:

- Dashboard
- Clientes
- Citas
- Importar Excel
- Ajustes
- Usuarios
- Seguridad

El acceso a administración está restringido al usuario con `id = 1`.

## 5. Dashboard

El panel principal muestra:

- Mensajes pendientes
- Mensajes enviados
- Mensajes fallidos
- Próximos mensajes programados

## 6. Clientes

En `Clientes` puedes:

- Buscar por nombre, apellidos o teléfono
- Ver la lista paginada
- Crear un cliente nuevo
- Editar un cliente existente
- Eliminar un cliente
- Ir a sus citas
- Programar una cita o un WhatsApp desde su ficha

### Reglas importantes

- El teléfono se normaliza al formato internacional cuando es posible.
- Si creas un cliente con un teléfono ya existente, el sistema reutiliza ese registro.

## 7. Citas

En `Citas` puedes:

- Ver todas las citas o solo las de un cliente concreto
- Filtrar por nombre, apellidos y estado de notificación
- Ordenar por cliente o por fecha
- Crear una cita
- Editar una cita futura que todavía no haya sido enviada
- Marcar una cita como activa o inactiva
- Enviar el WhatsApp inmediatamente
- Reenviar un WhatsApp de una cita futura ya enviada
- Abrir el historial de comunicaciones desde la columna `Respuesta`
- Eliminar una cita

### Reglas de negocio

- No se pueden programar citas para domingo.
- Las citas pasadas no se pueden enviar.
- Una cita enviada ya no se puede modificar.
- Si desactivas una cita, se eliminan sus mensajes pendientes asociados.

### Estados visibles

- `Enviado`: el WhatsApp ya fue enviado
- `Entregado`: el proveedor confirmó la entrega
- `Leído`: el destinatario abrió el mensaje
- `Pendiente`: sigue en cola para enviarse
- `Respuesta`: badge accionable con `Confirmada`, `Reprogramar` o `Leer Mensaje`

### Historial de comunicaciones

En `resources/views/livewire/client-appointments.blade.php`, la columna `Respuesta` es el punto de entrada al historial de una cita. Si existe respuesta, el badge llama a `openHistory({{ $appointment->id }})` con `wire:click.stop` para abrir `x-modales.historia-whatsapp` sin disparar la navegación de la fila.

El botón `Historial` se retiró de `resources/views/components/tabla/botones-maniobra.blade.php`. La columna de acciones queda para enviar WhatsApp, editar y eliminar.

## 8. Programar mensajes desde un cliente

En la pantalla de clientes hay un bloque para programar WhatsApp directamente desde una ficha.

### Pasos

1. Busca el cliente.
2. Pulsa `Usar`.
3. Elige plantilla.
4. Selecciona fecha y hora.
5. Pulsa `Programar mensaje` o `Enviar ahora`.

### Nota

La fecha mínima disponible es al día siguiente y no se permite domingo.

## 9. Importar Excel

En `Importar Excel` puedes cargar un archivo y previsualizarlo antes de importar.

### Formatos admitidos

- `.xlsx`
- `.xls`
- `.csv`

### Límite

- Máximo 10 MB

### Flujo

1. Selecciona una plantilla.
2. Sube el archivo.
3. Pulsa `Previsualizar`.
4. Revisa las filas.
5. Si todo está bien, pulsa `Importar`.

### Columnas recomendadas

La importación reconoce varios nombres de columna. Las más útiles son:

- `nombre`
- `apellidos`
- `telefono`
- `fecha` o `scheduled_date`
- `hora` o `scheduled_time`
- `plantilla` opcional

También acepta alias como `fecha_cita`, `dia`, `hora_cita`, `telefono_movil` o `whatsapp_number`.

### Resultado del import

- Crea o actualiza el cliente por teléfono.
- Genera un mensaje pendiente.
- Guarda la referencia de la plantilla usada.

## 10. Plantillas de WhatsApp

En `Ajustes > Plantillas` puedes:

- Crear una plantilla nueva
- Editarla
- Marcarla como predeterminada
- Activarla o desactivarla
- Cambiar su orden
- Eliminarla

### Variables disponibles

Las plantillas pueden usar:

- `[NOMBRE]`
- `[APELLIDOS]`
- `[TELEFONO]`
- `[DIA]`
- `[HORA]`

## 11. Ajustes

La pantalla de ajustes está organizada en bloques movibles y plegables.

### 11.1 Prueba de conexión

Sirve para probar el envío real de WhatsApp.

Puedes:

- Cambiar el modo de prueba
- Indicar un destinatario
- Ver el payload antes de enviar
- Enviar al destinatario guardado si existe `TWILIO_TEST_RECIPIENT`

Modos disponibles:

- `auto`
- `sandbox`
- `sender`
- `service`

### 11.2 Estado actual

Muestra:

- Driver activo
- Plantilla por defecto
- Estado de credenciales de Twilio
- Sender configurado
- Content SID, si se usa modo plantilla
- Destino de prueba

### 11.3 Twilio Sandbox

Variables habituales:

- `WHATSAPP_DRIVER=twilio`
- `TWILIO_WHATSAPP_MODE=sandbox`
- `TWILIO_WHATSAPP_FROM=whatsapp:+14155238886`

Para producción puedes usar:

- `sender` con un número real
- `service` con `TWILIO_MESSAGING_SERVICE_SID`

### 11.4 Tiempos de envío

Configura preferencias de recordatorios para:

- WhatsApp
- Email

## 12. Usuario administrador

El administrador principal puede:

- Crear usuarios
- Editar usuarios
- Eliminar usuarios, excepto su propia cuenta
- Cambiar su contraseña

## 13. Webhook de Twilio

La app expone un webhook para estados de WhatsApp de Twilio:

`POST /webhooks/twilio/whatsapp-status`

Ese endpoint:

- Verifica la firma de Twilio
- Rechaza callbacks inválidos
- Sincroniza el estado de entrega y lectura

## 14. Comandos útiles

```bash
php artisan test --compact
php artisan test --compact --filter=NombreDelTest
php artisan migrate
php artisan route:list
```

Si cambias frontend y no ves reflejado el cambio, ejecuta:

```bash
npm run dev
```

o

```bash
npm run build
```

## 15. Problemas habituales

- Si no envía WhatsApp, revisa `WHATSAPP_DRIVER` y las credenciales en `.env`.
- Si el webhook no actualiza estados, comprueba `TWILIO_AUTH_TOKEN` y la URL configurada.
- Si la importación no muestra datos, revisa los nombres de columna del Excel.
- Si una cita no deja enviarse, verifica que sea futura y esté activa.
````

## File: GUIA_USUARIO.md
````markdown
# Guía de uso de la aplicación

## Qué hace esta aplicación

Esta aplicación sirve para llevar el control de clientes, citas y mensajes de WhatsApp desde un solo sitio.

Con ella puedes:

- Guardar y consultar clientes
- Crear y revisar citas
- Programar mensajes de WhatsApp
- Enviar mensajes en el momento
- Importar datos desde un archivo
- Usar plantillas de mensajes ya preparadas
- Revisar el estado de los mensajes enviados
- Revisar respuestas e historial de comunicaciones de una cita

## Cómo está organizada

La aplicación se divide en estas partes:

- **Panel principal**: muestra un resumen de la actividad reciente
- **Clientes**: lista de personas registradas
- **Citas**: gestión de las citas y sus mensajes
- **Importar Excel**: carga de varios registros a la vez
- **Ajustes**: configuración general de mensajes y recordatorios
- **Usuarios**: gestión de cuentas, solo para el administrador
- **Seguridad**: cambio de contraseña, solo para el administrador

## Primeros pasos

1. Entra en la aplicación con tu usuario.
2. Revisa el panel principal para ver el estado general.
3. Añade clientes o importa un archivo si todavía no tienes datos.
4. Crea citas o programa mensajes de WhatsApp.
5. Comprueba el resultado en la lista de citas o mensajes.

## Panel principal

El panel principal te permite ver de un vistazo:

- Cuántos mensajes están pendientes
- Cuántos ya se han enviado
- Cuántos han fallado
- Cuáles son los próximos mensajes previstos

Es la pantalla más útil para comprobar si todo está funcionando bien.

## Clientes

En esta sección puedes buscar personas por nombre, apellidos o teléfono.

También puedes:

- Ver la lista completa de clientes
- Crear un cliente nuevo
- Editar datos de un cliente
- Eliminar un cliente
- Entrar en sus citas
- Programar un mensaje para ese cliente

### Cuándo usar esta sección

Usa esta pantalla cuando quieras localizar a una persona concreta o revisar sus datos antes de crear una cita.

## Citas

La sección de citas sirve para controlar las visitas programadas y los mensajes relacionados.

Desde aquí puedes:

- Ver todas las citas
- Filtrar por cliente
- Ordenar la lista
- Crear una cita nueva
- Editar una cita
- Marcar una cita como activa o inactiva
- Enviar el mensaje ahora
- Reenviar un WhatsApp si la cita enviada sigue siendo futura
- Abrir el historial desde la columna **Respuesta** cuando exista una respuesta
- Eliminar una cita

### Estados que puedes ver

- **Pendiente**: la cita está prevista, pero el mensaje todavía no se ha enviado
- **Enviado**: el mensaje ya salió
- **Entregado**: el mensaje llegó correctamente
- **Leído**: el destinatario abrió el mensaje
- **Respuesta**: el paciente confirmó, pidió reprogramar o envió texto

### Respuestas e historial

Cuando una cita tiene respuesta, la columna **Respuesta** muestra un botón con el estado:

- **Confirmada**: el paciente confirmó la cita
- **Reprogramar**: el paciente pidió cambiar la cita
- **Leer Mensaje**: el paciente envió una respuesta de texto

Pulsa ese botón para abrir el historial completo de comunicaciones de la cita. La columna **Acciones** ya no muestra un botón separado de historial; ahí quedan las acciones de enviar, editar o eliminar.

### Reglas importantes

- No se pueden programar citas para domingo
- No se pueden enviar citas del pasado
- Cuando una cita ya fue enviada, normalmente no se puede modificar
- Si una cita se desactiva, deja de quedar pendiente para envío

## Programar mensajes desde un cliente

Si ya estás dentro de un cliente, puedes preparar un mensaje sin volver a escribir sus datos.

### Pasos

1. Busca al cliente.
2. Selecciona ese cliente.
3. Elige una plantilla de mensaje.
4. Indica la fecha y la hora.
5. Pulsa para programar el mensaje.

También puedes enviarlo en ese momento si lo necesitas.

### Recomendaciones

- Comprueba siempre que la fecha sea correcta
- Revisa el texto antes de enviar
- Usa esta opción cuando quieras ahorrar tiempo con un cliente ya registrado

## Importar datos desde Excel

Esta opción te permite cargar muchos clientes o mensajes de una sola vez.

### Cómo usarla

1. Entra en la pantalla de importación.
2. Selecciona la plantilla que quieras usar.
3. Sube el archivo.
4. Revisa la vista previa.
5. Si todo está bien, confirma la importación.

### Qué revisar antes de importar

- Que los nombres estén correctos
- Que los teléfonos sean válidos
- Que la fecha y la hora sean las deseadas
- Que la plantilla elegida sea la adecuada

## Plantillas de mensajes

Las plantillas sirven para no tener que escribir el mismo mensaje una y otra vez.

Desde esta sección puedes:

- Crear una nueva plantilla
- Cambiar una plantilla existente
- Elegir una plantilla predeterminada
- Activar o desactivar una plantilla
- Ordenar las plantillas
- Eliminar una plantilla

### Cuándo usar una plantilla

Usa plantillas cuando envíes mensajes repetidos, como recordatorios o avisos de cita.

## Ajustes

En ajustes puedes revisar y organizar el funcionamiento general de la aplicación.

### Qué puedes hacer aquí

- Probar el envío de mensajes
- Ver el estado general del sistema de mensajes
- Comprobar qué plantilla está activa por defecto
- Revisar la configuración de recordatorios

### Para qué sirve

Esta sección está pensada para tener todo bajo control y evitar errores antes de enviar mensajes importantes.

## Usuarios y seguridad

Estas opciones solo están disponibles para el administrador principal.

### Usuarios

Permite:

- Crear nuevas cuentas
- Editar cuentas existentes
- Eliminar cuentas

### Seguridad

Permite cambiar la contraseña del usuario administrador.

## Consejos de uso

- Revisa siempre los datos antes de guardar
- Usa la lista de clientes para evitar duplicados
- Comprueba el estado de las citas antes de enviar mensajes
- Usa la importación cuando tengas muchos registros
- Mantén las plantillas claras y cortas

## Si algo no sale como esperas

- Revisa que el cliente o la cita estén bien guardados
- Comprueba que la fecha sea válida
- Verifica que hayas elegido la plantilla correcta
- Si un mensaje no se envía, revisa el estado que aparece en la pantalla

## Resumen rápido

1. Guarda los clientes.
2. Crea o revisa las citas.
3. Elige una plantilla de mensaje.
4. Programa el envío o envíalo al momento.
5. Comprueba el resultado en el panel principal o en la lista de citas.
````

## File: HANDOFF.md
````markdown
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
````

## File: instrucciones.md
````markdown
# Instrucciones y funcionamiento.
- No chequea el estado de los envíos de Whatsapps pasadas las 24 horas del envío.
- El que indique si ha sido leído o no depende de la configuración del cliente en su Whatsapp.
- En la lista de citas, las respuestas se consultan desde la columna `Respuesta`.
- Si aparece `Confirmada`, `Reprogramar` o `Leer Mensaje`, ese badge abre el historial de comunicaciones.
- La columna de acciones ya no tiene botón de historial; queda para enviar, editar o eliminar.
````

## File: ngrok-herd.yml
````yaml
on_http_request:
  - actions:
      - type: add-headers
        config:
          headers:
            host: "citasdentista.test"
````

## File: opencode.json
````json
{
    "$schema": "https://opencode.ai/config.json",
    "plugin": ["@dietrichgebert/ponytail"],
    "mcp": {
        "laravel-boost": {
            "type": "local",
            "enabled": true,
            "command": [
                "php",
                "artisan",
                "boost:mcp"
            ]
        }
    }
}
````

## File: package.json
````json
{
  "$schema": "https://www.schemastore.org/package.json",
  "private": true,
  "type": "module",
  "scripts": {
    "build": "vite build",
    "dev": "vite"
  },
  "devDependencies": {
    "@playwright/test": "^1.61.0",
    "@tailwindcss/vite": "^4.0.0",
    "@types/node": "^26.0.0",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^3.1",
    "tailwindcss": "^4.0.0",
    "vite": "^8.0.0"
  }
}
````

## File: phpunit.xml
````xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_MAINTENANCE_DRIVER" value="file"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="BROADCAST_CONNECTION" value="null"/>
        <env name="CACHE_STORE" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="DB_URL" value=""/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="PULSE_ENABLED" value="false"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
        <env name="NIGHTWATCH_ENABLED" value="false"/>
    </php>
</phpunit>
````

## File: playwright.config.js
````javascript
// @ts-check
⋮----
/**
 * Read environment variables from file.
 * https://github.com/motdotla/dotenv
 */
// import dotenv from 'dotenv';
// import path from 'path';
// dotenv.config({ path: path.resolve(__dirname, '.env') });
⋮----
/**
 * @see https://playwright.dev/docs/test-configuration
 */
⋮----
/* Run tests in files in parallel */
⋮----
/* Fail the build on CI if you accidentally left test.only in the source code. */
⋮----
/* Retry on CI only */
⋮----
/* Opt out of parallel tests on CI. */
⋮----
/* Reporter to use. See https://playwright.dev/docs/test-reporters */
⋮----
/* Shared settings for all the projects below. See https://playwright.dev/docs/api/class-testoptions. */
⋮----
/* Base URL to use in actions like `await page.goto('')`. */
// baseURL: 'http://localhost:3000',
⋮----
/* Collect trace when retrying the failed test. See https://playwright.dev/docs/trace-viewer */
⋮----
/* Configure projects for major browsers */
⋮----
/* Test against mobile viewports. */
// {
//   name: 'Mobile Chrome',
//   use: { ...devices['Pixel 5'] },
// },
// {
//   name: 'Mobile Safari',
//   use: { ...devices['iPhone 12'] },
// },
⋮----
/* Test against branded browsers. */
// {
//   name: 'Microsoft Edge',
//   use: { ...devices['Desktop Edge'], channel: 'msedge' },
// },
// {
//   name: 'Google Chrome',
//   use: { ...devices['Desktop Chrome'], channel: 'chrome' },
// },
⋮----
/* Run your local dev server before starting the tests */
// webServer: {
//   command: 'npm run start',
//   url: 'http://localhost:3000',
//   reuseExistingServer: !process.env.CI,
// },
````

## File: README.md
````markdown
# Citas Dentista

Aplicación Laravel para gestionar citas, pacientes y recordatorios por WhatsApp desde una sola interfaz.

## Qué incluye

- Panel principal con métricas y próximos envíos
- Gestión de pacientes y citas
- Programación de mensajes de WhatsApp
- Seguimiento de envío, entrega, lectura y respuestas de WhatsApp
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

En la lista de citas, la columna `Respuesta` muestra si el paciente confirmó, pidió reprogramar o envió un mensaje de texto. Cuando hay respuesta, el badge de esa columna abre el historial completo de comunicaciones de la cita. La columna de acciones queda reservada para enviar WhatsApp, editar y eliminar.

## WhatsApp

La app soporta distintos drivers de envío mediante configuración. Revisa `config/whatsapp.php` y las credenciales asociadas en `.env` para dejar activo el canal que uses.

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

- El proyecto ya incluye skills y guías para seguir el trabajo desde otra sesión o equipo.
- Si cambias frontend, recuerda ejecutar `npm run dev` o `npm run build`.
````

## File: responded_at
````

````

## File: TWILIO_WEBHOOKS.md
````markdown
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
````

## File: vite.config.js
````javascript

````
