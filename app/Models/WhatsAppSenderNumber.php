<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppSenderNumber extends Model
{
    protected $table = 'whatsapp_sender_numbers';

    protected $fillable = [
        'whatsapp_credential_id',
        'name',
        'prefix',
        'number',
        'selected',
    ];

    protected function casts(): array
    {
        return [
            'selected' => 'boolean',
        ];
    }

    public function credential(): BelongsTo
    {
        return $this->belongsTo(WhatsAppCredential::class, 'whatsapp_credential_id');
    }

    public function scopeSelected(Builder $query): Builder
    {
        return $query->where('selected', true);
    }

    public function getFullNumberAttribute(): string
    {
        return $this->prefix.$this->number;
    }

    public function getWhatsAppAddressAttribute(): string
    {
        return 'whatsapp:'.$this->prefix.$this->number;
    }
}
