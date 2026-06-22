<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('whatsapp_messages', 'nombre')) {
                $table->string('nombre')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('whatsapp_messages', 'apellidos')) {
                $table->string('apellidos')->nullable()->after('nombre');
            }

            if (! Schema::hasColumn('whatsapp_messages', 'telefono')) {
                $table->string('telefono', 40)->nullable()->after('apellidos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_messages', 'telefono')) {
                $table->dropColumn('telefono');
            }

            if (Schema::hasColumn('whatsapp_messages', 'apellidos')) {
                $table->dropColumn('apellidos');
            }

            if (Schema::hasColumn('whatsapp_messages', 'nombre')) {
                $table->dropColumn('nombre');
            }
        });
    }
};
