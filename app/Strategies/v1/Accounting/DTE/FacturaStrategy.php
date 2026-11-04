<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;
use App\Models\Billing\PaymentModel;
use App\Models\Clients\ClientModel;
use Illuminate\Support\Collection;
use Random\RandomException;
use InvalidArgumentException;
use Throwable;

class FacturaStrategy extends BaseDTEStrategy
{
    /***
     * @return true[]
     */
    protected function identificacionSchema(): array
    {
        return [
            'version' => true,
            'ambiente' => true,
            'tipoDte' => true,
            'numeroControl' => true,
            'codigoGeneracion' => true,
            'tipoModelo' => true,
            'tipoOperacion' => true,
            'fecEmi' => true,
            'horEmi' => true,
            'tipoMoneda' => true,
            'tipoContingencia' => true,
            'motivoContin' => true,
        ];
    }

    /***
     *  Router principal: detecta el escenario según los datos recibidos.
     * @param array $data
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    protected function buildBody(array $data): array
    {
        return match (true) {
            isset($data['payment']) => $this->buildFromPayment((int)$data['payment']),
            $data['source'] === 'manual' => $this->buildFromManualData($data),
            default => throw new InvalidArgumentException("Origen no soportado: {$data['source']}")
        };
    }

    /***
     * Escenario 1 - Desde un pago ya registrado
     *
     * Construye el DTE a partir de un pago ya existente.
     * Carga las facturas, sus ítems, el período y los datos del cliente.
     *
     * @param int $paymentId
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    private function buildFromPayment(int $paymentId): array
    {
        $payment = PaymentModel::query()
            ->with([
                'invoices.items',
                'invoices.period',
                'client.dui.document_type',
                'client.nit.document_type',
                'client.address',
                'client.mobile',
                'client.email',
                'client.corporate_info'
            ])
            ->where('id', $paymentId)
            ->where('status_id', true)
            ->firstOrFail();

        $retainedIva = (bool)($payment->client->corporate_info?->retained_iva ?? false);
        $discount = $this->round2((float)$payment->discount_amount ?? 0);
        $methodCode = $payment->payment_method?->code ?? '01';
        [$body, $gravado] = $this->buildLinesFromInvoices($payment->invoices);

        return $this->assembleDocument(
            client: $payment->client,
            body: $body,
            gravado: $gravado,
            retainedIva: $retainedIva,
            discount: $discount,
            condition: 1,
            method: $methodCode,
        );
    }

    /***
     * Escenario 2 - Desde datos ingresados manualmente
     *
     * Construye el DTE a partir de un JSON manual con sus ítems explícitos
     *
     * @param array $data
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    private function buildFromManualData(array $data): array
    {
        $client = $this->loadClient((int)$data['client_id'], [
            'dui.document_type',
            'nit.document_type',
            'address',
            'mobile',
            'email',
            'corporate_info',
        ]);

        $retainedIva = $client->corporate_info?->retained_iva ?? false;
        $discount = $this->round2((float)($data['totals']['discount'] ?? 0));
        $methodCode = $this->paymentMethodCode((int)$data['payment_method'] ?? 1);
        [$body, $gravado] = $this->buildLinesFromItems($data['items']);

        return $this->assembleDocument(
            client: $client,
            body: $body,
            gravado: $gravado,
            retainedIva: $retainedIva,
            discount: $discount,
            condition: (int)$data['payment_condition'],
            method: $methodCode,
        );
    }

    /***
     * Ensambla la estructura completa del DTE.
     *
     * @param ClientModel $client
     * @param array $body
     * @param float $gravado
     * @param bool $retainedIva
     * @param float $discount
     * @param int $condition
     * @param string $method
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    private function assembleDocument(
        ClientModel $client,
        array       $body,
        float       $gravado,
        bool        $retainedIva,
        float       $discount,
        int         $condition,
        string      $method
    ): array
    {
        return [
            'identificacion' => $this->identificacion(DocumentTypes::FACTURA, 2),
            'documentoRelacionado' => null,
            'emisor' => $this->emisor(),
            'receptor' => $this->buildReceptor($client),
            'otrosDocumentos' => null,
            'ventaTercero' => null,
            'cuerpoDocumento' => $body,
            'resumen' => $this->buildResumen(
                gravado: $gravado,
                retainedIva: $retainedIva,
                discount: $discount,
                condition: $condition,
                method: $method,
            ),
            'apendice' => null,
        ];
    }

    /****
     * Itera facturas y sus items usando el helper base.
     * @param Collection $invoices
     * @return array
     */
    private function buildLinesFromInvoices(Collection $invoices): array
    {
        return $this->iterateInvoiceItems($invoices, function ($item, $invoice, $numItem) {
            $lineTotal = $this->round2($item->total);
            $lineIva = $this->round2($item->iva);

            return [
                [
                    'numItem' => $numItem,
                    'tipoItem' => 2,
                    'numeroDocumento' => null,
                    'cantidad' => $item->quantity ?? 1,
                    'codigo' => null,
                    'codTributo' => null,
                    'uniMedida' => 99,
                    'descripcion' => "$item->description ({$invoice->period?->name})",
                    'precioUni' => $lineTotal,
                    'montoDescu' => 0,
                    'ventaNoSuj' => 0,
                    'ventaExenta' => 0,
                    'ventaGravada' => $lineTotal,
                    'tributos' => null,
                    'psv' => 0,
                    'noGravado' => 0,
                    'ivaItem' => $lineIva,
                ],
                $lineTotal,
            ];
        });
    }

