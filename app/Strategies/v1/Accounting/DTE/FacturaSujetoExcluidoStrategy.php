<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;
use App\Models\Clients\ClientModel;
use Random\RandomException;
use Throwable;

class FacturaSujetoExcluidoStrategy extends BaseDTEStrategy
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
            'tipoContingencia' => true,
            'motivoContin' => true,
            'fecEmi' => true,
            'horEmi' => true,
            'tipoMoneda' => true,
        ];
    }

    /***
     * @return array
     */
    protected function emisorSchema(): array
    {
        return [
            'nit' => true,
            'nrc' => true,
            'nombre' => true,
            'codActividad' => true,
            'descActividad' => true,
            'nombreComercial' => false,
            'tipoEstablecimiento' => false,
            'direccion' => true,
            'telefono' => true,
            'codEstableMH' => true,
            'codEstable' => true,
            'codPuntoVentaMH' => true,
            'codPuntoVenta' => true,
            'correo' => true,
        ];
    }

    /***
     *  Carga los datos del cliente, y construye el DTE.
     *
     * @param array $data
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    protected function buildBody(array $data): array
    {
        $client = $this->loadClient((int)$data['client_id'], [
            'dui.document_type',
            'address.state',
            'address.municipality',
            'mobile',
            'email',
            'corporate_info',
            'corporate_info.state',
            'corporate_info.municipality',
        ]);
        $retainedIva = (($data['totals']['iva_retenido'] ?? 0) > 0) || (bool)($client->corporate_info?->retained_iva ?? false);
        $discount = $this->round2((float)($data['totals']['discount'] ?? 0));
        [$body, $compra] = $this->buildLinesFromItems($data['items']);

        return $this->assembleDocument(
            client: $client,
            body: $body,
            compra: $compra,
            retainedIva: $retainedIva,
            discount: $discount,
            condition: (int)$data['payment_condition'],
            method: $this->paymentMethodCode((int)$data['payment_method']),
        );
    }

    /***
     * Construye las líneas del "cuerpoDocumento" a partir de los elementos ingresados manualmente.
     *
     * @param array $items
     * @return array
     */
    private function buildLinesFromItems(array $items): array
    {
        $compra = 0;
        $body = [];

        foreach ($items as $item) {
            $precioUni = (float)$item['unit_price'];
            $purchase = $precioUni * (int)$item['quantity'];

            $body[] = [
                'numItem' => (int)$item['_line'],
                'tipoItem' => (int)$item['item_type'],
                'cantidad' => (int)$item['quantity'],
                'codigo' => null,
                'uniMedida' => 99,
                'descripcion' => $item['description'],
                'precioUni' => round($precioUni, 8),
                'montoDescu' => 0,
                'compra' => round($purchase, 8),
            ];
            $compra += $purchase;
        }

        return [$body, $compra];
    }

    /***
     * Ensambla la estructura completa del DTE.
     *
     * @param ClientModel $client
     * @param array $body
     * @param float $compra
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
        float       $compra,
        bool        $retainedIva,
        float       $discount,
        int         $condition,
        ?string     $method,
    ): array
    {
        return [
            'identificacion' => $this->identificacion(DocumentTypes::FACTURA_SUJETO_EXCLUIDO, 2),
            'emisor' => $this->emisor(),
            'receptor' => $this->buildReceptor($client),
            'cuerpoDocumento' => $body,
            'resumen' => $this->buildResumen(
                compra: $compra,
                retainedIva: $retainedIva,
                discount: $discount,
                condition: $condition,
                method: $method ?? '01',
            ),
            'apendice' => null,
        ];
    }

    /***
     * Construye el bloque "receptor" con los datos del cliente.
     *
     * @param ClientModel $client
     * @return array
     */
    private function buildReceptor(ClientModel $client): array
    {
        $fi = $client->corporate_info;

        return [
            'tipoDocumento' => $fi ? 36 : $client->dui?->document_type?->code,
            'numDocumento' => $fi ? $this->parseNumber($fi->nit) : $this->parseNumber($client->dui?->number),
            'nombre' => $fi ? $fi?->invoice_alias : "{$client->name} {$client->surname}",
            'codActividad' => $fi?->activity?->code ?? null,
            'descActividad' => $fi?->activity?->name ?? null,
            'direccion' => [
                'departamento' => $fi?->state?->code ?? $client->address?->state?->code,
                'municipio' => $fi?->municipality?->code ?? $client->address?->municipality?->code,
                'distrito' => $fi?->district?->code ?? $client->address?->district?->code,
                'complemento' => $fi?->address ?? $client->address?->address,
            ],
            'telefono' => $this->phoneFormatter($fi?->phone_number ?? $client->mobile?->number) ?? null,
            'correo' => $client->email?->email ?? null,
        ];
    }

    /***
     * Construye el bloque "resumen" con su respectiva lógica financiera.
     *
     * @param float $compra
     * @param bool $retainedIva
     * @param float $discount
     * @param int $condition
     * @param string|null $method
     * @return array
     */
    private function buildResumen(
        float   $compra,
        bool    $retainedIva,
        float   $discount,
        int     $condition,
        ?string $method,
    ): array
    {
        $totales = $this->calculateTotals($compra, $discount, $retainedIva);

        return [
            'totalCompra' => $this->round2($compra),
            'descu' => $this->round2($discount),
            'totalDescu' => $this->round2($discount),
            'subTotal' => $this->round2($compra),
            'reteRenta' => 0,
            'totalPagar' => $this->round2($totales['totalPagar']),
            'totalLetras' => $this->numberToLetter->convert($this->round2($totales['totalPagar'])),
            'condicionOperacion' => $condition,
            'pagos' => $this->buildPagos($method, $totales['totalPagar']),
            'observaciones' => null,
        ];
    }
}
