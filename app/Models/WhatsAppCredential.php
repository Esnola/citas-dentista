<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class WhatsAppCredential extends Model
{
    protected $table = 'whatsapp_credentials';

    protected $fillable = [
        'mode',
        'api_key_sid',
        'api_key_secret',
        'status_callback_url',
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

    public function senderNumbers(): HasMany
    {
        return $this->hasMany(WhatsAppSenderNumber::class, 'whatsapp_credential_id');
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
                'selected' => true,
            ]);
        }

        return $credential;
    }

    public function selectedSenderNumber(): ?WhatsAppSenderNumber
    {
        return $this->senderNumbers()->selected()->first();
    }

    public function resolveFrom(): ?string
    {
        $selected = $this->selectedSenderNumber();

        if ($selected) {
            return $selected->whatsapp_address;
        }

        return config('whatsapp.twilio.from');
    }

    public function resolveApiKeySid(): ?string
    {
        return $this->api_key_sid ?: config('whatsapp.twilio.api_key_sid');
    }

    public function resolveApiKeySecret(): ?string
    {
        return $this->api_key_secret ?: config('whatsapp.twilio.api_key_secret');
    }

    public function resolveMode(): string
    {
        return $this->mode ?: config('whatsapp.twilio.mode', 'sandbox');
    }

    public function resolveStatusCallbackUrl(): string
    {
        $configuredUrl = trim((string) ($this->status_callback_url ?? ''));

        return $configuredUrl !== ''
            ? $configuredUrl
            : (string) config('whatsapp.twilio.status_callback_url', '');
    }
}
