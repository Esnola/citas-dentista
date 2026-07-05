<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('appointments')
            ->whereNull('fecha_original')
            ->orWhereNull('hora_original')
            ->update([
                'fecha_original' => DB::raw('fecha'),
                'hora_original' => DB::raw('hora'),
            ]);
    }

    public function down(): void
    {
        DB::table('appointments')->update([
            'fecha_original' => null,
            'hora_original' => null,
        ]);
    }
};
