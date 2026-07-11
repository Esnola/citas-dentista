<?php

namespace App\Console\Commands;

use Database\Seeders\AppointmentSeeder;
use Database\Seeders\ClientSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetClientData extends Command
{
    private const PROTECTED_TABLES = [
        'appointment_reminder_preferences',
        'migrations',
        'sistema_opciones',
        'sqlite_sequence',
        'twilio_content_templates',
        'users',
        'whatsapp_credentials',
        'whatsapp_dispatch_settings',
        'whatsapp_sender_numbers',
        'whatsapp_messages',
    ];

    protected $signature = 'clients:reset-data {--force : Confirm the destructive reset}';

    protected $description = 'Delete and restart all data tables except protected settings tables.';

    public function handle(): int
    {
        if (! $this->option('force')) {
            $this->error('This command is destructive. Re-run with --force.');

            return self::FAILURE;
        }

        $tables = collect(Schema::getTables())
            ->pluck('name')
            ->reject(fn (string $table): bool => in_array($table, self::PROTECTED_TABLES, true))
            ->values();

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($tables as $table) {
                DB::table($table)->delete();
                $this->restartIdentity($table);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        $this->info(sprintf(
            'Deleted and restarted %d table(s): %s.',
            $tables->count(),
            $tables->implode(', '),
        ));
        $this->callSilent('db:seed', [
            '--class' => ClientSeeder::class,
            '--force' => true,
        ]);
        $this->callSilent('db:seed', [
            '--class' => AppointmentSeeder::class,
            '--force' => true,
        ]);

        $this->info('ClientSeeder and AppointmentSeeder executed.');
        $this->info('Protected tables were not changed.');

        return self::SUCCESS;
    }

    private function restartIdentity(string $table): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE '.$this->wrapTable($table).' AUTO_INCREMENT = 1');

            return;
        }

        if ($driver === 'sqlite' && Schema::hasTable('sqlite_sequence')) {
            DB::table('sqlite_sequence')->where('name', $table)->delete();
        }
    }

    private function wrapTable(string $table): string
    {
        return DB::connection()->getQueryGrammar()->wrapTable($table);
    }
}
