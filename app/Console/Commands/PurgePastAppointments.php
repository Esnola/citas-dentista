<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\SistemaOpcion;
use App\Services\ClientDataDeletionService;
use Carbon\CarbonInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PurgePastAppointments extends Command
{
    protected $signature = 'appointments:purge-past';

    protected $description = 'Eliminar las citas pasadas que superen el período de retención configurado.';

    public function handle(ClientDataDeletionService $deletionService): int
    {
        $settings = SistemaOpcion::get();

        Log::info('appointments:purge-past started', [
            'retention_period' => $settings->retention_period,
            'enabled' => $settings->isEnabled(),
            'timezone' => config('app.timezone'),
            'executed_at' => now(config('app.timezone'))->toDateTimeString(),
        ]);

        if (! $settings->isEnabled()) {
            Log::info('appointments:purge-past skipped because cleanup is disabled', [
                'retention_period' => $settings->retention_period,
            ]);

            $this->info('Borrado automático desactivado.');

            return self::SUCCESS;
        }

        $cutoffDate = $this->resolveCutoff($settings->retention_period)->toDateString();

        $appointmentIds = Appointment::query()
            ->whereDate('fecha', '<=', $cutoffDate)
            ->pluck('id');

        Log::info('appointments:purge-past resolved candidates', [
            'retention_period' => $settings->retention_period,
            'cutoff_date' => $cutoffDate,
            'candidate_count' => $appointmentIds->count(),
            'candidate_ids' => $appointmentIds->take(20)->values()->all(),
        ]);

        $deleted = $deletionService->deleteAppointments($appointmentIds);

        Log::info('appointments:purge-past completed', [
            'retention_period' => $settings->retention_period,
            'cutoff_date' => $cutoffDate,
            'deleted_count' => $deleted,
        ]);

        $this->info(sprintf('Borrado %d citas expiradas.', $deleted));

        return self::SUCCESS;
    }

    private function resolveCutoff(string $retentionPeriod): CarbonInterface
    {
        $now = now(config('app.timezone'));

        return match ($retentionPeriod) {
            '1_week' => $now->copy()->subWeek(),
            '2_weeks' => $now->copy()->subWeeks(2),
            '1_month' => $now->copy()->subMonth(),
            '3_months' => $now->copy()->subMonths(3),
            '6_months' => $now->copy()->subMonths(6),
            '1_year' => $now->copy()->subYear(),
            '2_years' => $now->copy()->subYears(2),
            '5_years' => $now->copy()->subYears(5),
            default => $now,
        };
    }
}
