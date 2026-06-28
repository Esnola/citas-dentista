<?php

namespace App\Traits;

trait NormalizesPhone
{
    public static function normalizePhone(string $phone): string
    {
        $cleaned = preg_replace('/^whatsapp:/i', '', trim($phone)) ?? trim($phone);
        $digits = preg_replace('/\D+/', '', $cleaned) ?? '';

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($cleaned, '+')) {
            return '+'.$digits;
        }

        $countryCode = preg_replace('/\D+/', '', (string) config('whatsapp.default_country_code', '+34')) ?? '34';

        return '+'.$countryCode.$digits;
    }

    public static function normalizeWhatsAppAddress(string $address): string
    {
        return str_starts_with($address, 'whatsapp:') ? $address : 'whatsapp:'.ltrim($address);
    }

    public static function normalizeWhatsAppRecipient(string $recipient): string
    {
        $normalized = static::normalizePhone($recipient);

        return $normalized !== '' ? 'whatsapp:'.$normalized : '';
    }
}
