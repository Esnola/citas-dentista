<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('twilio_content_templates', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('content_sid', 34)->unique();
            $table->boolean('seleccionada')->default(false)->index();
            $table->json('content_variables')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('twilio_content_templates');
    }
};
