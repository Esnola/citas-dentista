<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('whatsapp_messages', 'provider_message_id')) {
                $table->string('provider_message_id')->nullable()->after('last_error');
            }

            if (! Schema::hasColumn('whatsapp_messages', 'provider_payload')) {
                $table->json('provider_payload')->nullable()->after('provider_message_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_messages', 'provider_payload')) {
                $table->dropColumn('provider_payload');
            }

            if (Schema::hasColumn('whatsapp_messages', 'provider_message_id')) {
                $table->dropColumn('provider_message_id');
            }
        });
    }
};