    /***
     * Construye el cuerpo del documento a partir del array de ítems del JSON manual.
     *
     * @param array $items
     * @return array
     */
    private function buildLinesFromItems(array $items): array
    {
        $gravado = 0;
        $body = [];

        foreach ($items as $item) {
            $lineTotal = $this->round2($item['total']);
            $lineIva = $this->round2($item['iva']);

            $body[] = [
                'numItem' => $item['_line'],
                'tipoItem' => $item['item_type'],
                'numeroDocumento' => null,
                'cantidad' => $item['quantity'],
                'codigo' => null,
                'codTributo' => null,
                'uniMedida' => 99,
                'descripcion' => $item['description'],
                'precioUni' => $this->round2($item['unit_price']),
                'montoDescu' => 0,
                'ventaNoSuj' => 0,
                'ventaExenta' => 0,
                'ventaGravada' => $lineTotal,
                'tributos' => null,
                'psv' => 0,
                'noGravado' => 0,
                'ivaItem' => $lineIva,
            ];

            $gravado += $lineTotal;
        }

        return [$body, $this->round2($gravado)];
    }

    /***
     * Crea contenido del apartado receptor a partir de un cliente
     *
     * @param $client
     * @return array
     */
    private function buildReceptor($client): array
    {
        $document = $client->dui ?? $client->nit;
        return [
            'tipoDocumento' => $document?->document_type?->code,
            'numDocumento' => $this->parseNumber($document->number ?? null),
            'nrc' => null,
            'nombre' => "$client->name $client->surname",
            'codActividad' => null,
            'descActividad' => null,
            'direccion' => [
                'departamento' => $client->address?->state?->code,
                'municipio' => $client->address?->municipality?->code,
                'distrito' => $client->address?->district?->code,
                'complemento' => $client->address?->address,
            ],
            'telefono' => $this->phoneFormatter($client->mobile->number ?? null),
            'correo' => $client->email?->email,
        ];
    }

    /***
     *   Construye elementos del apartado resumen a partir de los totales calculados.
     *
     * @param float $gravado
     * @param bool $retainedIva
     * @param float $discount
     * @param int $condition
     * @param int $method
     * @return array
     */
    private function buildResumen(
        float $gravado,
        bool  $retainedIva,
        float $discount,
        int   $condition,
        int   $method
    ): array
    {
        $totales = $this->calculateTotals($gravado, $discount, $retainedIva);

        return [
            'totalNoSuj' => 0,
            'totalExenta' => 0,
            'totalGravada' => $gravado,
            'subTotalVentas' => $gravado,
            'descuNoSuj' => 0,
            'descuExenta' => 0,
            'descuGravada' => $discount,
            'porcentajeDescuento' => 0,
            'totalDescu' => $discount,
            'tributos' => /*[]*/ null,
            'subTotal' => $this->round2($totales['totalConDescuento']),
            'ivaRete' => $this->round2($totales['ivaRetenido']),
            'montoTotalOperacion' => $this->round2($totales['totalPagar']),
            'totalNoGravado' => 0,
            'totalPagar' => $this->round2($totales['totalPagar']),
            'totalLetras' => $this->numberToLetter->convert($this->round2($totales['totalPagar'])),
            'totalIva' => $this->round2($totales['iva']),
            'saldoFavor' => 0,
            'condicionOperacion' => $condition,
            'pagos' => $this->buildPagos($method, $totales['totalPagar']),
            'numPagoElectronico' => null,
            'observaciones' => null,
        ];
    }
}
