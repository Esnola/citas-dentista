<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_sender_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_credential_id')->constrained('whatsapp_credentials')->cascadeOnDelete();
            $table->string('name', 100)->nullable();
            $table->string('prefix', 5)->default('+1');
            $table->string('number', 20);
            $table->boolean('selected')->default(false);
            $table->timestamps();

            $table->index(['whatsapp_credential_id', 'selected']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_sender_numbers');
    }
};
