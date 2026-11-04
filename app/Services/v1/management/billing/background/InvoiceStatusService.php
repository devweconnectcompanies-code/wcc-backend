<?php

namespace App\Services\v1\management\billing\background;

use App\Enums\v1\General\BillingStatus;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\PeriodModel;
use Carbon\Carbon;

class InvoiceStatusService
{
    /***
     * Actualiza el estado de una factura basándose en su período y balance
     * @param InvoiceModel $invoice
     * @param Carbon|null $referenceDate
     * @return bool
     */
    public function updateInvoiceStatusByPeriod(InvoiceModel $invoice, ?Carbon $referenceDate = null): bool
    {
        if ($invoice->billing_status_id === BillingStatus::CANCELED->value) return false;

        $referenceDate = $referenceDate ?? Carbon::today();

        if ($invoice->balance_due <= 0) {
            if ($invoice->billing_status_id !== BillingStatus::PAID->value) {
                $invoice->update(['billing_status_id' => BillingStatus::PAID->value]);
                return true;
            }
            return false;
        }

        $period = $invoice->period;

        if (!$period) return false;

        $newStatus = $this->determineStatusByDate($period, $referenceDate);

        if ($newStatus && $invoice->billing_status_id !== $newStatus) {
            $invoice->update(['billing_status_id' => $newStatus]);
            return true;
        }
        return false;
    }

    /***
     * Determina el estado basándose en las fechas del período
     * @param PeriodModel $period
     * @param Carbon $referenceDate
     * @return int|null
     */
    private function determineStatusByDate(PeriodModel $period, Carbon $referenceDate): ?int
    {
        $periodStart = Carbon::parse($period->period_start);
        $dueDate = Carbon::parse($period->due_date);

        if ($referenceDate->lt($periodStart)) {
            return BillingStatus::ISSUED->value;
        } elseif ($referenceDate->gte($periodStart) && $referenceDate->lte($dueDate)) {
            return BillingStatus::PENDING->value;
        } elseif ($referenceDate->gt($dueDate)) {
            return BillingStatus::OVERDUE->value;
        }

        return null;
    }

    /***
     * Actualiza los estados de las facturas según las fechas del período
     * @param int|null $periodId <<Si se proporciona, solo actualiza el período>>
     * @return array
     */
    public function updateInvoiceStatuses(?int $periodId = null): array
    {
        $results = [
            'updated' => 0,
            'total' => 0,
            'errors' => []
        ];

        $query = InvoiceModel::query()
            ->with('period')
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereIn('billing_status_id', [
                BillingStatus::ISSUED->value,
                BillingStatus::PENDING->value,
                BillingStatus::OVERDUE->value,
            ]);

        if ($periodId) $query->where('billing_period_id', $periodId);

        $invoices = $query->get();
        $results['total'] = $invoices->count();
        $today = Carbon::today();

        foreach ($invoices as $invoice) {
            try {
                if ($this->updateInvoiceStatusByPeriod($invoice, $today)) $results['updated']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Factura {$invoice->id}: {$e->getMessage()}";
            }
        }

        return $results;
    }

    /***
     * Determina el estado correcto de una factura según fechas
     * @param InvoiceModel $invoice
     * @param PeriodModel $period
     * @param Carbon $today
     * @return int|null
     */
    private function determineInvoiceStatus(
        InvoiceModel $invoice,
        PeriodModel  $period,
        Carbon       $today
    ): ?int
    {
        if (in_array($invoice->billing_status_id, [
            BillingStatus::PENDING->value,
            BillingStatus::CANCELED->value,
        ])) return null;

        if ($invoice->balance_due <= 0) return BillingStatus::PAID->value;

        $issueDate = Carbon::parse($period->issue_date);
        $periodStart = Carbon::parse($period->period_start);
        $dueDate = Carbon::parse($period->due_date);

        if ($today->lt($periodStart)) {
            return BillingStatus::ISSUED->value;
        } elseif ($today->gte($periodStart) && $today->lte($dueDate)) {
            return BillingStatus::PENDING->value;
        } elseif ($today->gt($dueDate)) {
            return BillingStatus::OVERDUE->value;
        }

        return null;
    }

    /***
     * Actualiza el estado de una factura específica
     * @param int $invoiceId
     * @return bool
     */
    public function updateSingleInvoiceStatus(int $invoiceId): bool
    {
        $invoice = InvoiceModel::query()->with('period')->findOrFail($invoiceId);

        return $this->updateInvoiceStatusByPeriod($invoice);
    }

    /***
     * Actualiza estados de facturas de un período específico
     * @param int $periodId
     * @return array
     */
    public function updatePeriodInvoices(int $periodId): array
    {
        return $this->updateInvoiceStatuses($periodId);
    }

    /***
     * Actualiza los estados de múltiple facturas
     * @param array $invoiceIds
     * @return array
     */
    public function updateMultipleInvoices(array $invoiceIds): array
    {
        $results = [
            'updated' => 0,
            'total' => count($invoiceIds),
            'errors' => [],
        ];

        $invoices = InvoiceModel::query()
            ->with('period')
            ->whereIn('id', $invoiceIds)
            ->get();

        foreach ($invoices as $invoice) {
            try {
                if ($this->updateInvoiceStatusByPeriod($invoice)) $results['updated']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Factura {$invoice->id}: {$e->getMessage()}";
            }
        }
        return $results;
    }
}
