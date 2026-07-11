<?php

namespace App\Models;

use App\Traits\NormalizesPhone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppMessage extends Model
{
    use HasFactory, NormalizesPhone;

    protected $table = 'whatsapp_messages';

    public const STATUS_PENDING = 'pending';

    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    public const SOURCE_MANUAL = 'manual';

    public const SOURCE_CSV = 'csv';

    public const SOURCE_APPOINTMENT = 'appointment';

    public const DIRECTION_OUTBOUND = 'outbound';

    public const DIRECTION_INBOUND = 'inbound';

    public const RESPUESTA_CONFIRMAR = 'Confirmar';

    public const RESPUESTA_REPROGRAMAR = 'Reprogramar';

    protected $fillable = [
        'user_id',
        'client_id',
        'appointment_id',
        'parent_id',
        'nombre',
        'apellidos',
        'telefono',
        'scheduled_for',
        'message',
        'source',
        'status',
        'direction',
        'sent_at',
        'last_error',
        'provider_message_id',
        'provider_payload',
        'metadata',
        'respuesta',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'datetime',
            'sent_at' => 'datetime',
            'provider_payload' => 'array',
            'metadata' => 'array',
            'responded_at' => 'datetime',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(WhatsAppMessage::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class, 'parent_id');
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
        Carbon::setLocale('es');

        $templateKey = $template ?: WhatsAppTemplate::defaultKey();
        $template = WhatsAppTemplate::hasKey($templateKey)
            ? WhatsAppTemplate::resolve($templateKey)['message']
            : $templateKey;
        $scheduled = $data['scheduled_for'] ?? null;

        $replacements = [
            '[NOMBRE]' => (string) ($data['nombre'] ?? ''),
            '[APELLIDOS]' => (string) ($data['apellidos'] ?? ''),
            '[TELEFONO]' => (string) ($data['telefono'] ?? ''),
            '[DIA]' => $scheduled?->translatedFormat('l j \d\e F') ?? '',
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

    public function scopeOutbound($query)
    {
        return $query->where(function ($query): void {
            $query->where('direction', self::DIRECTION_OUTBOUND)
                ->orWhereNull('direction');
        });
    }

    public function scopeInbound($query)
    {
        return $query->where('direction', self::DIRECTION_INBOUND);
    }

    public function isRead(): bool
    {
        return $this->deliveryStatus() === 'read';
    }

    public function isDelivered(): bool
    {
        return in_array($this->deliveryStatus(), ['delivered', 'read'], true);
    }

    public function deliveredAt(): ?Carbon
    {
        if (! $this->isDelivered()) {
            return null;
        }

        $timestamp = data_get($this->provider_payload, 'callback.received_at')
            ?? data_get($this->provider_payload, 'sync.received_at');

        return $this->parseTimestamp($timestamp) ?? $this->sent_at ?? $this->created_at;
    }

    public function readAt(): ?Carbon
    {
        if (! $this->isRead()) {
            return null;
        }

        return $this->deliveredAt();
    }

    public function deliveryStatus(): string
    {
        $callbackStatus = strtolower(trim((string) data_get($this->provider_payload, 'callback.message_status', '')));
        $callbackEventType = strtoupper(trim((string) data_get($this->provider_payload, 'callback.event_type', '')));
        $rawStatus = strtolower(trim((string) data_get($this->provider_payload, 'raw.status', '')));

        if (in_array($callbackStatus, ['delivered', 'read'], true)) {
            return $callbackStatus;
        }

        if ($callbackEventType === 'READ') {
            return 'read';
        }

        if (in_array($rawStatus, ['delivered', 'read'], true)) {
            return $rawStatus;
        }

        return $rawStatus;
    }

    public function hasResponse(): bool
    {
        return $this->responseValue() !== null;
    }

    public function isConfirmed(): bool
    {
        $buttonPayload = strtolower(trim((string) data_get($this->provider_payload, 'inbound.button_payload', '')));

        if ($buttonPayload !== '') {
            return str_starts_with($buttonPayload, 'confirm');
        }

        $response = $this->normalizedInboundResponse();

        return $response !== ''
            && (
                str_contains($response, 'confirm')
                || in_array($response, ['confirmada', 'confirmado'], true)
            );
    }

    public function isRescheduleRequested(): bool
    {
        $buttonPayload = strtolower(trim((string) data_get($this->provider_payload, 'inbound.button_payload', '')));

        if ($buttonPayload !== '') {
            return str_starts_with($buttonPayload, 'reprogram') || str_starts_with($buttonPayload, 'cambiar');
        }

        $response = $this->normalizedInboundResponse();

        return $response !== ''
            && (
                str_contains($response, 'reprogram')
                || str_contains($response, 'cambiar')
            );
    }

    public function responseValue(): ?string
    {
        $direction = strtolower(trim((string) data_get($this->provider_payload, 'inbound.direction', '')));
        $status = strtolower(trim((string) data_get($this->provider_payload, 'inbound.status', '')));
        $body = trim((string) data_get($this->provider_payload, 'inbound.body', ''));

        if (in_array($direction, ['inbound api', 'inbound-api', 'inbound_api'], true) && $status === 'received' && $body !== '') {
            return $body;
        }

        if (filled($this->respuesta)) {
            return $this->respuesta;
        }

        return null;
    }

    public function scopeResponded($query)
    {
        return $query->whereNotNull('respuesta');
    }

    public function normalizedPhone(): string
    {
        return static::normalizeInternationalPhone((string) $this->telefono);
    }

    public function twilioPhone(): string
    {
        $normalized = static::normalizeInternationalPhone((string) $this->telefono);

        return $normalized !== '' ? 'whatsapp:'.$normalized : '';
    }

    protected function telefono(): Attribute
    {
        return Attribute::set(fn (string $value): string => static::normalizePhone($value));
    }

    protected function formattedScheduledFor(): Attribute
    {
        return Attribute::get(fn () => $this->scheduled_for?->timezone(config('app.timezone'))?->format('d/m/Y H:i'));
    }

    private function parseTimestamp(mixed $timestamp): ?Carbon
    {
        if (! is_string($timestamp) || trim($timestamp) === '') {
            return null;
        }

        try {
            return Carbon::parse($timestamp)->timezone(config('app.timezone'));
        } catch (\Throwable) {
            return null;
        }
    }

    private function normalizedInboundResponse(): string
    {
        $responseText = trim((string) data_get($this->provider_payload, 'inbound.response_text', ''));
        $buttonText = trim((string) data_get($this->provider_payload, 'inbound.button_text', ''));
        $body = trim((string) data_get($this->provider_payload, 'inbound.body', ''));
        $respuesta = trim((string) $this->respuesta);
        $value = $responseText !== '' ? $responseText : ($buttonText !== '' ? $buttonText : ($body !== '' ? $body : $respuesta));

        $value = strtolower($value);
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value, " \t\n\r\0\x0B:;,.!?-_+*#\"'()[]{}");
    }
}
