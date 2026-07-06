<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TwilioContentTemplate extends Model
{
    protected $fillable = [
        'nombre',
        'content_sid',
        'seleccionada',
    ];

    protected function casts(): array
    {
        return [
            'seleccionada' => 'boolean',
        ];
    }

    public static function selectedContentSid(): ?string
    {
        return static::query()->where('seleccionada', true)->value('content_sid');
    }

    public function select(): void
    {
        DB::transaction(function (): void {
            static::query()->where('seleccionada', true)->update(['seleccionada' => false]);
            $this->update(['seleccionada' => true]);
        });
    }
}
