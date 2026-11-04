<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;

class ComprobanteDonacionStrategy extends BaseDTEStrategy
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
            'fecEmi' => true,
            'horEmi' => true,
            'tipoMoneda' => true,
            'tipoContingencia' => false,
            'motivoContin' => false,
        ];
    }

    /***
     * @param array $data
     * @return array
     * @throws \Random\RandomException
     */
    protected function buildBody(array $data): array
    {
        $donante = $this->applyFieldSchema(
            array_merge(
                ['tipoDocumento' => '36', 'numDocumento' => $this->issuerUtils->getNit()],
                array_slice($this->emisorBase(), 1)
            ),
            [
                'tipoDocumento' => true,
                'numDocumento' => true,
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
                'nit' => false,
            ]
        );

        return [
            'identificacion' => $this->identificacion(DocumentTypes::COMPROBANTE_DONACION),
            'donatario' => $donante,
            'donante' => [
                'tipoDocumento' => '13',
                'numDocumento' => '000000000',
                'nrc' => '00000000',
                'nombre' => 'John Doe',
                'codActividad' => '10002',
                'descActividad' => 'Pensionado',
                'direccion' => [
                    'departamento' => '06',
                    'municipio' => '24',
                    'complemento' => 'En algún lugar de Panchimalco.',
                ],
                'telefono' => '22832509',
                'correo' => 'johndoe@gmail.com',
                'codDomiciliado' => 2,
                'codPais' => 'SV',
            ],
            'otrosDocuments' => [
                [
                    'codDocAsociado' => '1', // 1. Emisor, 2. Receptor,
                    'descDocumento' => 'Identificación del documento.',  // string <= 100
                    'detalleDocumento' => 'Descripción del documento asociado.',  // string <= 300
                ],
            ],
            'cuerpoDocumento' => [
                [
                    'numItem' => 1,
                    'tipoDonacion' => 1, // 1. Efectivo, 2. Bien, 3. Servicio
                    'cantidad' => 1,
                    'codigo' => null,
                    'uniMedida' => 99,
                    'descripcion' => 'Donación',
                    'depreciacion' => 0,
                    'valorUni' => 100.00,
                    'valor' => 100.00,
                ],
            ],
            'resumen' => [
                'valorTotal' => 100.00,
                'totalLetras' => $this->numberToLetter->convert(100.00),
                //  Puede ser null
                'pagos' => [
                    [
                        'codigo' => '03',   // Tipo DTE || 99 || null
                        'montoPago' => 100.00,
                        'referencia' => null,   //  string <= 50
                    ],
                ],
            ],
            'apendice' => null,
        ];
    }

    protected function donante(): array
    {
        return array_merge(['tipoDocumento' => 36], $this->emisor());
    }

    protected function emisorFieldMap(): array
    {
        return [
            'nit' => 'numDocumento',
        ];
    }
}
