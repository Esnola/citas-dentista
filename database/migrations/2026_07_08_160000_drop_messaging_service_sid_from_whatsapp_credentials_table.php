<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_credentials', function (Blueprint $table): void {
            if (Schema::hasColumn('whatsapp_credentials', 'messaging_service_sid')) {
                $table->dropColumn('messaging_service_sid');
            }
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_credentials', function (Blueprint $table): void {
            $table->string('messaging_service_sid')->nullable()->after('auth_token');
        });
    }
};
