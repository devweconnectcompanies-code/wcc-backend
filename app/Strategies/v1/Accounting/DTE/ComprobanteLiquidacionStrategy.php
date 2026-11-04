<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;

class ComprobanteLiquidacionStrategy extends BaseDTEStrategy
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
            'tipoContingencia' => false,
            'motivoContin' => false,
            'fecEmi' => true,
            'horEmi' => true,
            'tipoMoneda' => true,
        ];
    }

    /***
     * @param array $data
     * @return array
     * @throws \Random\RandomException
     */
    protected function buildBody(array $data): array
    {
        return [
            'identificacion' => $this->identificacion(DocumentTypes::COMPROBANTE_LIQUIDACION),
            'emisor' => $this->emisor(),
            'receptor' => [
                'nit' => '12052302951012',
                'nrc' => '12345678',
                'nombre' => 'John Doe',
                'codActividad' => '10001',
                'descActividad' => 'Empleados',
                'nombreComercial' => null,
                'direccion' => [
                    'departamento' => 12,
                    'municipio' => 23,
                    'complemento' => 'En algún lugar de San Jorge.',
                ],
                'telefono' => '73780050',
                'correo' => 'jonhdoe@gmail.com',
            ],
            'cuerpoDocumento' => [
                [
                    'numItem' => 1,
                    'tipoDte' => '01',    // 01, 03, 05, 06, 11
                    'tipoGeneracion' => 2,    // 1. Físico, 2. Electrónico
                    'numeroDocumento' => 'DTE-01-NTPS2026-000000000000345',
                    'fechaGeneracion' => '2026-01-15',
                    'ventaNoSuj' => 0,
                    'ventaExenta' => 0,
                    'ventaGravada' => 0,
                    'exportaciones' => 0,
                    'tributos' => [null],   // 20, C3, 59, 71, D1, C8, D5, D4
                    'ivaItem' => 0,
                    'obsItem' => 'Something',   // string > 3 && <= 3000
                ],
            ],
            'resumen' => [
                'totalNoSuj' => 0,
                'totalExenta' => 0,
                'totalGravada' => 0,
                'totalExportacion' => 0,
                'subTotalVentas' => 0,
                'tributos' => [null],     // 20, C3, 59, 71, D1, C8, D5, D4
                'montoTotalOperacion' => 0,
                'ivaPerci' => 0,
                'total' => 0,
                'totalLetras' => $this->numberToLetter->convert(0.00),
                'condicionOperacion' => 1,   // 1. Contado, 2. Crédito, 3. Otro
            ],
            'extension' => null,
            'apendice' => null,
        ];
    }
}
