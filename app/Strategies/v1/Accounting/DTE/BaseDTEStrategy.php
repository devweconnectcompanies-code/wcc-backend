<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Contracts\v1\Accounting\DTE\DTEGeneratorInterface;
use App\Enums\v1\Accounting\TaxRate;
use App\Enums\v1\Billing\DocumentTypes;
use App\Libraries\Accounting\DTE\HeaderUtils;
use App\Libraries\Accounting\DTE\IssuerUtils;
use App\Libraries\NumberToLetter;
use App\Models\Accounting\DTEModel;
use App\Models\Billing\Options\PaymentMethodModel;
use App\Models\Clients\ClientModel;
use Illuminate\Support\Collection;

abstract class BaseDTEStrategy implements DTEGeneratorInterface
{
    /***
     * @param HeaderUtils $headerUtils
     * @param IssuerUtils $issuerUtils
     * @param NumberToLetter $numberToLetter
     */
    public function __construct(
        protected HeaderUtils    $headerUtils,
        protected IssuerUtils    $issuerUtils,
        protected NumberToLetter $numberToLetter,
    )
    {

    }

    /***
     * @param array $data
     * @return array
     */
    public function generate(array $data): array
    {
        return $this->buildBody($data);
    }

    /***
     * @param array $data
     * @return array
     */
    abstract protected function buildBody(array $data): array;

    /***
     * @return array
     */
    protected function identificacionSchema(): array
    {
        return [];
    }

    /***
     * @return array
     */
    protected function emisorSchema(): array
    {
        return [];
    }


    /***
     * Construye el bloque "identificacion" del DTE.
     *
     * @param DocumentTypes $type
     * @param int $version
     * @return array
     * @throws \Random\RandomException
     * @throws \Throwable
     */
    protected function identificacion(DocumentTypes $type, int $version = 1): array
    {
        $base = [
            'version' => $version,
            'ambiente' => $this->headerUtils->ambient(),
            'tipoDte' => $type->code(),
            'numeroControl' => $this->headerUtils->controlNumber($type),
            'codigoGeneracion' => $this->headerUtils->generationCode(),
            'tipoModelo' => 1,
            'tipoOperacion' => 1,
            'fecEmi' => $this->headerUtils->getDate(),
            'horEmi' => $this->headerUtils->getHour(),
            'tipoMoneda' => $this->headerUtils->getCurrency(),
            'tipoContingencia' => null,
            'motivoContin' => null,
        ];
        return $this->applyFieldSchema($base, $this->identificacionSchema());
    }

    /***
     * Construye el bloque "emisor" del DTE.
     *
     * @return array
     */
    protected function emisorBase(): array
    {
        return [
            'nit' => $this->issuerUtils->getNit(),
            'nrc' => $this->issuerUtils->getNrc(),
            'nombre' => $this->issuerUtils->getName(),
            'codActividad' => $this->issuerUtils->activityCode(),
            'descActividad' => $this->issuerUtils->activityName(),
            'nombreComercial' => $this->issuerUtils->getName(),
//            'tipoEstablecimiento' => '02',
            'direccion' => [
                'departamento' => $this->issuerUtils->getState(),
                'municipio' => $this->issuerUtils->getMunicipality(),
                'distrito' => $this->issuerUtils->getDistrict(),
                'complemento' => $this->issuerUtils->getAddress(),
            ],
            'telefono' => $this->issuerUtils->getPhoneNumber(),
            'correo' => $this->issuerUtils->getEmail(),
            'codEstable' => null,
            'codPuntoVenta' => null,
        ];
    }

    /***
     * Aplica el schema sobre el emisor base.
     *
     * @return array
     */
    protected function emisor(): array
    {
        return $this->applyFieldSchema($this->emisorBase(), $this->emisorSchema());
    }

