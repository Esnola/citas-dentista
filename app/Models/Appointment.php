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

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function whatsAppMessages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class);
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

    public function canBeChanged(): bool
    {
        return ! $this->scheduledFor()->isPast();
    }

    public function isFuture(): bool
    {
        return $this->scheduledFor()->isFuture();
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
        $this->update(['confirmada' => true]);
    }

    public function marcarReprogramacion(): void
    {
        $this->update(['pendiente_reprogramacion' => true]);
    }

    public function isConfirmed(): bool
    {
        return $this->confirmada;
    }

    public function needsReschedule(): bool
    {
        return $this->pendiente_reprogramacion;
    }

    public function responseStatusLabel(): ?string
    {
        $response = $this->latestRespondedWhatsAppMessage;

        if ($response && $response->respuesta) {
            return $response->respuesta;
        }

        if ($this->confirmada) {
            return 'Confirmada';
        }

        if ($this->pendiente_reprogramacion) {
            return 'Reprogramar';
        }

        return null;
    }

    public function responseStatusColor(): ?string
    {
        $label = $this->responseStatusLabel();

        if ($label === 'Confirmar' || $label === 'Confirmada') {
            return 'emerald';
        }

        if ($label === 'Reprogramar') {
            return 'amber';
        }

        return null;
    }

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'enviado' => 'boolean',
            'entregado' => 'boolean',
            'whatsapp_sent_at' => 'datetime',
            'whatsapp_delivered_at' => 'datetime',
            'whatsapp_read_at' => 'datetime',
            'activo' => 'boolean',
            'cita_activa' => 'boolean',
            'confirmada' => 'boolean',
            'pendiente_reprogramacion' => 'boolean',
        ];
    }
}
