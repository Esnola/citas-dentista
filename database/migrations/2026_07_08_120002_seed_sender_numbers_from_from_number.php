<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('whatsapp_credentials') || ! Schema::hasTable('whatsapp_sender_numbers')) {
            return;
        }

        $credentials = DB::table('whatsapp_credentials')->whereNotNull('from_number')->get();

        foreach ($credentials as $credential) {
            $fromNumber = $credential->from_number;

            // Strip whatsapp: prefix if present
            $cleaned = preg_replace('/^whatsapp:/i', '', $fromNumber);
            $digits = preg_replace('/\D+/', '', $cleaned);

            // Extract prefix (assume +34 if starts with 6/7/8/9 for Spain, otherwise try to detect)
            $prefix = '+34';
            $number = $digits;

            if (str_starts_with($digits, '34') && strlen($digits) > 9) {
                $prefix = '+34';
                $number = substr($digits, 2);
            } elseif (str_starts_with($digits, '1') && strlen($digits) > 10) {
                $prefix = '+1';
                $number = substr($digits, 1);
            }

            DB::table('whatsapp_sender_numbers')->insert([
                'whatsapp_credential_id' => $credential->id,
                'prefix' => $prefix,
                'number' => $number,
                'selected' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('whatsapp_sender_numbers')->delete();
    }
};
