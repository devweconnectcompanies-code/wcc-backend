<?php

namespace App\Strategies\v1\Accounting\DTE\Prints;

use App\Models\Accounting\DTEModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;

readonly class CreditoFiscalPrintStrategy extends BasePrint
{
    /***
     * Genera el pdf basado en el ID del DTE generado.
     *
     * @param DTEModel $model
     * @return DomPDF
     */
    protected function generate(DTEModel $model): DomPDF
    {
        $condition = $this->condition($model->json_body['resumen']['condicionOperacion']);

        return Pdf::loadView($this->getView(), [
            'qrCode' => $this->buildQrCode(
                generationCode: $model->generation_code,
                date: $model->generation_datetime,
            ),
            'data' => $model->json_body,
            'receptionStamp' => $model->reception_stamp,
            'clientData' => $this->baseReceptor($model->client_id),
            'condition' => $condition,
            'invalidated' => $model->invalidation()->exists(),
        ])
            ->setPaper('A4', 'portrait');
    }

    /***
     * Ruta de la vista a renderizar
     *
     * @return string
     */
    protected function getView(): string
    {
        return 'v1.management.pdf.accounting.dte.credito_fiscal';
    }
}
