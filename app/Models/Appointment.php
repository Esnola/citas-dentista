<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'fecha',
        'hora',
        'enviado',
        'activo',
    ];

    protected $attributes = [
        'enviado' => false,
        'activo' => true,
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'enviado' => 'boolean',
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
