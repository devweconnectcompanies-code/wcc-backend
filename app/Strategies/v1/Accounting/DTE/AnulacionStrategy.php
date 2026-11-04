<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Accounting\TaxRate;
use App\Models\Accounting\DTEModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AnulacionStrategy extends BaseDTEStrategy
{
    protected function identificacionSchema(): array
    {
        return [
            'version' => false,
            'ambiente' => false,
            'tipoDte' => false,
            'numeroControl' => false,
            'codigoGeneracion' => false,
            'tipoModelo' => false,
            'tipoOperacion' => false,
            'tipoContingencia' => false,
            'motivoContin' => false,
            'fecEmi' => false,
            'horEmi' => false,
            'tipoMoneda' => false,
        ];
    }

    protected function emisorSchema(): array
    {
        return [
            'nrc' => false,
            'codActividad' => false,
            'descActividad' => false,
            'nombreComercial' => false,
            'codEstableMH' => false,
            'codEstable' => false,
            'codPuntoVentaMH' => false,
            'codPuntoVenta' => false,
        ];
    }

    /***
     * Construye el json de la anulación.
     *
     * @param array $data
     * @return array
     * @throws \Random\RandomException
     */
    protected function buildBody(array $data): array
    {
        $relatedDoc = $this->getRelatedDoc((int)$data['dte_id']);
        $genCode = $this->headerUtils->generationCode();

        return [
            'identificacion' => $this->buildIdentificacion($genCode),
            'emisor' => $this->buildEmisor(),
            'documento' => $this->buildDocumento(dteModel: $relatedDoc, genCode: $genCode),
            'motivo' => $this->buildMotivo(data: $data, dteModel: $relatedDoc),
        ];
    }

    /***
     * Construye el bloque 'identificacion'.
     *
     * @param string $genCode
     * @return array
     */
    private function buildIdentificacion(string $genCode): array
    {
        return [
            'version' => 3,
            'ambiente' => $this->headerUtils->ambient(),
            'codigoGeneracion' => $genCode,
            'fecEmi' => $this->headerUtils->getDate(),
            'horEmi' => $this->headerUtils->getHour(),
            'fusion' => null,
        ];
    }

    /***
     * Construye el bloque 'emisor'.
     *
     * @return array
     */
    private function buildEmisor(): array
    {
        return [
            'nit' => '12170206211014',
            'nombre' => 'NETPLUS COMPANY WORK S.A. DE C.V',
            'codEstableMH' => 'M001',
            'codEstable' => null,
            'codPuntoVentaMH' => 'M001',
            'codPuntoVenta' => null,
            'telefono' => '76266022',
            'correo' => 'netpluscompanywork@gmail.com',
        ];
    }

    /****
     * Construye el bloque 'documento' referencia al DTE que se anula.
     *
     * @param DTEModel $dteModel
     * @param string $genCode
     * @return array
     */
    private function buildDocumento(DTEModel $dteModel, string $genCode): array
    {
        $json = $dteModel->json_body;
        $receptor = $json['receptor'] ?? $json['sujetoExcluido'] ?? [];

        return [
            'tipoDte' => $dteModel->dte_type?->code,
            'codigoGeneracion' => $dteModel->generation_code,
            'selloRecibido' => $dteModel->reception_stamp,
            'numeroControl' => $dteModel->control_number,
            'fecEmi' => Carbon::parse($dteModel->generation_datetime)->format('Y-m-d'),
            'codigoGeneracionR' => $genCode,
            'tipoDocumento' => $receptor['tipoDocumento'] ?? null,
            'numDocumento' => $receptor['numDocumento'] ?? null,
            'nombre' => $receptor['nombre'] ?? null,
            'telefono' => $receptor['telefono'] ?? null,
            'correo' => $receptor['correo'] ?? null,
        ];
    }

    /***
     * Construye el bloque 'motivo'.
     *
     * @param array $data
     * @param DTEModel $dteModel
     * @return array
     */
    private function buildMotivo(array $data, DTEModel $dteModel): array
    {
        $dte = $dteModel->json_body;
        $receptor = $dte['receptor'] ?? $dte['sujetoExcluido'] ?? [];

        return [
            'tipoAnulacion' => (int)$data['invalidation_type'],
            'motivoAnulacion' => $data['invalidation_reason'] ?? '',
            'nombreResponsable' => Auth::user()->name ?? '',
            'tipoDocResponsable' => '13',
            'numDocResponsable' => $this->parseNumber($data['responsible_dui']),
            'nombreSolicita' => $receptor['nombre'] ?? '',
            'tipoDocSolicita' => $receptor['tipoDocumento'] ?? null,
            'numDocSolicita' => $receptor['numDocumento'] ?? null,
        ];
    }
}
