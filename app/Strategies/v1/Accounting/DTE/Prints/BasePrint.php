<?php

namespace App\Strategies\v1\Accounting\DTE\Prints;

use App\Contracts\v1\Accounting\DTE\DTEPrinterInterface;
use App\Models\Accounting\DTEModel;
use App\Models\Clients\ClientModel;
use Barryvdh\DomPDF\PDF as DomPDF;
use Carbon\Carbon;
use http\Client;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

abstract readonly class BasePrint implements DTEPrinterInterface
{
    /***
     * Genera el PDF con los datos del documento emitido.
     *
     * @param DTEModel $dte
     * @return DomPDF
     */
    public function print(DTEModel $dte): DomPDF
    {
        return $this->generate($dte);
    }

    abstract protected function generate(DTEModel $model): DomPDF;

    /***
     * Genera el código QR.
     *
     * @param string $generationCode
     * @param Carbon $date
     * @return string
     */
    protected function buildQrCode(string $generationCode, Carbon $date): string
    {
        $genDate = Carbon::parse($date)->format('Y-m-d');
        $uri = "https://admin.factura.gob.sv/consultaPublica?ambiente=01&codGen={$generationCode}&fechaEmi={$genDate}";

        return base64_encode(
            QrCode::format('svg')
                ->size(120)
                ->errorCorrection('H')
                ->generate($uri)
        );
    }

    /***
     * Retorna los datos del cliente.
     *
     * @param int $clientId
     * @param array $relations
     * @return ClientModel
     */
    protected function getClientInfo(int $clientId, array $relations = []): ClientModel
    {
        return ClientModel::query()
            ->with($relations)
            ->findOrFail($clientId);
    }

    protected function baseReceptor(int $clientId): array
    {
        $client = $this->getClientInfo($clientId, [
            'corporate_info.activity',
            'corporate_info.district',
            'corporate_info.municipality',
            'corporate_info.state',
            'email',
        ]);
        $address = $client->corporate_info?->address;
        $address .= ", {$client->corporate_info?->district?->name}";
        $address .= ", {$client->corporate_info?->municipality?->name}";
        $address .= ", {$client->corporate_info?->state?->name}.";

        return [
            'name' => ucwords($client->corporate_info?->invoice_alias),
            'nit' => str_replace('-', '', $client->corporate_info?->nit),
            'nrc' => str_replace('-', '', $client->corporate_info?->nrc),
            'giro' => $client->corporate_info?->activity?->name,
            'address' => $address,
            'phone' => str_replace('-', '', $client->corporate_info?->phone_number),
            'email' => $client->email?->email,
        ];
    }

    /***
     * Retorna la condición de la operación.
     *
     * @param int $condition
     * @return string
     */
    protected function condition(int $condition): string
    {
        return match ($condition) {
            1 => 'Contado',
            2 => 'Crédito',
            3 => 'Otro',
        };
    }

    /**
     * Calcula el total final de un documento, restando el monto reembolsado si aplica.
     *
     * @param float $total
     * @param array|null $refundResumen
     * @param string $field
     * @return float
     */
    protected function calculateFinalTotal(float $total, ?array $refundResumen, string $field): float
    {
        if (!$refundResumen) return $total;

        return $total - ($refundResumen[$field] ?? 0.00);
    }
}
