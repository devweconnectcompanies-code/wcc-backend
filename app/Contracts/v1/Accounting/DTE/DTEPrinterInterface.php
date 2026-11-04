<?php

namespace App\Contracts\v1\Accounting\DTE;

use App\Models\Accounting\DTEModel;
use Barryvdh\DomPDF\PDF;

interface DTEPrinterInterface
{
    public function print(DTEModel $dte): PDF;
}
