<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class SistemaOpcion extends Model
{
    protected $table = 'sistema_opciones';

    protected $fillable = [
        'retention_period',
    ];

    protected $attributes = [
        'retention_period' => 'disabled',
    ];

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

    public static function get(): static
    {
        if (! Schema::hasTable('sistema_opciones')) {
            return new static([
                'retention_period' => 'disabled',
            ]);
        }

        $settings = static::query()->first();

        if (! $settings) {
            $settings = static::query()->create([
                'retention_period' => 'disabled',
            ]);
        }

        return $settings;
    }

    public function isEnabled(): bool
    {
        return $this->retention_period !== 'disabled';
    }
}
