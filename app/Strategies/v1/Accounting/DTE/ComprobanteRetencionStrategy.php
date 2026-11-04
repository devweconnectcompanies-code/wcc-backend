<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;

class ComprobanteRetencionStrategy extends BaseDTEStrategy
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
            'direccion' => true,
            'telefono' => true,
            'codEstableMH' => 'codigoMH',
            'codEstable' => 'codigo',
            'codPuntoVentaMH' => 'puntoVentaMH',
            'codPuntoVenta' => 'puntoVenta',
            'correo' => true,
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
            'identificacion' => $this->identificacion(DocumentTypes::COMPROBANTE_RETENCION),
            'emisor' => $this->emisor(),
            'receptor' => [
                'tipoDocumento' => '13',    //  36. NIT, 13. DUI, 37. Otro, 03. Pasaporte, 02. Carnet de residente
                'numDocumento' => '000000002',
                'nrc' => '12345678',
                'nombre' => 'John Doe',
                'codActividad' => '10001',
                'descActividad' => 'Empleado',
                'nombreComercial' => 'John Doe',
                'direccion' => [
                    'departamento' => '05',
                    'municipio' => '27',
                    'complemento' => 'En algún lugar de Tamanique',
                ],
                'telefono' => '79780050',
                'correo' => 'johndoe@gmail.com',
            ],
            'cuerpoDocumento' => [
                [
                    'numItem' => 1,
                    'tipoDte' => '14',  //  01. Factura, 03. Crédito Fiscal, 14. Factura de Sujeto Excluido
                    'tipoDoc' => 1, // 1. Físico, 2. Electrónico
                    'numDocumento' => 'DTE-14-NTPS2026-000000000000005',
                    'fechaEmision' => '2026-02-08',
                    'montoSujetoGrav' => 0,
                    'codigoRetencionMH' => 'C4',    // 22. IVA Retenido 1%, C4. IVA 13%, C9. Otras retenciones de IVA
                    'ivaRetenido' => 0,
                    'descripcion' => 'Descripción.',
                ],
            ],
            'resumen' => [
                'totalSujetoRetencion' => 0,
                'totalIVAretenido' => 0,
                'totalIVAretenidoLetras' => $this->numberToLetter->convert(0.00),
            ],
            'extension' => null,
            'apendice' => null,
        ];
    }

    protected function emisorFieldMap(): array
    {
        return [
            'codEstableMH' => 'codigoMH',
            'codEstable' => 'codigo',
            'codPuntoVentaMH' => 'puntoVentaMH',
            'codPuntoVenta' => 'puntoVenta',
        ];
    }
}
