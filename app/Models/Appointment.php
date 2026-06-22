<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
