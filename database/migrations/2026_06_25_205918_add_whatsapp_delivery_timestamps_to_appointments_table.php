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
        Schema::table('appointments', function (Blueprint $table): void {
            $table->dateTime('whatsapp_sent_at')->nullable()->after('entregado');
            $table->dateTime('whatsapp_delivered_at')->nullable()->after('whatsapp_sent_at');
            $table->dateTime('whatsapp_read_at')->nullable()->after('whatsapp_delivered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table): void {
            $table->dropColumn([
                'whatsapp_sent_at',
                'whatsapp_delivered_at',
                'whatsapp_read_at',
            ]);
        });
    }
};
