<?php

namespace App\Models;

use App\Traits\NormalizesPhone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        $rawPhone = trim((string) ($data['telefono'] ?? ''));
        $payload = [
            'nombre' => trim((string) ($data['nombre'] ?? '')),
            'apellidos' => trim((string) ($data['apellidos'] ?? '')),
            'telefono' => $rawPhone,
        ];

        $lookupPhone = static::normalizePhone($rawPhone);

        $client = static::withTrashed()
            ->get()
            ->first(fn (self $candidate): bool => static::matchesImportIdentity($candidate, $payload, $lookupPhone));

        if ($client) {
            if ($client->trashed()) {
                $client->restore();
            }

            return $client;
        }

        return static::query()->create($payload);
    }

    private static function matchesImportIdentity(self $client, array $payload, string $lookupPhone): bool
    {
        return static::normalizeImportValue($client->nombre) === static::normalizeImportValue($payload['nombre'])
            && static::normalizeImportValue($client->apellidos) === static::normalizeImportValue($payload['apellidos'])
            && static::normalizePhone((string) $client->telefono) === $lookupPhone;
    }

    private static function normalizeImportValue(string $value): string
    {
        $value = Str::ascii(trim($value));
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return mb_strtolower($value);
    }
}
