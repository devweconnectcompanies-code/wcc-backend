<?php

namespace App\Console\Commands;

use App\Services\v1\management\billing\background\ClientFinancialStatusService;
use App\Services\v1\management\billing\background\InvoiceStatusService;
use Illuminate\Console\Command;

class UpdateInvoiceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:update-status
                                {--period= : ID del periodo a actualizar}
                                {--clients=: ID de los clientes separados por coma}
                                {--full : Actualiza estados financieros completos}
                           ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de las facturas según fechas de período';

    /**
     * Execute the console command.
     */
    public function handle(
        InvoiceStatusService         $invoiceService,
        ClientFinancialStatusService $financialService
    ): int
    {
        $this->info('Inicializando actualización de estados de facturas');
        $this->newLine();

        try {
            if ($this->option('full')) return $this->updateFullFinancialStatuses($financialService);
            if ($this->option('clients')) return $this->updateSpecificClients($financialService);
            return $this->updateInvoiceStatusesOnly($invoiceService);

        } catch (\Exception $e) {
            $this->error("Error Crítico: {$e->getMessage()}");
        }
    }

    private function updateFullFinancialStatuses(ClientFinancialStatusService $service): int
    {
        $this->info("Modo: Actualización completa (estados financieros + facturas)");
        $this->newLine();

        $result = $service->updateAllClientsWithInvoices(updateInvoiceStatuses: true);

        return $this->displayResults($result);
    }

    private function updateSpecificClients(ClientFinancialStatusService $service): int
    {
        $clientIds = array_map('intval', explode(',', $this->option('clients')));
        $this->info("Modo: Clientes específicos");
        $this->info("Procesando " . count($clientIds) . " cliente(s)...");
        $this->newLine();

        $result = $service->updateMultipleClients($clientIds, updateInvoiceStatuses: true);

        return $this->displayResults($result);
    }

    private function updateInvoiceStatusesOnly(InvoiceStatusService $service): int
    {
        $periodId = $this->option('period');

        if ($periodId) {
            $this->info("Modo: Período específico (ID: {$periodId})");
        } else {
            $this->info("Modo: Todas las facturas");
            $this->warn("Considera usar --full para actualizar también estados financieros");
        }

        $this->newLine();
        $result = $service->updateInvoiceStatuses($periodId);
        return $this->displayResults($result);
    }

    private function displayResults(array $result): int
    {
        $this->newLine();
        $this->info("Proceso completado");
        $this->newLine();

        // Mostrar tabla de resultados
        $this->table(
            ['Métrica', 'Valor'],
            [
                ['Total procesados', $result['total']],
                ['Actualizados', $result['updated']],
                ['Sin cambios', $result['total'] - $result['updated']],
                ['Errores', count($result['errors'])],
            ]
        );

        // Mostrar errores si existen
        if (!empty($result['errors'])) {
            $this->newLine();
            $this->error("Errores encontrados:");
            foreach ($result['errors'] as $error) {
                $this->error("  • {$error}");
            }
            return Command::FAILURE;
        }

        // Información adicional
        $this->newLine();
        $this->info("Estados de facturas actualizados según:");

        return Command::SUCCESS;
    }
}
