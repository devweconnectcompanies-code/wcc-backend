<?php

namespace App\Services\v1\management\billing\config;

use App\Models\Billing\PeriodModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PeriodService
{
    public function generateMonthlyPeriods(int $months = 12, ?Carbon $starDate = null): array
    {
        $starDate = $starDate ?? Carbon::now()->startOfMonth();
        $generated = [];

        try {
            DB::transaction(function () use ($months, $starDate, &$generated) {
                for ($i = 0; $i < $months; $i++) {
                    $periodDate = $starDate->copy()->addMonths($i);
                    $period = $this->createPeriod($periodDate);

                    if ($period) $generated[] = $period;
                }
            });
        } catch (\Throwable $e) {
            return ['message' => $e->getMessage()];
        }

        return $generated;
    }

    public function createPeriod(Carbon $date): ?PeriodModel
    {
        $code = $date->format('Ym');

        if (PeriodModel::query()->where('code', $code)->exists()) return null;

        $config = $this->getPeriodConfiguration($date);

        return PeriodModel::query()->create([
            'name' => $config['name'],
            'code' => $code,
            'period_start' => $config['period_start'],
            'period_end' => $config['period_end'],
            'issue_date' => $config['issue_date'],
            'due_date' => $config['due_date'],
            'cutoff_date' => $config['cutoff_date'],
            'is_active' => $config['is_active'],
            'is_closed' => false,
            'status_id' => true,
            'comments' => $config['comments'],
        ]);
    }

    private function getPeriodConfiguration(Carbon $date): array
    {
        $periodStart = $date->copy()->startOfMonth();
        $periodEnd = $date->copy()->endOfMonth();
        $issueDate = $date->copy()->subMonth()->day(20);

        return [
            'name' => $date->locale('es')->monthName . ' ' . $date->year,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'issue_date' => $issueDate,
            'due_date' => $periodEnd,
            'cutoff_date' => $periodStart->copy()->addDays(10),
            'is_active' => $date->isCurrentMonth(),
            'comments' => "Período generado desde servicio"
        ];
    }

    public function closePeriod(PeriodModel $period): bool
    {
        return DB::transaction(function () use ($period) {
            $period->update([
                'is_active' => false,
                'is_closed' => true,
                'closed_at' => Carbon::now(),
                'status_id' => false,
            ]);

            $nextPeriod = PeriodModel::query()
                ->where('period_start', '>', $period->period_end)
                ->orderBy('period_start')
                ->first();

            if ($nextPeriod) {
                $nextPeriod->update(['is_active' => true]);
            }

            return true;
        });
    }

    public function getCurrentPeriod(): ?PeriodModel
    {
        return PeriodModel::query()->where('is_active', true)->first();
    }

    public function getUpcomingPeriods(int $limit = 6): array
    {
        return PeriodModel::query()
            ->where('period_start', '>=', Carbon::now()->startOfMonth())
            ->orderBy('period_start')
            ->limit($limit)
            ->get()
            ->toArray();
    }
}
