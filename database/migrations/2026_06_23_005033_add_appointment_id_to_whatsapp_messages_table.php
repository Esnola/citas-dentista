<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table): void {
            if (! Schema::hasColumn('whatsapp_messages', 'appointment_id')) {
                $table->foreignId('appointment_id')
                    ->nullable()
                    ->after('client_id')
                    ->constrained('appointments')
                    ->nullOnDelete()
                    ->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table): void {
            if (Schema::hasColumn('whatsapp_messages', 'appointment_id')) {
                $table->dropConstrainedForeignId('appointment_id');
            }
        });
    }
};
