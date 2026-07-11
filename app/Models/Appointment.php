<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'fecha',
        'hora',
        'fecha_original',
        'hora_original',
        'enviado',
        'entregado',
        'whatsapp_sent_at',
        'whatsapp_delivered_at',
        'whatsapp_read_at',
        'activo',
        'cita_activa',
        'confirmada',
        'pendiente_reprogramacion',
    ];

    protected $attributes = [
        'enviado' => false,
        'entregado' => false,
        'activo' => true,
        'cita_activa' => true,
        'confirmada' => false,
        'pendiente_reprogramacion' => false,
    ];

    protected static function booted(): void
    {
        static::creating(function (self $appointment): void {
            $appointment->fecha_original ??= $appointment->fecha;
            $appointment->hora_original ??= $appointment->hora;
        });

        static::updating(function (self $appointment): void {
            if (! $appointment->wasChangedSchedule() || ($appointment->fecha_original && filled($appointment->hora_original))) {
                return;
            }

            $appointment->fecha_original ??= $appointment->getOriginal('fecha');
            $appointment->hora_original ??= $appointment->getOriginal('hora');
        });

        static::updated(function (self $appointment): void {
            if (! $appointment->wasChangedSchedule()) {
                return;
            }

            $appointment->changes()->create([
                'fecha_anterior' => $appointment->getOriginal('fecha'),
                'hora_anterior' => $appointment->getOriginal('hora'),
                'fecha_nueva' => $appointment->fecha->toDateString(),
                'hora_nueva' => $appointment->hora,
            ]);
        });
    }

    public function wasChangedSchedule(): bool
    {
        return $this->isDirty('fecha') || $this->isDirty('hora');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function latestWhatsAppMessage(): HasOne
    {
        return $this->hasOne(WhatsAppMessage::class)->latestOfMany();
    }

    public function latestRespondedWhatsAppMessage(): HasOne
    {
        return $this->hasOne(WhatsAppMessage::class)
            ->whereNotNull('respuesta')
            ->latestOfMany('responded_at');
    }

    public function latestInboundWhatsAppMessage(): HasOne
    {
        return $this->hasOne(WhatsAppMessage::class)
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->latestOfMany();
    }

    public function hasTextResponse(): bool
    {
        $latest = $this->latestInboundAfterLastSent();

        if (! $latest) {
            return false;
        }

        return ! $latest->isConfirmed() && ! $latest->isRescheduleRequested();
    }

    public function latestInboundAfterLastSent(): ?WhatsAppMessage
    {
        $lastSent = $this->whatsAppMessages()
            ->outbound()
            ->whereNotNull('sent_at')
            ->orderByDesc('sent_at')
            ->first();

        if (! $lastSent) {
            return $this->whatsAppMessages()
                ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
                ->orderByDesc('created_at')
                ->first();
        }

        return $this->whatsAppMessages()
            ->where('direction', WhatsAppMessage::DIRECTION_INBOUND)
            ->where('created_at', '>=', $lastSent->created_at)
            ->orderByDesc('created_at')
            ->first();
    }

    public function whatsAppMessages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class);
    }

    public function changes(): HasMany
    {
        return $this->hasMany(AppointmentChange::class)->latest();
    }

    public function canBeChanged(): bool
    {
        return $this->fecha->toDateString() >= now()->toDateString();
    }

    public function isFuture(): bool
    {
        return $this->fecha->toDateString() >= now()->toDateString();
    }

    public function scheduledFor(): Carbon
    {
        return Carbon::parse($this->fecha?->toDateString().' '.$this->hora, config('app.timezone'));
    }

    public function getEsFallidoAttribute(): bool
    {
        $latestMsg = $this->latestWhatsAppMessage;

        return $latestMsg?->status === WhatsAppMessage::STATUS_FAILED
          || in_array($latestMsg?->deliveryStatus(), ['failed', 'undelivered'], true);
    }

    public function hasConflict(): bool
    {
        return static::query()
            ->where('fecha', $this->fecha)
            ->where('hora', $this->hora)
            ->where('id', '!=', $this->id)
            ->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function scopePending($query)
    {
        return $query->where('enviado', false);
    }

    public function scopeUpcoming($query)
    {
        $now = now(config('app.timezone'));

        return $query->where(function ($q) use ($now) {
            $q->whereDate('fecha', '>', $now->toDateString())
                ->orWhere(function ($q2) use ($now) {
                    $q2->whereDate('fecha', $now->toDateString())
                        ->where('hora', '>', $now->format('H:i:s'));
                });
        });
    }

    public function confirmar(): void
    {
        $this->update([
            'confirmada' => true,
            'pendiente_reprogramacion' => false,
        ]);
    }

    public function marcarReprogramacion(): void
    {
        $this->update([
            'confirmada' => false,
            'pendiente_reprogramacion' => true,
        ]);
    }

    public function queBoton(): ?string
    {
        $latestInbound = $this->latestInboundAfterLastSent();

        if ($latestInbound?->isConfirmed()) {
            return 'confirmada';
        }

        if ($latestInbound?->isRescheduleRequested()) {
            return 'cambiar';
        }

        return null;
    }

    public function esCitaConfirmada(): bool
    {
        $latestInbound = $this->latestInboundAfterLastSent();

        return $latestInbound?->isConfirmed() ?? false;
    }

    public function wasRescheduled(): bool
    {
        if (! $this->fecha_original || blank($this->hora_original)) {
            return false;
        }

        return $this->fecha_original->toDateString() !== $this->fecha?->toDateString()
          || $this->hora_original !== $this->hora;
    }

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'fecha_original' => 'date',
            'enviado' => 'boolean',
            'entregado' => 'boolean',
            'whatsapp_sent_at' => 'datetime',
            'whatsapp_delivered_at' => 'datetime',
            'whatsapp_read_at' => 'datetime',
            'hora_original' => 'string',
            'activo' => 'boolean',
            'cita_activa' => 'boolean',
            'confirmada' => 'boolean',
            'pendiente_reprogramacion' => 'boolean',
        ];
    }
}
