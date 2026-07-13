<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwilioContentTemplate extends Model
{
    protected $fillable = [
        'nombre',
        'content_sid',
        'content_variables',
    ];

    protected function casts(): array
    {
        return [
            'content_variables' => 'array',
        ];
    }
}
