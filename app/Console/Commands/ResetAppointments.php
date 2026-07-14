<?php

namespace App\Console\Commands;

use Database\Seeders\AppointmentSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetAppointments extends Command
{
    protected $signature = 'appointments:reset {--force : Confirm the destructive reset}';

    protected $description = 'Delete all appointments and WhatsApp messages, then run the AppointmentSeeder.';

    public function handle(): int
    {
        if (! $this->option('force')) {
            $this->error('This command is destructive. Re-run with --force.');

            return self::FAILURE;
        }

        $deletedWhatsAppMessages = DB::table('whatsapp_messages')->delete();
        $deletedAppointments = DB::table('appointments')->delete();

        $seeded = $this->callSilent('db:seed', [
            '--class' => AppointmentSeeder::class,
            '--force' => true,
        ]);

        if ($seeded !== self::SUCCESS) {
            $this->error('AppointmentSeeder failed.');

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Deleted %d WhatsApp message(s) and %d appointment(s).',
            $deletedWhatsAppMessages,
            $deletedAppointments,
        ));
        $this->info('AppointmentSeeder executed.');

        return self::SUCCESS;
    }
}
