<?php

namespace App\Console\Commands;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetDatabaseAndSeed extends Command
{
    protected $signature = 'db:reset-and-seed {--force : Confirm the destructive reset}';

    protected $description = 'Delete users, clients, WhatsApp messages and appointments, then run the DatabaseSeeder.';

    public function handle(): int
    {
        if (! $this->option('force')) {
            $this->error('This command is destructive. Re-run with --force.');

            return self::FAILURE;
        }

        $deletedWhatsAppMessages = DB::table('whatsapp_messages')->delete();
        $deletedAppointments = DB::table('appointments')->delete();
        $deletedClients = DB::table('clients')->delete();
        $deletedUsers = DB::table('users')->delete();

        $seeded = $this->callSilent('db:seed', [
            '--class' => DatabaseSeeder::class,
            '--force' => true,
        ]);

        if ($seeded !== self::SUCCESS) {
            $this->error('DatabaseSeeder failed.');

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Deleted %d user(s), %d client(s), %d WhatsApp message(s) and %d appointment(s).',
            $deletedUsers,
            $deletedClients,
            $deletedWhatsAppMessages,
            $deletedAppointments,
        ));
        $this->info('DatabaseSeeder executed.');

        return self::SUCCESS;
    }
}
