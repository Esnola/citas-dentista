<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_sender_numbers', function (Blueprint $table) {
            $table->string('name', 100)->nullable()->after('whatsapp_credential_id');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_sender_numbers', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
};
