<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('clients', 'deleted_at')) {
            return;
        }

        DB::table('clients')->whereNotNull('deleted_at')->delete();

        Schema::table('clients', function (Blueprint $table): void {
            $table->dropSoftDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('clients', 'deleted_at')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table): void {
            $table->softDeletes();
        });
    }
};
