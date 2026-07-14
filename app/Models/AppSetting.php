<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class AppSetting extends Model
{
    protected $table = 'app_settings';

    protected $fillable = [
        'retention_period',
        'dispatch_enabled',
        'dispatch_hours',
        'twilio_template_appointment_reminder_id',
        'twilio_template_appointment_created_id',
        'twilio_template_appointment_changed_id',
    ];

    protected $attributes = [
        'retention_period' => 'disabled',
        'dispatch_enabled' => true,
        'dispatch_hours' => '["09:00","12:00","15:00"]',
        'twilio_template_appointment_reminder_id' => null,
        'twilio_template_appointment_created_id' => null,
        'twilio_template_appointment_changed_id' => null,
    ];

    protected function casts(): array
    {
        return [
            'dispatch_enabled' => 'boolean',
            'dispatch_hours' => 'array',
            'twilio_template_appointment_reminder_id' => 'integer',
            'twilio_template_appointment_created_id' => 'integer',
            'twilio_template_appointment_changed_id' => 'integer',
        ];
    }

    public static function get(): static
    {
        if (! Schema::hasTable('app_settings')) {
            return new static([
                'retention_period' => 'disabled',
                'dispatch_enabled' => true,
                'dispatch_hours' => ['09:00', '12:00', '15:00'],
                'twilio_template_appointment_reminder_id' => null,
                'twilio_template_appointment_created_id' => null,
                'twilio_template_appointment_changed_id' => null,
            ]);
        }

        $settings = static::query()->first();

        if (! $settings) {
            $settings = static::query()->create([
                'retention_period' => 'disabled',
                'dispatch_enabled' => true,
                'dispatch_hours' => ['09:00', '12:00', '15:00'],
                'twilio_template_appointment_reminder_id' => null,
                'twilio_template_appointment_created_id' => null,
                'twilio_template_appointment_changed_id' => null,
            ]);
        }

        return $settings;
    }

    /**
     * @return array<string, string>
     */
    public static function retentionOptions(): array
    {
        return [
            'disabled' => 'Desactivar',
            '1_week' => '1 semana',
            '2_weeks' => '2 semanas',
            '1_month' => '1 mes',
            '3_months' => '3 meses',
            '6_months' => '6 meses',
            '1_year' => '1 año',
            '2_years' => '2 años',
            '5_years' => '5 años',
        ];
    }

    public function isEnabled(): bool
    {
        return $this->retention_period !== 'disabled';
    }

    public function isTimeToDispatch(): bool
    {
        if (! $this->dispatch_enabled) {
            return false;
        }

        $currentHour = now()->format('H:i');

        return in_array($currentHour, $this->dispatch_hours, true);
    }
}
