<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Accounting\TaxRate;
use App\Enums\v1\Billing\DocumentTypes;
use App\Models\Billing\InvoiceModel;
use App\Models\Billing\PaymentModel;
use App\Models\Clients\ClientModel;
use Illuminate\Support\Collection;
use Random\RandomException;
use Throwable;

class CreditoFiscalStrategy extends BaseDTEStrategy
{
    /***
     * Crédito fiscal exige un orden específico de campos en
     * "identificacion" diferente al orden base.
     *
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
            'tipoContingencia' => true,
            'motivoContin' => true,
            'fecEmi' => true,
            'horEmi' => true,
            'tipoMoneda' => true,
        ];
    }

    /***
     * Detecta el origen del DTE y delega al escenario correspondiente.
     *
     * @param array $data
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    protected function buildBody(array $data): array
    {
        return match (true) {
            isset($data['payment']) => $this->buildFromPayment((int)$data['payment']),
            $data['source'] === 'manual' => $this->buildFromManual($data),
            $data['source'] === 'invoices' => $this->buildFromSelectedInvoices($data),
            default => throw new \InvalidArgumentException("Origen no soportado: {$data['source']}"),
        };
    }

    /***
     * Escenario 1 - Desde un pago previamente registrado.
     * Construye el DTE a partir de un pago ya registrado en el sistema.
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
                'client.corporate_info.activity',
                'client.corporate_info.state',
                'client.corporate_info.municipality',
                'client.corporate_info.district',
                'client.nit',
                'client.mobile',
                'client.email',
                'invoices.items',
                'invoices.period',
                'payment_method',
            ])
            ->where('id', $paymentId)
            ->where('status_id', true)
            ->firstOrFail();

        $client = $payment->client;
        $this->assertClientCanGenerateCreditoFiscal($client);

        $retainedIva = (bool)($client->corporate_info?->retained_iva ?? false);
        $discount = (float)($payment->discount_amount ?? 0);
        [$body, $gravado] = $this->buildLinesFromInvoices($payment->invoices);

        return $this->assembleDocument(
            client: $client,
            body: $body,
            gravado: (float)$gravado,
            retainedIva: $retainedIva,
            discount: (float)$discount,
            condition: 1,
            method: $payment->payment_method?->code ?? '01',
        );
    }

    /***
     * Escenario 2 - Formulario desde el frontend.
     * Genera el DTE a partir de los datos ingresados manualmente por el usuario.
     *
     * @param array $data
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    private function buildFromManual(array $data): array
    {
        $client = $this->loadClient((int)$data['client_id'], [
            'corporate_info.activity',
            'corporate_info.state',
            'corporate_info.municipality',
            'nit',
            'mobile',
            'email',
        ]);
        $this->assertClientCanGenerateCreditoFiscal($client);
        $retainedIva = (($data['totals']['iva_retenido'] ?? 0) > 0) || (bool)($client->corporate_info?->retained_iva ?? false);
        $discount = round((float)($data['totals']['discount'] ?? 0), 2);
        [$body, $gravado] = $this->buildLinesFromItems($data['items']);

        return $this->assembleDocument(
            client: $client,
            body: $body,
            gravado: (float)$gravado,
            retainedIva: $retainedIva,
            discount: (float)$discount,
            condition: (int)($data['payment_condition']),
            method: $this->paymentMethodCode((int)$data['payment_method']),
        );
    }

    /***
     * Escenario 3 - Selección de facturas desde el frontend.
     * Crea el DTE a partir de facturas seleccionadas por el usuario.
     *
     * @param array $data
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    private function buildFromSelectedInvoices(array $data): array
    {
        $client = $this->loadClient((int)$data['client_id'], [
            'corporate_info.activity',
            'corporate_info.state',
            'corporate_info.municipality',
            'nit',
            'mobile',
            'email',
        ]);

        $invoices = InvoiceModel::query()
            ->with(['items', 'period'])
            ->whereIn('id', $data['items'])
            ->where('client_id', $data['client_id'])
            ->get();

        $this->assertClientCanGenerateCreditoFiscal($client);

        $retainedIva = (($data['totals']['iva_retenido'] ?? 0) > 0) || (bool)($client->corporate_info?->retained_iva ?? false);
        $discount = round((float)($data['totals']['discount'] ?? 0), 2);
        [$body, $gravado] = $this->buildLinesFromInvoices($invoices);

        return $this->assembleDocument(
            client: $client,
            body: $body,
            gravado: (float)$gravado,
            retainedIva: $retainedIva,
            discount: (float)$discount,
            condition: (int)($data['payment_condition']),
            method: $this->paymentMethodCode((int)$data['payment_method']),
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
     * @param string|null $method
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
        ?string     $method,
    ): array
    {
        return [
            'identificacion' => $this->identificacion(DocumentTypes::CREDITO_FISCAL, 4),
            'documentoRelacionado' => null,
            'emisor' => $this->emisorBase(),
            'receptor' => $this->buildReceptorBase($client),
            'otrosDocumentos' => null,
            'ventaTercero' => null,
            'cuerpoDocumento' => $body,
            'resumen' => $this->buildResumen(
                gravado: $gravado,
                retainedIva: $retainedIva,
                discount: $discount,
                condition: $condition,
                method: $method ?? '01',
            ),
            'apendice' => null,
        ];
    }

    /***
     * Itera las facturas con sus ítems y construye las líneas del cuerpoDocumento.
     *
     * @param Collection $invoices
     * @return array
     */
    private function buildLinesFromInvoices(Collection $invoices): array
    {
        return $this->iterateInvoiceItems($invoices, function ($item, $invoice, $numItem) {
            $precioUni = (float)$item->unit_price;
            $gravada = $precioUni * (int)$item->quantity;
            return [
                $this->buildLineItem(
                    num: $numItem,
                    tipoItem: 2,
                    descripcion: "{$item->description} ({$invoice->period?->name})",
                    cantidad: (int)$item->quantity,
                    precioUni: $precioUni,
                    gravada: $gravada,
                ),
                $gravada
            ];
        });
    }

