<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $retentionPeriod = 'disabled';
        $dispatchEnabled = true;
        $dispatchHours = ['09:00', '12:00', '15:00'];

        if (Schema::hasTable('sistema_opciones')) {
            $row = DB::table('sistema_opciones')->first();
            if ($row) {
                $retentionPeriod = $row->retention_period;
            }
        }

        if (Schema::hasTable('whatsapp_dispatch_settings')) {
            $row = DB::table('whatsapp_dispatch_settings')->first();
            if ($row) {
                $dispatchEnabled = $row->enabled;
                $dispatchHours = json_decode($row->hours, true) ?? $dispatchHours;
            }
        }

        DB::table('app_settings')->insert([
            'retention_period' => $retentionPeriod,
            'dispatch_enabled' => $dispatchEnabled,
            'dispatch_hours' => json_encode($dispatchHours),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::dropIfExists('sistema_opciones');
        Schema::dropIfExists('whatsapp_dispatch_settings');
    }

    public function down(): void
    {
        Schema::create('sistema_opciones', function ($table): void {
            $table->id();
            $table->string('retention_period', 20)->default('disabled');
            $table->timestamps();
        });

        Schema::create('whatsapp_dispatch_settings', function ($table): void {
            $table->id();
            $table->boolean('enabled')->default(true);
            $table->json('hours');
            $table->timestamps();
        });

        $row = DB::table('app_settings')->first();

        if ($row) {
            DB::table('sistema_opciones')->insert([
                'retention_period' => $row->retention_period,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('whatsapp_dispatch_settings')->insert([
                'enabled' => $row->dispatch_enabled,
                'hours' => $row->dispatch_hours,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
