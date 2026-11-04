<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;

class NotaRemisionStrategy extends BaseDTEStrategy
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
     * @param array $data
     * @return array[]
     * @throws \Random\RandomException
     */
    protected function buildBody(array $data): array
    {
        return [
            'identificacion' => $this->identificacion(DocumentTypes::NOTA_REMISION, 3),
            'documentoRelacionado' => [
                [
                    'tipoDocumento' => '03',    // 01. Factura de consumidor final, 03. Crédito fiscal
                    'tipoGeneracion' => 2,   // 1. Físico, 2. Electrónico
                    'numeroDocumento' => 'DTE-03-NTPS2026-000000000000153',
                    'fechaEmision' => '2026-01-16',
                ],
            ],
            'emisor' => $this->emisor(),
            'receptor' => [
                'tipoDocumento' => '13',
                'numDocumento' => '123456789',
                'nrc' => null,
                'nombre' => 'John Doe',
                'codActividad' => null,
                'descActividad' => null,
                'nombreComercial' => 'John Doe',
                'direccion' => [
                    'departamento' => $this->issuerUtils->getState(),
                    'municipio' => $this->issuerUtils->getMunicipality(),
                    'complemento' => 'En algún lugar de Moncagua',
                ],
                'telefono' => '79784513',
                'correo' => 'johndoe@gmail.com',
                'bienTitulo' => 'Ni idea',
            ],
            'ventaTercero' => null,
            'cuerpoDocumento' => [
                [
                    'numItem' => 1,
                    'tipoItem' => 2,
                    'numeroDocumento' => 'DTE-03-NTPS2026-000000000000153',
                    'codigo' => null,
                    'codTributo' => null,
                    'descripcion' => 'Algo de algo',
                    'cantidad' => 1,
                    'uniMedida' => 99,
                    'precioUni' => 176.99,
                    'montoDescu' => 0,
                    'ventaNoSuj' => 0,
                    'ventaExenta' => 0,
                    'ventaGravada' => 176.99,
                    'tributos' => ['20'],
                ],
            ],
            'resumen' => [
                'totalNoSuj' => 0,
                'totalExenta' => 0,
                'totalGravada' => 176.99,
                'subTotalVentas' => 176.99,
                'descuNoSuj' => 0,
                'descuExenta' => 0,
                'descuGravada' => 0,
                'porcentajeDescuento' => 0,
                'totalDescu' => 0,
                'tributos' => [
                    'codigo' => '20',
                    'descripcion' => 'Impuesto al valor agregado 13%',
                    'valor' => 23.01,
                ],
                'subTotal' => 176.99,
                'montoTotalOperacion' => 200.00,
                'totalLetras' => $this->numberToLetter->convert(200.00),
            ],
            'extension' => null,
            'apendice' => null,
        ];
    }
}
