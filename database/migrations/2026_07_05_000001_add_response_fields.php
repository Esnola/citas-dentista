<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->string('respuesta', 50)->nullable()->after('metadata');
            $table->dateTime('responded_at')->nullable()->after('respuesta');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('confirmada')->default(false)->index()->after('entregado');
            $table->boolean('pendiente_reprogramacion')->default(false)->index()->after('confirmada');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['confirmada', 'pendiente_reprogramacion']);
        });

        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropColumn(['respuesta', 'responded_at']);
        });
    }
};
