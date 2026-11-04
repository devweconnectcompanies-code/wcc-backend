<?php

namespace App\Console\Commands;

use App\Models\Billing\PeriodModel;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateBillingPeriods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:generate-periods
                                {months=12 : Number of billing periods}
                                {--start-date= : Start date (YYYY-MM-DD)}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly billing periods';

    /**
     *  Execute the console command.
     * @return void
     */

    public function handle(): void
    {
        $months = $this->argument('months');
        $startDate = $this->option('start-date')
            ? Carbon::parse($this->option('start-date'))
            : Carbon::now()->startOfMonth();

        $this->info("Generando {$months} periodos, a partir de {$startDate->format('Y-m-d')}");

        for ($i = 0; $i < $months; $i++) {
            $currentDate = $startDate->copy()->addMonths($i);
            $this->createPeriod($currentDate);
        }

        $this->info("Se ha creado exitosamente el período {$months}");
    }

    private function createPeriod(Carbon $date)
    {
        $periodStart = $date->copy()->startOfMonth();
        $periodEnd = $date->copy()->endOfMonth();
        $issueDate = $date->copy()->subMonth()->day(20);
        $cutoffDate = $date->copy()->addMonth()->day(10);

        $name = $date->locale('es')->monthName . ' ' . $date->year;
        $code = $date->format('Ym');

        if (PeriodModel::query()->where('code', $code)->exists()) {
            $this->warn("El período {$code} ya existe.");
            return;
        }

        PeriodModel::query()->create([
            'name' => ucfirst($name),
            'code' => $code,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'issue_date' => $issueDate,
            'due_date' => $periodEnd,
            'cutoff_date' => $cutoffDate,
            'is_active' => $date->isCurrentMonth(),
            'is_closed' => false,
            'status_id' => true,
            'comments' => "Período generado automáticamente.",
        ]);

        $this->line("Se ha creado el período: {$name} ({$code})");
    }
}
