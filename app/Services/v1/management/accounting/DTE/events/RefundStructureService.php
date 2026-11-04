<?php

namespace App\Services\v1\management\accounting\DTE\events;

use App\Enums\v1\Accounting\DTE\EventTypes;
use App\Enums\v1\Accounting\TaxRate;
use App\Enums\v1\Billing\DocumentTypes;
use App\Libraries\Accounting\DTE\HeaderUtils;
use App\Libraries\Accounting\DTE\IssuerUtils;
use App\Libraries\NumberToLetter;
use App\Models\Accounting\DTEModel;
use Carbon\Carbon;

readonly class RefundStructureService
{
    public function __construct(
        private readonly HeaderUtils    $headerUtils,
        private readonly IssuerUtils    $issuerUtils,
        private readonly NumberToLetter $numberToLetter,
    )
    {

    }

    /**
     * Datos iniciales y creación del json.
     *
     * @param int $dteId
     * @param int $dteType
     * @param array $items
     * @return array[]
     * @throws \Random\RandomException
     */
    public function createJson(int $dteId, int $dteType, array $items): array
    {
        $relatedDoc = $this->getRelatedDoc(dteId: $dteId);
        [$body, $gravado] = $this->buildBodyFromItems(
            dteType: $dteType,
            items: $items,
            genCode: $relatedDoc->generation_code
        );

        return $this->assembleDocument(
            dteModel: $relatedDoc,
            dteType: $dteType,
            body: $body,
            gravado: $gravado
        );
    }

    /**
     * Genera el json.
     *
     * @param DTEModel $dteModel
     * @param int $dteType
     * @param array $body
     * @param float $gravado
     * @return array[]
     * @throws \Random\RandomException
     */
    private function assembleDocument(DTEModel $dteModel, int $dteType, array $body, float $gravado): array
    {
        return [
            'identificacion' => $this->buildIdentification(),
            'documentoRelacionado' => [
                $this->buildRelatedDoc(dteModel: $dteModel, dteType: $dteType)
            ],
            'emisor' => $this->buildIssuer(),
            'documento' => $this->buildDocument(dte: $dteModel),
            'ventaTercero' => null,
            'compraTercero' => null,
            'cuerpoDocumento' => $body,
            'resumen' => $this->buildResumen(type: $dteType, amount: $gravado),
            'apendice' => null,
        ];
    }

    /****
     * Construye el bloque 'identificacion' del json.
     *
     * @return array
     * @throws \Random\RandomException
     */
    private function buildIdentification(): array
    {
        return [
            'version' => 1,
            'ambiente' => $this->headerUtils->ambient(),
            'tipoModelo' => 1,
            'tipoOperacion' => 1,
            'tipoEvento' => EventTypes::RETORNO->code(),
            'tipoContingencia' => null,
            'motivoContin' => null,
            'codigoGeneracion' => $this->headerUtils->generationCode(),
            'fecEmi' => $this->headerUtils->getDate(),
            'horEmi' => $this->headerUtils->getHour(),
            'fusion' => null,
            'tipoMoneda' => $this->headerUtils->getCurrency(),
        ];
    }

    /****
     * Construye el bloque 'documentoRelacionado'.
     *
     * @param DTEModel $dteModel
     * @param int $dteType
     * @return array
     */
    private function buildRelatedDoc(DTEModel $dteModel, int $dteType): array
    {
        $type = DocumentTypes::from($dteType);

        return [
            'tipoDocumento' => $type->code(),
            'codigoGeneracion' => $dteModel->generation_code,
            'fechaEmision' => Carbon::parse($dteModel->generation_datetime)->toDateString(),
        ];
    }

    /**
     * Construye el bloque 'emisor'.
     *
     * @return array
     */
    private function buildIssuer(): array
    {
        return [
            'nit' => $this->issuerUtils->getNit(),
            'nombre' => $this->issuerUtils->getName(),
            'codEstableMH' => 'To do',
            'codEstable' => null,
            'codPuntoVentaMH' => 'To do',
            'codPuntoVenta' => null,
            'recintoFiscal' => null,
            'tipoRegimen' => null,
            'regimen' => null,
            'tipoItemExpor' => null,
        ];
    }

    /**
     * Construye el bloque 'documento'.
     *
     * @param DTEModel $dte
     * @return array
     */
    private function buildDocument(DTEModel $dte): array
    {
        $docType = match (true) {
            !empty($dte->client?->dui) => '13',
            !empty($dte->client?->nit) => '36',
            default => null,
        };
        $docNumber = match (true) {
            !empty($dte->client?->dui) => $dte->client?->dui->number,
            !empty($dte->client?->nit) => $dte->client?->nit->number,
            default => null,
        };

        return [
            'tipoDocumento' => $docType,
            'numDocumento' => str_replace('-', '', $docNumber),
            'nombre' => ucwords("{$dte->client?->name} {$dte->client?->surname}"),
            'codPais' => null,
            'nombrePais' => null,
            'telefono' => preg_replace('/[\s-]+/', '', $dte->client?->mobile?->number),
            'correo' => $dte->client?->email?->email,
        ];
    }

    /**
     * Construye el apartado 'cuerpoDocumento' del DTE.
     *
     * @param int $dteType
     * @param array $items
     * @param string $genCode
     * @return array
     */
    private function buildBodyFromItems(int $dteType, array $items, string $genCode): array
    {
        $gravado = 0;
        $body = [];
        $num = 1;
        $type = DocumentTypes::from($dteType);

        foreach ($items as $item) {
            $baseAmount = match ($type) {
                DocumentTypes::FACTURA => $item['ventaGravada'] ?? 0,
                DocumentTypes::FACTURA_SUJETO_EXCLUIDO => $item['compra'] ?? 0,
                default => 0,
            };
            $gravadoRow = (float)$item['precioUni'] * (int)$item['cantidad'];

            $body[] = [
                'numItem' => $num++,
                'tipoItem' => $item['tipoItem'],
                'codigoGeneracion' => $genCode,
                'cantidad' => $item['cantidad'],
                'precioUni' => $item['precioUni'],
                'descripcion' => $item['descripcion'],
                'codigo' => null,
                'uniMedida' => $item['uniMedida'],
                'montoDescu' => 0,
                'codTributo' => null,
                'ventaNoSuj' => 0,
                'ventaExenta' => 0,
                'ventaGravada' => round($gravadoRow ?? 0, 8),
                'compra' => $item['compra'] ?? 0,
                'tributos' => null,
                'psv' => 0,
                'ivaItem' => round($this->calculateIva($baseAmount), 8),
                'noGravado' => 0,
                'seguro' => 0,
                'flete' => 0,
                'ivaRete' => 0,
                'reteRenta' => 0,
            ];
            $gravado += $gravadoRow;
        }

        return [$body, $gravado];
    }

    private function buildResumen(int $type, float $amount): array
    {
        $dteType = DocumentTypes::from($type);
        [$totalGravada, $totalCompra, $totalIva] = match ($dteType) {
            DocumentTypes::FACTURA => [
                round($amount, 2),
                0,
                round($this->calculateIva($amount), 2),
            ],
            DocumentTypes::FACTURA_SUJETO_EXCLUIDO => [
                0,
                round($amount, 2),
                round($this->calculateIva($amount), 2),
            ],
            default => [round($amount, 2), 0, 0],
        };

        return [
            'totalNoSuj' => 0,
            'totalExenta' => 0,
            'totalGravada' => $totalGravada,
            'totalCompraExcluidos' => $totalCompra,
            'subTotalVentas' => round($amount, 2),
            'tributos' => null,
            'totalSeguro' => 0,
            'totalFlete' => null,
            'montoTotalOperacion' => round($amount, 2),
            'ivaRete' => 0,
            'reteRenta' => null,
            'totalNoGravado' => 0,
            'totalPagar' => round($amount, 2),
            'totalLetras' => $this->numberToLetter->convert(round($amount, 2)),
            'totalNoOnerosas' => 0,
            'totalIva' => $totalIva,
            'saldoFavor' => 0,
        ];
    }

    /**
     * Obtiene los datos generales del DTE al que se le aplicará el reembolso.
     *
     * @param int $dteId
     * @return DTEModel
     */
    private function getRelatedDoc(int $dteId): DTEModel
    {
        return DTEModel::query()
            ->findOrFail($dteId);
    }

    /**
     * Calcula el IVA.
     *
     * @param float $amount
     * @return float
     */
    private function calculateIva(float $amount): float
    {
        $neto = $amount / TaxRate::VALOR_NETO->value();
        return $neto * TaxRate::IVA->value();
    }
}
