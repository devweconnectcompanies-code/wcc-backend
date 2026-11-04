<?php

namespace App\Services\v1\management\billing;

use App\DTOs\v1\management\billing\extensions\ExtensionDTO;
use App\Enums\v1\General\CommonStatus;
use App\Models\Billing\InvoiceExtensionModel;
use App\Models\Billing\InvoiceModel;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ExtensionsService
{
    /***
     * Muestra las extensiones de una factura
     * @param int $invoiceId
     * @return array
     */
    public function invoiceExtensionData(int $invoiceId): array
    {
        $invoice = InvoiceModel::query()
            ->with(['extensions.user', 'period'])
            ->findOrFail($invoiceId);

        return [
            'period' => $invoice->period?->name,
            'extensions' => $invoice->extensions,
        ];
    }

    /***
     *  Almacena nueva extensión
     * @param ExtensionDTO $dto
     * @return InvoiceExtensionModel
     */
    public function createExtension(ExtensionDTO $dto): InvoiceExtensionModel
    {
        $invoice = InvoiceModel::query()->findOrFail($dto->invoiceId);

        if (!$invoice) {
            throw ValidationException::withMessages([
                'invoice' => 'La factura no existe.'
            ]);
        }

        $newDate = $dto->previousDueDate->copy()->addDays($dto->days);

        //  Solo se debe tener una prórroga activa por factura
        InvoiceExtensionModel::query()
            ->where([
                ['invoice_id', $invoice->id],
                ['status_id', CommonStatus::ACTIVE->value],
            ])
            ->update(['status_id' => CommonStatus::INACTIVE->value]);

        return InvoiceExtensionModel::query()
            ->create([
                'invoice_id' => $invoice->id,
                'previous_due_date' => $dto->previousDueDate,
                'extended_due_date' => $newDate,
                'reason' => $dto->reason,
                'user_id' => $dto->user_id,
                'status_id' => $dto->status_id,
            ]);
    }

    /***
     * Retorna datos pertenecientes a una extensión
     * @param int $id
     * @return array
     */
    public function extensionData(int $id): array
    {
        $extension = InvoiceExtensionModel::query()->findOrFail($id);
        $previous = Carbon::parse($extension->previous_due_date);
        $extended = Carbon::parse($extension->extended_due_date);

        return [
            'previous_due_date' => $previous->toDateString(),
            'days' => $previous->diffInDays($extended),
            'status_id' => $extension->status_id,
            'reason' => $extension->reason,
        ];
    }

    /***
     * Actualizar extensión según id
     * @param InvoiceExtensionModel $model
     * @param ExtensionDTO $dto
     * @return InvoiceExtensionModel
     */
    public function updateExtension(InvoiceExtensionModel $model, ExtensionDTO $dto): InvoiceExtensionModel
    {
        $invoice = InvoiceModel::find($dto->invoiceId);

        if (!$invoice) {
            throw ValidationException::withMessages([
                'invoice' => 'La factura no existe.'
            ]);
        }

        $newDate = $dto->previousDueDate->copy()->addDays($dto->days);

        $model->update([
            'previous_due_date' => $dto->previousDueDate,
            'extended_due_date' => $newDate,
            'reason' => $dto->reason,
            'user_id' => $dto->user_id,
            'status_id' => $dto->status_id,
        ]);

        return $model->refresh();
    }
}
