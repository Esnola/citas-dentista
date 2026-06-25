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
    ];

    protected $attributes = [
        'enviado' => false,
        'entregado' => false,
        'activo' => true,
    ];

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
        ];
    }

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

    public function scheduledFor(): Carbon
    {
        return Carbon::parse($this->fecha?->toDateString().' '.$this->hora, config('app.timezone'));
    }

    public function isFuture(): bool
    {
        return $this->scheduledFor()->isFuture();
    }

    public function canBeChanged(): bool
    {
        return ! $this->enviado && $this->isFuture();
    }
}