    /***
     * Construye el bloque 'emisor' para los siguientes tipos de documentos:
     * - Crédito Fiscal
     * - Nota de Crédito
     * - Nota de Débito
     *
     * @param ClientModel $clientModel
     * @return array
     */
    protected function buildReceptorBase(ClientModel $clientModel): array
    {
        $fi = $clientModel->corporate_info;

        return [
            'nit' => $fi ? $this->parseNumber($fi->nit) : null,
            'nrc' => $fi ? $this->parseNumber($fi->nrc) : null,
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

    /***
     * Motor de Schemas
     * - true => incluir con el nombre original
     * - false => excluir
     * - 'nuevoNombre' => incluir renombrado
     * - (ausente) => incluir con nombre original
     * @param array $base
     * @param array $schema
     * @return array
     */
    protected function applyFieldSchema(array $base, array $schema): array
    {
        if (empty($schema)) {
            return $base;
        }

        $result = [];

        foreach ($schema as $originalKey => $rule) {
            if ($rule === false || !array_key_exists($originalKey, $base)) {
                continue;
            }
            $result[is_string($rule) ? $rule : $originalKey] = $base[$originalKey];
        }

        foreach ($base as $key => $value) {
            $inResult = array_key_exists($key, $result);
            $renamedTo = is_string($schema[$key] ?? null) ? $schema[$key] : null;
            $inResult = $inResult || ($renamedTo && array_key_exists($renamedTo, $result));

            if (!$inResult && ($schema[$key] ?? true) !== false) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    /***
     * Remueve guiones de una cadena de texto.
     *
     * @param string $number
     * @return string
     */
    protected function parseNumber(string $number): string
    {
        return str_replace('-', '', $number);
    }

    /***
     * Formatea números de telefono a formato aceptado por hacienda (8 dígitos sin separadores).
     * Retorna null si el número no tiene exactamente 8 dígitos.
     *
     * @param string $phone
     * @return string|null
     */
    protected function phoneFormatter(string $phone): ?string
    {
        $clear = str_replace(['-', ' '], '', $phone);

        if (strlen($clear) === 8) {
            return $clear;
        }

        return null;
    }

    /***
     * Redondea a 2 decimales usando bcmath para evitar perdida de precisión.
     *
     * @param float|int $number
     * @return float
     */
    protected function round2(float|int $number): float
    {
        return (float)round($number, 2, PHP_ROUND_HALF_UP);
//        return bcadd((string)$number, '0.005', 2);
    }

    /***
     * Retorna el código del método de pago.
     *
     * @param int $methodId
     * @return string
     */
    protected function paymentMethodCode(int $methodId): string
    {
        $query = PaymentMethodModel::query()->findOrFail($methodId);
        return $query->code;
    }

    /***
     * Carga los datos de un cliente con las relaciones indicadas.
     *
     * @param int $clientId
     * @param array $relations
     * @return ClientModel
     */
    protected function loadClient(int $clientId, array $relations = []): ClientModel
    {
        return ClientModel::query()
            ->with($relations)
            ->findOrFail($clientId);
    }

    /***
     * Calcula los totales financieros compartidos por todas las estrategias.
     *
     * @param float $gravado
     * @param float $discount
     * @param bool $retainedIva
     * @return array
     */
    protected function calculateTotals(
        float $gravado,
        float $discount,
        bool  $retainedIva,
    ): array
    {
        $totalConDescuento = $gravado - $discount;
        $neto = $totalConDescuento / TaxRate::VALOR_NETO->value();
        $iva = $neto * TaxRate::IVA->value();
        $ivaRetenido = ($retainedIva && $totalConDescuento > 100) ? $neto * TaxRate::IVA_RETENIDO->value() : 0;
        $totalPagar = $neto + $iva - $ivaRetenido;

        return compact('neto', 'iva', 'ivaRetenido', 'totalPagar', 'totalConDescuento');
    }

    /****
     * Construye el array del bloque "pagos" presente en el bloque "resumen".
     *
     * @param string $method
     * @param float $monto
     * @return array
     */
    protected function buildPagos(string $method, float $monto): array
    {
        return [
            [
                'codigo' => $method,
                'montoPago' => $this->round2($monto),
                'referencia' => null,
                'plazo' => null,
                'periodo' => null,
            ]
        ];
    }

    /***
     * Itera las facturas con sus ítems y delega la construcción de cada línea al Closure proporcionado por la
     * estrategia correcta.
     *
     * El closure recibe $item, $invoice, $numItem y debe retornar [$lineArray, $valorAcumulable].
     *
     * @param Collection $invoices
     * @param \Closure $lineBuilder
     * @return array
     */
    protected function iterateInvoiceItems(Collection $invoices, \Closure $lineBuilder): array
    {
        $numItem = 1;
        $acumulado = 0;
        $body = [];

        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                [$line, $valor] = $lineBuilder($item, $invoice, $numItem);
                $body[] = $line;
                $acumulado += $valor;
                $numItem++;
            }
        }

        return [$body, $acumulado];
    }

    /***
     * Retorna el número de control del documento relacionado.
     *
     * @param int $dteId
     * @return DTEModel
     */
    protected function getRelatedDoc(int $dteId): DTEModel
    {
        return DTEModel::query()
            ->with('dte_type')
            ->findOrFail($dteId);
    }
}
