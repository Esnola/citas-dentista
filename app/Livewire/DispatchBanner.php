<?php

namespace App\Livewire;

use App\Models\AppSetting;
use App\Models\WhatsAppCredential;
use Livewire\Attributes\On;
use Livewire\Component;

class DispatchBanner extends Component
{
    public bool $enabled = true;

    public array $alerts = [];

    public function mount(): void
    {
        $this->refreshAlerts();
    }

    #[On('dispatchToggled')]
    public function onToggle(bool|array $value = true): void
    {
        $this->refreshAlerts(
            dispatchEnabled: (bool) (is_array($value) ? ($value['value'] ?? true) : $value),
        );
    }

    #[On('dispatchSettingsChanged')]
    #[On('credentialsChanged')]
    public function refreshBanner(): void
    {
        $this->refreshAlerts();
    }

    private function refreshAlerts(?bool $dispatchEnabled = null): void
    {
        $settings = AppSetting::get();
        $credential = WhatsAppCredential::get();
        $currentHost = strtolower(request()->getHost());
        $currentUrl = request()->fullUrl();
        $callbackUrl = trim((string) ($credential->status_callback_url ?? ''));
        $callbackHost = strtolower((string) parse_url($callbackUrl, PHP_URL_HOST));
        $callbackScheme = strtolower((string) parse_url($callbackUrl, PHP_URL_SCHEME));
        $hasCallbackUrl = $callbackUrl !== '';
        $isLocalServer = app()->environment('local')
            || in_array($currentHost, ['localhost', '127.0.0.1', '::1'], true)
            || str_ends_with($currentHost, '.test')
            || preg_match('/^(127\.\d+\.\d+\.\d+|10\.\d+\.\d+\.\d+|192\.168\.\d+\.\d+|172\.(1[6-9]|2\d|3[0-1])\.\d+\.\d+)$/', $currentHost) === 1;
        $callbackPointsToLocalHost = in_array($callbackHost, ['localhost', '127.0.0.1', '::1'], true)
            || str_ends_with($callbackHost, '.test')
            || preg_match('/^(127\.\d+\.\d+\.\d+|10\.\d+\.\d+\.\d+|192\.168\.\d+\.\d+|172\.(1[6-9]|2\d|3[0-1])\.\d+\.\d+)$/', $callbackHost) === 1;
        $hasWellConfiguredCallbackUrl = $hasCallbackUrl
            && in_array($callbackScheme, ['http', 'https'], true)
            && $callbackHost !== ''
            && ! $callbackPointsToLocalHost;

        $this->enabled = $dispatchEnabled ?? $settings->dispatch_enabled;
        $this->alerts = array_values(array_filter([
            ! $this->enabled ? [
                'tone' => 'danger',
                'title' => 'Envíos automáticos deshabilitados',
                'message' => 'Los recordatorios automáticos de WhatsApp están apagados en la configuración.',
            ] : null,
            ! filled($credential->resolveAccountSid()) ? [
                'tone' => 'warning',
                'title' => 'Falta TWILIO_ACCOUNT_SID',
                'message' => 'Twilio no podrá autenticarse correctamente hasta que se configure el Account SID.',
            ] : null,
            $isLocalServer && ! $hasWellConfiguredCallbackUrl ? [
                'tone' => 'warning',
                'title' => 'Servidor local detectado',
                'message' => $hasCallbackUrl
                    ? "La aplicación se está sirviendo desde {$currentUrl} y la Callback URL guardada en base de datos ({$callbackUrl}) no parece pública o válida para Twilio."
                    : 'La aplicación se está sirviendo en local y la Callback URL guardada en base de datos está vacía.',
            ] : null,
        ]));
    }

    public function render()
    {
        return view('livewire.avisos.sin-envio-automatico');
    }
}
