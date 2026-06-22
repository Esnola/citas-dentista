<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
    ];

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->nombre,
            $this->apellidos,
        ])));
    }

    public function messages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', trim($phone)) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with(trim($phone), '+')) {
            return '+'.$digits;
        }

        $countryCode = preg_replace('/\D+/', '', (string) config('whatsapp.default_country_code', '+34')) ?? '34';

        return '+'.$countryCode.$digits;
    }

    public static function upsertFromImport(array $data): self
    {
        $phone = static::normalizePhone((string) ($data['telefono'] ?? ''));

        return static::query()->updateOrCreate(
            ['telefono' => $phone !== '' ? $phone : (string) ($data['telefono'] ?? '')],
            [
                'nombre' => (string) ($data['nombre'] ?? ''),
                'apellidos' => (string) ($data['apellidos'] ?? ''),
            ]
        );
    }
}
