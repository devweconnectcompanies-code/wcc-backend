<?php

namespace App\Strategies\v1\Accounting\DTE;


use App\Enums\v1\Accounting\TaxRate;
use App\Enums\v1\Billing\DocumentTypes;
use App\Enums\v1\Clients\ClientTypes;
use App\Models\Accounting\DTEModel;
use App\Models\Clients\ClientModel;
use Carbon\Carbon;
use Random\RandomException;
use Throwable;

abstract class BaseNotaStrategy extends BaseDTEStrategy
{
    /***
     * Tipo de Nota que se emitirá.
     *
     * @return DocumentTypes
     */
    abstract protected function documentType(): DocumentTypes;

    /****
     * Define el orden y los campos que se incluirán en el bloque 'resumen'
     *
     * @return array
     */
    abstract protected function resumenSchema(): array;

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
            'fusion' => true,
        ];
    }

    /***
     * Añade campos adicionales al bloque identificación.
     *
     * @param DocumentTypes $type
     * @param int $version
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    protected function identificacion(DocumentTypes $type, int $version = 4): array
    {
        $identificacion = parent::identificacion($type, $version);
        $identificacion['fusion'] = null;
        return $identificacion;
    }

    protected function emisorSchema(): array
    {
        return [
            'codEstableMH' => false,
            'codEstable' => false,
            'codPuntoVentaMH' => false,
            'codPuntoVenta' => false,
        ];
    }

    /***
     * Construye el bloque "receptor" de las notas de crédito/débito.
     *
     * @param ClientModel $clientModel
     * @return array
     */
    protected function buildReceptor(ClientModel $clientModel): array
    {
        $fi = $clientModel->corporate_info;
        $number = $fi->dui ?? $fi->nit;

        return [
            'tipoDocumento' => $fi->dui ? '13' : '36',
            'numDocumento' => $this->parseNumber($number),
            'nrc' => $this->parseNumber($fi->nrc),
            'nombre' => $fi?->invoice_alias ?? "{$clientModel->name} {$clientModel->surname}",
            'codActividad' => $fi?->activity?->code,
            'descActividad' => $fi?->activity?->name,
            'nombreComercial' => $fi?->invoice_alias ?? "{$clientModel->name} {$clientModel->surname}",
            'direccion' => [
                'departamento' => $fi?->state?->code ?? $clientModel->address?->state?->code,
                'municipio' => $fi?->municipality?->code ?? $clientModel->address?->municipality?->code,
                'distrito' => $fi?->district?->code ?? $clientModel->address?->district?->code,
                'complemento' => $fi?->address ?? $clientModel->address?->address,
            ],
            'telefono' => $this->phoneFormatter($fi?->phone_number ?? $clientModel->mobile?->number) ?? null,
            'correo' => $clientModel->email?->email,
        ];
    }

    /**
     * Carga datos del cliente y construye el DTE.
     *
     * @throws RandomException
     * @throws Throwable
     */
    protected function buildBody(array $data): array
    {
        $client = $this->loadClient((int)$data['client_id'], [
            'corporate_info.activity',
            'corporate_info.state',
            'corporate_info.municipality',
            'nit',
            'mobile',
            'email',
        ]);
        $this->canGenerate($client);
        $relatedDocId = $data['related_documents'][0]['document_number'];
        $relatedDoc = $this->getRelatedDoc($relatedDocId);
        $retainedIva = (($data['totals']['iva_retenido'] ?? 0) > 0) || $client->corporate_info?->retained_iva ?? false;
        $discount = (float)($data['totals']['discount'] ?? 0);
        [$body, $gravado] = $this->buildLinesFromItems(
            items: $data['items'],
            relatedDoc: $relatedDoc->generation_code,
        );

        return $this->assembleDocument(
            clientModel: $client,
            dteModel: $relatedDoc,
            body: $body,
            gravado: $gravado,
            retainedIva: $retainedIva,
            discount: $discount,
            condition: (int)$data['payment_condition'],
        );
    }

    /***
     * Política que verifica que se cumplan las condiciones para generar la nota.
     *
     * @param ClientModel $clientModel
     * @return void
     */
    protected function canGenerate(ClientModel $clientModel): void
    {
        $isValidClientType = $clientModel->client_type_id === ClientTypes::CORPORATE->value;
        $isValidDocumentType = $clientModel->document_type_id === $this->documentType()->value;
        $hasCorporateInfo = $clientModel->relationLoaded('corporate_info')
            ? $clientModel->corporate_info !== null
            : $clientModel->corporate_info()->exists();

        if (!$isValidClientType && !$isValidDocumentType && !$hasCorporateInfo) {
            throw new \InvalidArgumentException(
                "Este cliente no cumple con los requisitos para generarle una "
                . $this->documentType()->label()
            );
        }
    }

    /***
     * Construye el bloque 'cuerpoDocumento'
     *
     * @param array $items
     * @param string $relatedDoc
     * @return array
     */
    private function buildLinesFromItems(array $items, string $relatedDoc): array
    {
        $gravado = 0;
        $body = [];

        foreach ($items as $item) {
            $unitPrice = (float)$item['unit_price'] / TaxRate::VALOR_NETO->value();
            $gravadoRow = $unitPrice * (int)$item['quantity'];
            $ivaRow = $gravadoRow * TaxRate::IVA->value();

            $body[] = [
                'numItem' => (int)$item['_line'],
                'tipoItem' => (int)$item['item_type'],
                'numeroDocumento' => $relatedDoc,
                'cantidad' => (int)$item['quantity'],
                'codigo' => null,
                'codTributo' => null,
                'uniMedida' => 99,
                'descripcion' => $item['description'],
                'precioUni' => round($unitPrice, 8),
                'montoDescu' => 0,
                'ventaNoSuj' => 0,
                'ventaExenta' => 0,
                'ventaGravada' => round($gravadoRow, 8),
                'tributos' => ['20'],
                'noGravado' => 0,
                'ivaPerci' => 0,
                'totalIva' => round($ivaRow, 8),
                'ivaRete' => 0,
            ];

            $gravado += $gravadoRow;
        }

        return [$body, $gravado];
    }

    /****
     * Construye el bloque 'documentoRelacionado' del DTE.
     *
     * @param DTEModel $dteModel
     * @return array[]
     */
    private function buildRelatedDoc(DTEModel $dteModel): array
    {
        return [
            [
                'tipoDocumento' => $dteModel->dte_type?->code,
                'tipoGeneracion' => 2,
                'numeroDocumento' => $dteModel->generation_code,
                'fechaEmision' => Carbon::parse($dteModel->generation_datetime)->format('Y-m-d'),
            ]
        ];
    }

    /***
     * Construye el bloque 'resumen' del DTE.
     *
     * @param float $gravado
     * @param bool $retainedIva
     * @param float $discount
     * @param int $condition
     * @return array
     */
    private function buildResumen(
        float $gravado,
        bool  $retainedIva,
        float $discount,
        int   $condition
    ): array
    {
        $totalConIva = $gravado * TaxRate::VALOR_NETO->value();
        $totales = $this->calculateTotals(
            gravado: $totalConIva,
            discount: $discount,
            retainedIva: $retainedIva,
        );

        $pool = [
            'totalNoSuj' => 0,
            'totalExenta' => 0,
            'totalGravada' => $this->round2($totales['neto']),
            'subTotalVentas' => $this->round2($totales['neto']),
            'totalDescu' => $discount,
            'tributos' => [
                [
                    'codigo' => '20',
                    'descripcion' => 'Impuesto al Valor Agregado 13%',
                    'valor' => $this->round2($totales['iva']),
                ]
            ],
            'ivaPerci' => 0,
            'totalIva' => $this->round2($totales['iva']),
            'ivaRete' => $this->round2($totales['ivaRetenido']),
            'montoTotalOperacion' => $this->round2($totales['totalPagar']),
            'totalNoGravado' => 0,
            'totalPagar' => $this->round2($totales['totalPagar']),
            'totalLetras' => $this->numberToLetter->convert($this->round2($totales['totalPagar'])),
            'condicionOperacion' => $condition,
            'numPagoElectronico' => null,
            'observaciones' => null,
            'codigoRetencionMH' => null,
        ];

        return $this->applyResumenSchema($pool, $this->resumenSchema());
    }

    /***
     * Ensambla la estructura completa del json.
     *
     * @param ClientModel $clientModel
     * @param DTEModel $dteModel
     * @param array $body
     * @param float $gravado
     * @param bool $retainedIva
     * @param float $discount
     * @param int $condition
     * @return array
     * @throws RandomException
     * @throws Throwable
     */
    private function assembleDocument(
        ClientModel $clientModel,
        DTEModel    $dteModel,
        array       $body,
        float       $gravado,
        bool        $retainedIva,
        float       $discount,
        int         $condition
    ): array
    {
        return [
            'identificacion' => $this->identificacion($this->documentType(), 4),
            'documentoRelacionado' => $this->buildRelatedDoc($dteModel),
            'emisor' => $this->emisor(),
            'receptor' => $this->buildReceptor($clientModel),
            'ventaTercero' => null,
            'cuerpoDocumento' => $body,
            'resumen' => $this->buildResumen(
                gravado: $gravado,
                retainedIva: $retainedIva,
                discount: $discount,
                condition: $condition,
            ),
            'apendice' => null,
        ];
    }

    /****
     * Filtra y reordena el pool de campos del resumen según el schema declarado por cada subclase.
     *
     * @param array $pool
     * @param array $schema
     * @return array
     */
    private function applyResumenSchema(array $pool, array $schema): array
    {
        $result = [];

        foreach ($schema as $key) {
            if (array_key_exists($key, $pool)) {
                $result[$key] = $pool[$key];
            }
        }

        return $result;
    }
}
