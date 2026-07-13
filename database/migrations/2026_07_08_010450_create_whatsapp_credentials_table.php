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
            $table->string('driver')->nullable();
            $table->string('default_country_code', 10)->nullable();
            $table->string('message_mode', 20)->nullable();
            $table->string('mode')->default('sandbox');
            $table->string('account_sid')->nullable();
            $table->text('auth_token')->nullable();
            $table->text('api_key_sid')->nullable();
            $table->text('api_key_secret')->nullable();
            $table->string('content_sid', 34)->nullable();
            $table->string('test_recipient', 40)->nullable();
            $table->unsignedSmallInteger('timeout')->nullable();
            $table->unsignedSmallInteger('connect_timeout')->nullable();
            $table->string('cloud_api_base_url')->nullable();
            $table->string('cloud_api_version', 20)->nullable();
            $table->string('cloud_api_phone_number_id')->nullable();
            $table->text('cloud_api_access_token')->nullable();
            $table->unsignedSmallInteger('cloud_api_timeout')->nullable();
            $table->string('default_template')->nullable();
            $table->text('default_message')->nullable();
            $table->string('status_callback_url')->nullable();
            $table->boolean('webhook_enabled')->default(true);
            $table->unsignedSmallInteger('poll_interval')->default(10);
            $table->boolean('selected')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_credentials');
    }
};
