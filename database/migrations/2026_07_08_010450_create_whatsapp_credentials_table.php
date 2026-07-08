<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('mode')->default('sandbox');
            $table->text('api_key_sid')->nullable();
            $table->boolean('selected')->default(false);
            $table->text('api_key_secret')->nullable();
            $table->string('status_callback_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_credentials');
    }
};
