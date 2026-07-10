<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class WhatsAppDispatchSettings extends Model
{
    protected $table = 'whatsapp_dispatch_settings';

    protected $fillable = [
        'enabled',
        'hours',
    ];

    protected $attributes = [
        'enabled' => true,
        'hours' => '["09:00","12:00","15:00"]',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'hours' => 'array',
        ];
    }

    public static function get(): static
    {
        if (! Schema::hasTable('whatsapp_dispatch_settings')) {
            return new static([
                'enabled' => true,
                'hours' => ['09:00', '12:00', '15:00'],
            ]);
        }

        $settings = static::first();

        if (! $settings) {
            $settings = static::create([
                'enabled' => true,
                'hours' => ['09:00', '12:00', '15:00'],
            ]);
        }

        return $settings;
    }

    public function isTimeToDispatch(): bool
    {
        if (! $this->enabled) {
            return false;
        }

        $currentHour = now()->format('H:i');

        return in_array($currentHour, $this->hours, true);
    }
}
