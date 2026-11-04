<?php

namespace App\Contexts\Accounting;

use App\Contracts\v1\Accounting\DTE\DTEPrinterInterface;
use App\Models\Accounting\DTEModel;
use Barryvdh\DomPDF\PDF;

class DTEPrintContext
{
    private DTEPrinterInterface $strategy;

    public function setStrategy(DTEPrinterInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function execute(DTEModel $model): PDF
    {
        return $this->strategy->print($model);
    }
}
