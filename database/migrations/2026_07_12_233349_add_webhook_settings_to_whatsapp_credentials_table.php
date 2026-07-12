<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_credentials', function (Blueprint $table) {
            $table->boolean('webhook_enabled')->default(true)->after('status_callback_url');
            $table->unsignedSmallInteger('poll_interval')->default(10)->after('webhook_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_credentials', function (Blueprint $table) {
            $table->dropColumn(['webhook_enabled', 'poll_interval']);
        });
    }
};
