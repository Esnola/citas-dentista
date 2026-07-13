<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('twilio_content_templates', 'seleccionada')) {
            return;
        }

        Schema::table('twilio_content_templates', function (Blueprint $table): void {
            $table->dropColumn('seleccionada');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('twilio_content_templates', 'seleccionada')) {
            return;
        }

        Schema::table('twilio_content_templates', function (Blueprint $table): void {
            $table->boolean('seleccionada')->default(false)->index();
        });
    }
};
