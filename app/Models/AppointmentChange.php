<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentChange extends Model
{
    protected $fillable = [
        'fecha_anterior',
        'hora_anterior',
        'fecha_nueva',
        'hora_nueva',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    protected function casts(): array
    {
        return [
            'fecha_anterior' => 'date',
            'fecha_nueva' => 'date',
        ];
    }
}