    /***
     * Construye las líneas del cuerpoDocumento desde ítems ingresados manualmente.
     *
     * @param array $items
     * @return array
     */
    private function buildLinesFromItems(array $items): array
    {
        $gravado = 0;
        $body = [];

        foreach ($items as $item) {
            $precioUni = (float)$item['unit_price'] / TaxRate::VALOR_NETO->value();
            $gravada = $precioUni * (int)$item['quantity'];

            $body[] = $this->buildLineItem(
                num: (int)$item['_line'],
                tipoItem: (int)$item['item_type'],
                descripcion: $item['description'],
                cantidad: (int)$item['quantity'],
                precioUni: $precioUni,
                gravada: $gravada,
            );

            $gravado += $gravada;
        }

        return [$body, $gravado];
    }

    /***
     * Construye las líneas del cuerpoDocumento con los campos fijos requeridos del crédito fiscal.
     *
     * @param int $num
     * @param int $tipoItem
     * @param string $descripcion
     * @param int $cantidad
     * @param float $precioUni
     * @param float $gravada
     * @return array
     */
    private function buildLineItem(
        int    $num,
        int    $tipoItem,
        string $descripcion,
        int    $cantidad,
        float  $precioUni,
        float  $gravada,
    ): array
    {
        return [
            'numItem' => $num,
            'tipoItem' => $tipoItem,
            'numeroDocumento' => null,
            'codigo' => null,
            'codTributo' => null,
            'descripcion' => $descripcion,
            'cantidad' => $cantidad,
            'uniMedida' => 99,
            'precioUni' => round($precioUni, 8),
            'montoDescu' => 0,
            'ventaNoSuj' => 0,
            'ventaExenta' => 0,
            'ventaGravada' => round($gravada, 8),
            'tributos' => ['20'],
            'psv' => 0,
            'noGravado' => 0,
        ];
    }

    /***
     * Construye el bloque "resumen" con la lógica financiera del crédito fiscal.
     *
     * @param float $gravado
     * @param bool $retainedIva
     * @param float $discount
     * @param int $condition
     * @param string|null $method
     * @return array
     */
    private function buildResumen(
        float   $gravado,
        bool    $retainedIva,
        float   $discount,
        int     $condition,
        ?string $method,
    ): array
    {
        //  Total bruto con Iva antes del descuento
        $totalConIva = $gravado * TaxRate::VALOR_NETO->value();
        $totales = $this->calculateTotals($totalConIva, $discount, $retainedIva);

        return [
            'totalNoSuj' => 0,
            'totalExenta' => 0,
            'totalGravada' => $this->round2($totales['neto']),
            'subTotalVentas' => $this->round2($totales['neto']),
            'descuNoSuj' => 0,
            'descuExenta' => 0,
            'descuGravada' => $discount,
            'porcentajeDescuento' => 0,
            'totalDescu' => $discount,
            'tributos' => [
                [
                    'codigo' => '20',
                    'descripcion' => 'Impuesto al Valor Agregado 13%',
                    'valor' => $this->round2($totales['iva']),
                ],
            ],
            'subTotal' => $this->round2($totales['neto']),
            'ivaPerci' => 0,
            'ivaRete' => $this->round2($totales['ivaRetenido']),
            'montoTotalOperacion' => $this->round2($totalConIva),
            'totalNoGravado' => 0,
            'totalPagar' => $this->round2($totales['totalPagar']),
            'totalLetras' => $this->numberToLetter->convert($this->round2($totales['totalPagar'])),
            'saldoFavor' => 0,
            'condicionOperacion' => $condition,
            'pagos' => $this->buildPagos($method, $totales['totalPagar']),
            'numPagoElectronico' => null,
            'observaciones' => null,
        ];
    }

    /***
     * Politica que verifica si se cumplen las condiciones para generar crédito fiscal a ese cliente.
     *
     * @param ClientModel $client
     * @return void
     */
    private function assertClientCanGenerateCreditoFiscal(ClientModel $client): void
    {
        $isValidClientType = $client->client_type_id === 2;
        $isValidDocumentType = $client->document_type_id === 2;
        $hasCorporateInfo = $client->relationLoaded('corporate_info')
            ? $client->corporate_info !== null
            : $client->corporate_info()->exists();

        if (!$isValidClientType && !$isValidDocumentType && !$hasCorporateInfo) {
            throw new \InvalidArgumentException("Este cliente no cumple con los requisitos para generarle un crédito fiscal.");
        }
    }
}
