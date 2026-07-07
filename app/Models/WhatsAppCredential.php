<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class WhatsAppCredential extends Model
{
    protected $table = 'whatsapp_credentials';

    protected $fillable = [
        'mode',
        'from_number',
        'test_recipient',
        'api_key_sid',
        'api_key_secret',
        'selected',
    ];

    protected function casts(): array
    {
        return [
            'api_key_sid' => 'encrypted',
            'api_key_secret' => 'encrypted',
            'selected' => 'boolean',
        ];
    }

    public static function get(): static
    {
        if (! Schema::hasTable('whatsapp_credentials')) {
            return new static([
                'mode' => 'sandbox',
                'selected' => false,
            ]);
        }

        $credential = static::where('selected', true)->first();

        if (! $credential) {
            $credential = static::first();
        }

        if (! $credential) {
            $credential = static::create([
                'mode' => config('whatsapp.twilio.mode', 'sandbox'),
                'from_number' => config('whatsapp.twilio.from'),
                'test_recipient' => config('whatsapp.twilio.test_recipient'),
                'selected' => true,
            ]);
        }

        return $credential;
    }

    public function resolveFrom(): ?string
    {
        return $this->from_number ?: config('whatsapp.twilio.from');
    }

    public function resolveApiKeySid(): ?string
    {
        return $this->api_key_sid ?: config('whatsapp.twilio.api_key_sid');
    }

    public function resolveApiKeySecret(): ?string
    {
        return $this->api_key_secret ?: config('whatsapp.twilio.api_key_secret');
    }

    public function resolveTestRecipient(): ?string
    {
        return $this->test_recipient ?: config('whatsapp.twilio.test_recipient');
    }

    public function resolveMode(): string
    {
        return $this->mode ?: config('whatsapp.twilio.mode', 'sandbox');
    }
}
