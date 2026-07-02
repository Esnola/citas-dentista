<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->stripSpanishPrefix('clients');
        $this->stripSpanishPrefix('whatsapp_messages');
    }

    public function down(): void
    {
        $this->addSpanishPrefix('clients');
        $this->addSpanishPrefix('whatsapp_messages');
    }

    private function stripSpanishPrefix(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        DB::table($table)
            ->where('telefono', 'like', '+34%')
            ->eachById(fn (object $row) => DB::table($table)->where('id', $row->id)->update([
                'telefono' => substr($row->telefono, 3),
            ]));
    }

    private function addSpanishPrefix(string $table): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        DB::table($table)
            ->where('telefono', 'not like', '+%')
            ->eachById(fn (object $row) => DB::table($table)->where('id', $row->id)->update([
                'telefono' => '+34'.$row->telefono,
            ]));
    }
};
