<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $indexName = $this->appointmentIdUniqueIndexName();

        if ($indexName !== null) {
            Schema::table('whatsapp_messages', function (Blueprint $table) use ($indexName): void {
                $table->dropUnique($indexName);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->appointmentIdUniqueIndexName() === null) {
            Schema::table('whatsapp_messages', function (Blueprint $table): void {
                $table->unique('appointment_id');
            });
        }
    }

    private function appointmentIdUniqueIndexName(): ?string
    {
        foreach (Schema::getIndexes('whatsapp_messages') as $index) {
            if (
                ($index['unique'] ?? false)
                && ($index['columns'] ?? []) === ['appointment_id']
            ) {
                return $index['name'];
            }
        }

        return null;
    }
};
