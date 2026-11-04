<?php

namespace App\Strategies\v1\Accounting\DTE\Prints;

use App\Libraries\NumberToLetter;
use App\Models\Accounting\DTEModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDF;

readonly class FacturaPrintStrategy extends BasePrint
{
    public function __construct(private readonly NumberToLetter $numberToLetter)
    {

    }

    /***
     * Genera el pdf basado en el ID del DTE generado.
     *
     * @param DTEModel $model
     * @return DomPDF
     */
    protected function generate(DTEModel $model): DomPDF
    {
        $client = $this->getClientInfo($model->client_id, [
            'dui',
            'mobile',
            'email',
            'address',
        ]);

        $address = $client->address?->address;
        $address .= ", {$client->address?->district?->name}";
        $address .= ", {$client->address?->municipality?->name}";
        $address .= ", {$client->address?->state?->name}.";

        $clientData = [
            'name' => ucwords("{$client->name} {$client->surname}"),
            'dui' => str_replace('-', '', $client->dui?->number) ?? '',
            'address' => $address,
            'phone' => $client->mobile?->number ?? '',
            'email' => $client->email?->email ?? '',
        ];

        $condition = $this->condition($model->json_body['resumen']['condicionOperacion']);
        $model->loadMissing(['invalidation', 'refund']);
        $refundData = $model->refund?->json_body;
        $finalTotal = $this->calculateFinalTotal(
            total: $model->json_body['resumen']['totalPagar'],
            refundResumen: $refundData['resumen'] ?? null,
            field: 'totalGravada',
        );

        return Pdf::loadView($this->getView(), [
            'qrCode' => $this->buildQrCode(
                generationCode: $model->generation_code,
                date: $model->generation_datetime,
            ),
            'data' => $model->json_body,
            'receptionStamp' => $model->reception_stamp,
            'clientData' => $clientData,
            'condition' => $condition,
            'invalidated' => $model->invalidation,
            'refund' => $refundData,
            'finalTotal' => $finalTotal,
            'letterRefundAmount' => $refundData ? $this->numberToLetter->convert($refundData['resumen']['totalPagar']) : null,
            'documentValue' => $this->numberToLetter->convert($finalTotal),
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
        return 'v1.management.pdf.accounting.dte.factura';
    }
}
