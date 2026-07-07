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
            $table->string('from_number')->nullable();
            $table->string('test_recipient')->nullable();
            $table->text('api_key_sid')->nullable();
            $table->text('api_key_secret')->nullable();
            $table->boolean('selected')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_credentials');
    }
};
