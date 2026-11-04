<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;

class NotaDebitoStrategy extends BaseNotaStrategy
{
    protected function documentType(): DocumentTypes
    {
        return DocumentTypes::NOTA_DEBITO;
    }

    /***
     * Elementos requeridos en el bloque 'resumen'.
     *
     * @return array
     */
    protected function resumenSchema(): array
    {
        return [
            'totalNoSuj',
            'totalExenta',
            'totalGravada',
            'subTotalVentas',
            'totalDescu',
            'tributos',
            'ivaPerci',
            'totalIva',
            'ivaRete',
            'montoTotalOperacion',
            'totalNoGravado',
            'totalPagar',
            'totalLetras',
            'condicionOperacion',
            'numPagoElectronico',
            'observaciones',
            'codigoRetencionMH'
        ];
    }
}
