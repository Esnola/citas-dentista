<?php

namespace App\Models;

use App\Traits\NormalizesPhone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, NormalizesPhone, SoftDeletes;

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

    public static function isValidPhone(string $phone): bool
    {
        $normalized = static::normalizePhone($phone);

        if ($normalized === '') {
            return false;
        }

        $digits = preg_replace('/\D+/', '', $normalized) ?? '';

        return strlen($digits) >= 7 && strlen($digits) <= 15;
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
