<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;

class DocumentoContableLiquidacionStrategy extends BaseDTEStrategy
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
            'nombreComercial' => true,
            'tipoEstablecimiento' => true,
            'telefono' => true,
            'correo' => true,
            'direccion' => true,
            'codEstableMH' => 'codigoMH',
            'codEstable' => 'codigo',
            'codPuntoVentaMH' => 'puntoVentaMH',
            'codPuntoVenta' => 'puntoVentaContri',
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
            'identificacion' => $this->identificacion(DocumentTypes::DOCUMENTO_CONTABLE_LIQUIDACION),
            'emisor' => $this->emisor(),
            'receptor' => [
                'nit' => '000000000',
                'nrc' => '12345678',
                'nombre' => 'John Doe',
                'codActividad' => '10001',
                'descActividad' => 'Empleado',
                'nombreComercial' => 'John Doe',
                'tipoEstablecimiento' => '02',    // 01. Sucursal, 02. Casa Matriz, 04. Bodega, 07. Patio
                'direccion' => [
                    'departamento' => '09',
                    'municipio' => '11',
                    'complemento' => 'En algún lugar de Dolores.',
                ],
                'telefono' => '78520412',
                'correo' => 'johndoe@gmail.com',
                'codigoMH' => null,
                'puntoVentaMH' => null,
            ],
            'cuerpoDocumento' => [
                'periodoLiquidacionFechaInicio' => '2026-01-19',
                'periodoLiquidacionFechaFin' => '2026-03-19',
                'codLiquidacion' => null,
                'cantidadDoc' => 1,
                'valorOperaciones' => 0,
                'montoSinPercepcion' => 0,
                'descripSinPercepcion' => 0,
                'subTotal' => 0,
                'iva' => 0,
                'montoSujetoPercepcion' => 0,
                'ivaPercibido' => 0,
                'comision' => 0,
                'porcentComision' => null,
                'ivaComision' => 0,
                'liquidoApagar' => 0,
                'totalLetras' => $this->numberToLetter->convert(0.00),
                'observaciones' => null,
            ],
            'extension' => null,
            'apendice' => null,
        ];
    }
}
