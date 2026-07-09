<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->string('direction', 10)->default('outbound')->after('status');
            $table->foreignId('parent_id')->nullable()->constrained('whatsapp_messages')->nullOnDelete()->after('appointment_id');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['direction', 'parent_id']);
        });
    }
};
