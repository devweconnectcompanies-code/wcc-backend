<?php

namespace App\Services\v1\management\billing\invoices;

use App\Enums\v1\General\BillingStatus;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\InvoiceModel;
use Illuminate\Database\Eloquent\Builder;

/**
 * Fuente única de verdad para saber que facturas de un cliente se pueden pagar,
 * en qué orden y a qué grupo (servicio) pertenece cada una.
 * La usan tanto en el listado de formulario de pago como la validación del pago.
 */
final class InvoiceGrouping
{
    public const string CONSOLIDATED = 'consolidated';

    /**
     * @return array
     */
    public static function payableStatuses(): array
    {
        return [
            BillingStatus::OVERDUE->value,
            BillingStatus::PENDING->value,
            BillingStatus::PARTIALLY_PAID->value,
            BillingStatus::ISSUED->value,
        ];
    }

    /**
     * Facturas pagables del cliente en orden cronológico:
     * vencidas primero, luego por período y por ID (de la más antigua a la más reciente)
     *
     * @param int $clientId
     * @return Builder
     */
    public static function payableQuery(int $clientId): Builder
    {
        return InvoiceModel::query()
            ->with(['period', 'items.service'])
            ->where('client_id', $clientId)
            ->where('status_id', CommonStatus::ACTIVE->value)
            ->whereIn('billing_status_id', self::payableStatuses())
            ->where('balance_due', '>', 0)
            ->orderByRaw('CASE WHEN billing_status_id = ? THEN 0 ELSE 1 END', [BillingStatus::OVERDUE->value])
            ->orderBy('billing_period_id', 'ASC')
            ->orderBy('id', 'ASC');
    }

    /**
     * Cada servicio con facturación independiente forma su propia cadena de pago;
     * los servicios consolidados comparten una sola.
     *
     * @param InvoiceModel $invoice
     * @return string
     */
    public static function groupKey(InvoiceModel $invoice): string
    {
        $service = $invoice->items->first()?->service;
        return $service?->separate_billing ? "service-{$service->id}" : self::CONSOLIDATED;
    }
}
