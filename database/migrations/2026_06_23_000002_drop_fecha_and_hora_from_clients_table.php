<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'hora')) {
                $table->dropColumn('hora');
            }

            if (Schema::hasColumn('clients', 'fecha')) {
                $table->dropIndex('clients_fecha_index');
                $table->dropColumn('fecha');
            }
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'fecha')) {
                $table->date('fecha')->nullable()->index();
            }

            if (! Schema::hasColumn('clients', 'hora')) {
                $table->time('hora')->nullable();
            }
        });
    }
};
