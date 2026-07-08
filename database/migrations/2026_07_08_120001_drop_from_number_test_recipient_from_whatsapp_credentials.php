<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_credentials', function (Blueprint $table) {
            $table->dropColumn(['from_number', 'test_recipient']);
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_credentials', function (Blueprint $table) {
            $table->string('from_number')->nullable()->after('mode');
            $table->string('test_recipient')->nullable()->after('from_number');
        });

        // Migrate data back from whatsapp_sender_numbers if possible
        if (Schema::hasTable('whatsapp_sender_numbers')) {
            $selected = DB::table('whatsapp_sender_numbers')
                ->join('whatsapp_credentials', 'whatsapp_credentials.id', '=', 'whatsapp_sender_numbers.whatsapp_credential_id')
                ->where('whatsapp_sender_numbers.selected', true)
                ->select('whatsapp_credentials.id', 'whatsapp_sender_numbers.prefix', 'whatsapp_sender_numbers.number')
                ->first();

            if ($selected) {
                $fullNumber = 'whatsapp:'.$selected->prefix.$selected->number;
                DB::table('whatsapp_credentials')
                    ->where('id', $selected->id)
                    ->update(['from_number' => $fullNumber]);
            }
        }
    }
};
