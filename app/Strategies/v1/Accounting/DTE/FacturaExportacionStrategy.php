<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;

class FacturaExportacionStrategy extends BaseDTEStrategy
{
    /***
     * @return array
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
            'motivoContin' => 'motivoContigencia',
            'fecEmi' => true,
            'horEmi' => true,
            'tipoMoneda' => true,
        ];
    }

    /***
     * @return true[]
     */
    protected function emisorSchema(): array
    {
        return [
            'nit' => true,
            'nrc' => true,
            'nombre' => true,
            'codActividad' => true,
            'descActividad' => true,
            'nombreComercial' => true,
            'tipoEstablecimiento' => true,
            'direccion' => true,
            'telefono' => true,
            'correo' => true,
            'codEstableMH' => true,
            'codEstable' => true,
            'codPuntoVentaMH' => true,
            'codPuntoVenta' => true,
        ];
    }

    /***
     * @param array $data
     * @return array
     * @throws \Random\RandomException
     */
    protected function buildBody(array $data): array
    {
        $emisor = array_merge($this->emisor(), [
            'tipoItemExpor' => 2,   //  1. Bienes, 2. Servicios, 3. Bienes y Servicios, 4. Otros
            'recintoFiscal' => null,
            'regimen' => null,
        ]);
        
        return [
            'identificacion' => $this->identificacion(DocumentTypes::FACTURA_EXPORTACION),
            'emisor' => $emisor,
            'receptor' => [
                'nombre' => 'John Doe',
                'tipoDocumento' => '03',
                'numDocumento' => 'A00000000',
                'nombreComercial' => 'John Doe',
                'codPais' => 'CR',
                'nombrePais' => 'Costa Rica',
                'complemento' => 'Refugio de vida salvaje Tucán Home.',
                'tipoPersona' => 1,
                'descActividad' => 'Cuidado de animales en peligro.',
                'telefono' => '50688880000',
                'correo' => 'johndoe@gmail.com',
            ],
            'otrosDocumentos' => null,
            'ventaTercero' => null,
            'cuerpoDocumento' => [
                [
                    'numItem' => 1,
                    'cantidad' => 1,
                    'codigo' => null,
                    'uniMedida' => 99,
                    'descripcion' => 'Algo de algo',
                    'precioUni' => 1253.49,
                    'montoDescu' => 0,
                    'ventaGravada' => 1253.49,
                    'tributos' => null,
                    'noGravado' => 0
                ],
            ],
            'resumen' => [
                'totalGravada' => 1253.49,
                'descuento' => 0,
                'porcentajeDescuento' => 0,
                'totalDescu' => 0,
                'seguro' => null,
                'flete' => null,
                'montoTotalOperacion' => 1253.49,
                'totalNoGravado' => 0,
                'totalPagar' => 1253.49,
                'totalLetras' => $this->numberToLetter->convert(1253.49),
                'condicionOperacion' => 1,
                'pagos' => [
                    'codigo' => '',
                    'montoPago' => 1253.49,
                    'referencia' => null,
                    'plazo' => null,
                    'periodo' => null,
                ],
                'codIncoterms' => null,
                'descIncoterms' => null,
                'numPagoElectronico' => null,
                'observaciones' => null,
            ],
            'apendice' => null,
        ];
    }
}
