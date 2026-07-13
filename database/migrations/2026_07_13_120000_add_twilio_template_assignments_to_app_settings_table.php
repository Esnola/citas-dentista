<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table): void {
            $table->foreignId('twilio_template_appointment_reminder_id')
                ->nullable()
                ->after('dispatch_hours')
                ->constrained('twilio_content_templates')
                ->nullOnDelete();

            $table->foreignId('twilio_template_appointment_created_id')
                ->nullable()
                ->after('twilio_template_appointment_reminder_id')
                ->constrained('twilio_content_templates')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('twilio_template_appointment_created_id');
            $table->dropConstrainedForeignId('twilio_template_appointment_reminder_id');
        });
    }
};
