<?php

namespace App\Models;

use Illuminate\Support\Collection;

final class WhatsAppTemplate
{
    public static function catalog(): Collection
    {
        return collect(config('whatsapp.templates', []))
            ->map(function (array $template, string $key): array {
                return [
                    'key' => $key,
                    'label' => $template['label'] ?? $key,
                    'message' => $template['message'] ?? '',
                    'is_default' => $key === config('whatsapp.default_template'),
                    'is_active' => true,
                    'sort_order' => 0,
                ];
            })
            ->values();
    }

    public static function templateOptions(): array
    {
        return self::catalog()
            ->map(fn (array $template) => [
                'key' => $template['key'],
                'label' => $template['label'],
                'message' => $template['message'],
            ])
            ->values()
            ->all();
    }

    public static function resolve(?string $key = null): array
    {
        $catalog = self::catalog();
        $defaultKey = config('whatsapp.default_template');

        $template = $key ? $catalog->firstWhere('key', $key) : null;
        $template ??= $catalog->firstWhere('key', $defaultKey);
        $template ??= $catalog->first();

        if (! $template) {
            return [
                'key' => $key ?: $defaultKey,
                'label' => $key ?: $defaultKey,
                'message' => '',
            ];
        }

        return [
            'key' => $template['key'],
            'label' => $template['label'],
            'message' => $template['message'],
        ];
    }

    public static function hasKey(string $key): bool
    {
        return self::catalog()->contains(fn (array $template) => $template['key'] === $key);
    }

    public static function defaultKey(): string
    {
        $catalog = self::catalog();

        $default = $catalog->firstWhere('is_default', true);

        if ($default) {
            return $default['key'];
        }

        $fallback = $catalog->first();

        if ($fallback) {
            return $fallback['key'];
        }

        return config('whatsapp.default_template');
    }
}
