<?php

namespace App\Strategies\v1\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;

class NotaCreditoStrategy extends BaseNotaStrategy
{
    protected function documentType(): DocumentTypes
    {
        return DocumentTypes::NOTA_CREDITO;
    }

    /***
     * Elementos requeridos en el bloque "resumen".
     *
     * @return string[]
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
            'montoTotalOperacion',
            'ivaPerci',
            'totalIva',
            'ivaRete',
            'totalNoGravado',
            'totalPagar',
            'totalLetras',
            'condicionOperacion',
            'observaciones',
            'codigoRetencionMH'
        ];
    }
}
