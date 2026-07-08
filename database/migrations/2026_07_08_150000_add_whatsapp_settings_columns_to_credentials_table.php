<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_credentials', function (Blueprint $table): void {
            $table->string('driver')->nullable()->after('selected');
            $table->string('default_country_code', 10)->nullable()->after('driver');
            $table->string('message_mode', 20)->nullable()->after('default_country_code');
            $table->string('account_sid')->nullable()->after('message_mode');
            $table->text('auth_token')->nullable()->after('account_sid');
            $table->string('messaging_service_sid')->nullable()->after('auth_token');
            $table->string('content_sid', 34)->nullable()->after('messaging_service_sid');
            $table->json('content_variables')->nullable()->after('content_sid');
            $table->string('test_recipient', 40)->nullable()->after('content_variables');
            $table->unsignedSmallInteger('timeout')->nullable()->after('test_recipient');
            $table->unsignedSmallInteger('connect_timeout')->nullable()->after('timeout');
            $table->string('cloud_api_base_url')->nullable()->after('connect_timeout');
            $table->string('cloud_api_version', 20)->nullable()->after('cloud_api_base_url');
            $table->string('cloud_api_phone_number_id')->nullable()->after('cloud_api_version');
            $table->text('cloud_api_access_token')->nullable()->after('cloud_api_phone_number_id');
            $table->unsignedSmallInteger('cloud_api_timeout')->nullable()->after('cloud_api_access_token');
            $table->string('default_template')->nullable()->after('cloud_api_timeout');
            $table->text('default_message')->nullable()->after('default_template');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_credentials', function (Blueprint $table): void {
            $table->dropColumn([
                'driver',
                'default_country_code',
                'message_mode',
                'account_sid',
                'auth_token',
                'messaging_service_sid',
                'content_sid',
                'content_variables',
                'test_recipient',
                'timeout',
                'connect_timeout',
                'cloud_api_base_url',
                'cloud_api_version',
                'cloud_api_phone_number_id',
                'cloud_api_access_token',
                'cloud_api_timeout',
                'default_template',
                'default_message',
            ]);
        });
    }
};
