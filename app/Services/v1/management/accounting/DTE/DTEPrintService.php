<?php

namespace App\Services\v1\management\accounting\DTE;

use App\Contexts\Accounting\DTEPrintContext;
use App\Enums\v1\Billing\DocumentTypes;
use App\Models\Accounting\DTEModel;
use Barryvdh\DomPDF\PDF;

readonly class DTEPrintService
{
    public function __construct(private readonly DTEPrintContext $context)
    {

    }

    /****
     * Realiza el proceso de generar el PDF con los datos del documento emitido.
     *
     * @param int $dteId
     * @return PDF
     */
    public function print(int $dteId): PDF
    {
        $dte = DTEModel::query()
            ->with(['client', 'dte_type'])
            ->findOrFail($dteId);

        $type = DocumentTypes::from($dte->document_type_id);
        $this->context->setStrategy($type->printStrategy());

        return $this->context->execute($dte);
    }
}
