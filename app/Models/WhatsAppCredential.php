<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class WhatsAppCredential extends Model
{
    protected $table = 'whatsapp_credentials';

    protected $fillable = [
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
        'mode',
        'api_key_sid',
        'api_key_secret',
        'status_callback_url',
        'selected',
    ];

    protected function casts(): array
    {
        return [
            'account_sid' => 'encrypted',
            'api_key_sid' => 'encrypted',
            'api_key_secret' => 'encrypted',
            'auth_token' => 'encrypted',
            'cloud_api_access_token' => 'encrypted',
            'content_variables' => 'array',
            'connect_timeout' => 'integer',
            'cloud_api_timeout' => 'integer',
            'timeout' => 'integer',
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

    public function resolveDriver(): string
    {
        return $this->stringSetting($this->driver, 'whatsapp.driver', 'twilio');
    }

    public function resolveDefaultCountryCode(): string
    {
        return $this->stringSetting($this->default_country_code, 'whatsapp.default_country_code', '+34');
    }

    public function resolveMessageMode(): string
    {
        return $this->stringSetting($this->message_mode, 'whatsapp.message_mode', 'text');
    }

    public function resolveAccountSid(): ?string
    {
        return $this->nullableStringSetting($this->account_sid, 'whatsapp.twilio.account_sid');
    }

    public function resolveAuthToken(): ?string
    {
        return $this->nullableStringSetting($this->auth_token, 'whatsapp.twilio.auth_token');
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

    public function resolveMessagingServiceSid(): ?string
    {
        return $this->nullableStringSetting($this->messaging_service_sid, 'whatsapp.twilio.messaging_service_sid');
    }

    public function resolveContentSid(): ?string
    {
        return $this->nullableStringSetting($this->content_sid, 'whatsapp.twilio.content_sid');
    }

    /**
     * @return array<string, string>
     */
    public function resolveContentVariables(): array
    {
        $variables = $this->content_variables ?? config('whatsapp.twilio.content_variables', []);

        return is_array($variables) ? $variables : [];
    }

    public function resolveTestRecipient(): ?string
    {
        return $this->nullableStringSetting($this->test_recipient, 'whatsapp.twilio.test_recipient');
    }

    public function resolveTimeout(): int
    {
        return $this->integerSetting($this->timeout, 'whatsapp.twilio.timeout', 15);
    }

    public function resolveConnectTimeout(): int
    {
        return $this->integerSetting($this->connect_timeout, 'whatsapp.twilio.connect_timeout', 10);
    }

    public function resolveCloudApiBaseUrl(): string
    {
        return $this->stringSetting($this->cloud_api_base_url, 'whatsapp.cloud_api.base_url', 'https://graph.facebook.com');
    }

    public function resolveCloudApiVersion(): string
    {
        return $this->stringSetting($this->cloud_api_version, 'whatsapp.cloud_api.version', 'v22.0');
    }

    public function resolveCloudApiPhoneNumberId(): ?string
    {
        return $this->nullableStringSetting($this->cloud_api_phone_number_id, 'whatsapp.cloud_api.phone_number_id');
    }

    public function resolveCloudApiAccessToken(): ?string
    {
        return $this->nullableStringSetting($this->cloud_api_access_token, 'whatsapp.cloud_api.access_token');
    }

    public function resolveCloudApiTimeout(): int
    {
        return $this->integerSetting($this->cloud_api_timeout, 'whatsapp.cloud_api.timeout', 15);
    }

    public function resolveDefaultTemplateKey(): string
    {
        return $this->stringSetting($this->default_template, 'whatsapp.default_template', 'clinical_reminder');
    }

    public function resolveDefaultMessage(): string
    {
        $fallbackTemplate = (string) config('whatsapp.templates.'.$this->resolveDefaultTemplateKey().'.message', '');

        return $this->stringSetting($this->default_message, 'whatsapp.default_message', $fallbackTemplate);
    }

    public function resolveStatusCallbackUrl(): string
    {
        $configuredUrl = trim((string) ($this->status_callback_url ?? ''));

        return $configuredUrl !== ''
            ? $configuredUrl
            : (string) config('whatsapp.twilio.status_callback_url', '');
    }

    private function stringSetting(mixed $value, string $configKey, string $default): string
    {
        $normalized = trim((string) $value);

        if ($normalized !== '') {
            return $normalized;
        }

        return (string) config($configKey, $default);
    }

    private function nullableStringSetting(mixed $value, string $configKey): ?string
    {
        $normalized = trim((string) $value);

        if ($normalized !== '') {
            return $normalized;
        }

        $fallback = trim((string) config($configKey, ''));

        return $fallback !== '' ? $fallback : null;
    }

    private function integerSetting(mixed $value, string $configKey, int $default): int
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        return (int) config($configKey, $default);
    }
}
