<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppMessage extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_messages';

    public const STATUS_PENDING = 'pending';

    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    public const SOURCE_MANUAL = 'manual';

    public const SOURCE_EXCEL = 'excel';

    public const SOURCE_APPOINTMENT = 'appointment';

    protected $fillable = [
        'user_id',
        'client_id',
        'appointment_id',
        'nombre',
        'apellidos',
        'telefono',
        'scheduled_for',
        'message',
        'source',
        'status',
        'sent_at',
        'last_error',
        'provider_message_id',
        'provider_payload',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'datetime',
            'sent_at' => 'datetime',
            'provider_payload' => 'array',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->nombre,
            $this->apellidos,
        ])));
    }

    public static function buildMessage(array $data, ?string $template = null): string
    {
        $templateKey = $template ?: config('whatsapp.default_template');
        $template = WhatsAppTemplate::hasKey($templateKey)
            ? WhatsAppTemplate::resolve($templateKey)['message']
            : $templateKey;
        $scheduled = $data['scheduled_for'] ?? null;

        $replacements = [
            '[NOMBRE]' => (string) ($data['nombre'] ?? ''),
            '[APELLIDOS]' => (string) ($data['apellidos'] ?? ''),
            '[TELEFONO]' => (string) ($data['telefono'] ?? ''),
            '[DIA]' => $scheduled?->format('d/m/Y') ?? '',
            '[HORA]' => $scheduled?->format('H:i') ?? '',
        ];

        return strtr($template, $replacements);
    }

    public static function templateOptions(): array
    {
        return WhatsAppTemplate::templateOptions();
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeDue($query)
    {
        return $query->pending()->where('scheduled_for', '<=', now());
    }

    public function normalizedPhone(): string
    {
        $number = preg_replace('/\D+/', '', (string) $this->telefono) ?? '';

        if ($number === '') {
            return '';
        }

        if (str_starts_with((string) $this->telefono, '+')) {
            return '+'.$number;
        }

        $countryCode = preg_replace('/\D+/', '', (string) config('whatsapp.default_country_code', '+34')) ?? '34';

        return '+'.$countryCode.$number;
    }

    public function twilioPhone(): string
    {
        $normalized = $this->normalizedPhone();

        return $normalized !== '' ? 'whatsapp:'.$normalized : '';
    }

    protected function formattedScheduledFor(): Attribute
    {
        return Attribute::get(fn () => $this->scheduled_for?->timezone(config('app.timezone'))?->format('d/m/Y H:i'));
    }
}
