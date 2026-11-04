<?php

namespace App\Enums\v1\Billing;

use App\Contracts\v1\Accounting\DTE\DTEGeneratorInterface;
use App\Contracts\v1\Accounting\DTE\DTEPrinterInterface;
use App\Strategies\v1\Accounting\DTE\AnulacionStrategy;
use App\Strategies\v1\Accounting\DTE\ComprobanteDonacionStrategy;
use App\Strategies\v1\Accounting\DTE\ComprobanteLiquidacionStrategy;
use App\Strategies\v1\Accounting\DTE\ComprobanteRetencionStrategy;
use App\Strategies\v1\Accounting\DTE\CreditoFiscalStrategy;
use App\Strategies\v1\Accounting\DTE\DocumentoContableLiquidacionStrategy;
use App\Strategies\v1\Accounting\DTE\FacturaExportacionStrategy;
use App\Strategies\v1\Accounting\DTE\FacturaStrategy;
use App\Strategies\v1\Accounting\DTE\FacturaSujetoExcluidoStrategy;
use App\Strategies\v1\Accounting\DTE\NotaCreditoStrategy;
use App\Strategies\v1\Accounting\DTE\NotaDebitoStrategy;
use App\Strategies\v1\Accounting\DTE\NotaRemisionStrategy;
use App\Strategies\v1\Accounting\DTE\Prints\CreditoFiscalPrintStrategy;
use App\Strategies\v1\Accounting\DTE\Prints\FacturaPrintStrategy;
use App\Strategies\v1\Accounting\DTE\Prints\FacturaSujetoExcluidoPrintStrategy;
use App\Strategies\v1\Accounting\DTE\Prints\NotaCreditoPrintStrategy;
use App\Strategies\v1\Accounting\DTE\Prints\NotaDebitoPrintStrategy;

enum DocumentTypes: int
{
    case FACTURA = 1;
    case CREDITO_FISCAL = 2;
    case NOTA_REMISION = 3;
    case NOTA_CREDITO = 4;
    case NOTA_DEBITO = 5;
    case COMPROBANTE_RETENCION = 6;
    case COMPROBANTE_LIQUIDACION = 7;
    case DOCUMENTO_CONTABLE_LIQUIDACION = 8;
    case FACTURA_EXPORTACION = 9;
    case FACTURA_SUJETO_EXCLUIDO = 10;
    case COMPROBANTE_DONACION = 11;
    case ANULACION = 12;

    public function label(): string
    {
        return match ($this) {
            self::FACTURA => 'Factura',
            self::CREDITO_FISCAL => 'Crédito fiscal',
            self::NOTA_REMISION => 'Nota de remisión',
            self::NOTA_CREDITO => 'Nota de crédito',
            self::NOTA_DEBITO => 'Nota de debito',
            self::COMPROBANTE_RETENCION => 'Comprobante de retención',
            self::COMPROBANTE_LIQUIDACION => 'Comprobante de liquidación',
            self::DOCUMENTO_CONTABLE_LIQUIDACION => 'Documento contable de liquidación',
            self::FACTURA_EXPORTACION => 'Factura de exportación',
            self::FACTURA_SUJETO_EXCLUIDO => 'Factura de sujeto excluído',
            self::COMPROBANTE_DONACION => 'Comprobante de donación',
            self::ANULACION => 'Anulación',
            default => 'No identificado',
        };
    }

    public function code(): string
    {
        return match ($this) {
            self::FACTURA => '01',
            self::CREDITO_FISCAL => '03',
            self::NOTA_REMISION => '04',
            self::NOTA_CREDITO => '05',
            self::NOTA_DEBITO => '06',
            self::COMPROBANTE_RETENCION => '07',
            self::COMPROBANTE_LIQUIDACION => '08',
            self::DOCUMENTO_CONTABLE_LIQUIDACION => '09',
            self::FACTURA_EXPORTACION => '11',
            self::FACTURA_SUJETO_EXCLUIDO => '14',
            self::COMPROBANTE_DONACION => '15',
            self::ANULACION => '99',
            default => '00',
        };
    }

    public function strategyClass(): string
    {
        return match ($this) {
            self::FACTURA => FacturaStrategy::class,
            self::CREDITO_FISCAL => CreditoFiscalStrategy::class,
            self::NOTA_REMISION => NotaRemisionStrategy::class,
            self::NOTA_CREDITO => NotaCreditoStrategy::class,
            self::NOTA_DEBITO => NotaDebitoStrategy::class,
            self::COMPROBANTE_RETENCION => ComprobanteRetencionStrategy::class,
            self::COMPROBANTE_LIQUIDACION => ComprobanteLiquidacionStrategy::class,
            self::DOCUMENTO_CONTABLE_LIQUIDACION => DocumentoContableLiquidacionStrategy::class,
            self::FACTURA_EXPORTACION => FacturaExportacionStrategy::class,
            self::FACTURA_SUJETO_EXCLUIDO => FacturaSujetoExcluidoStrategy::class,
            self::COMPROBANTE_DONACION => ComprobanteDonacionStrategy::class,
            self::ANULACION => AnulacionStrategy::class,
        };
    }

    public function printStrategyClass(): string
    {
        return match ($this) {
            self::FACTURA => FacturaPrintStrategy::class,
            self::CREDITO_FISCAL => CreditoFiscalPrintStrategy::class,
            self::NOTA_CREDITO => NotaCreditoPrintStrategy::class,
            self::NOTA_DEBITO => NotaDebitoPrintStrategy::class,
            self::FACTURA_SUJETO_EXCLUIDO => FacturaSujetoExcluidoPrintStrategy::class,
        };
    }

    public function strategy(): DTEGeneratorInterface
    {
        return app($this->strategyClass());
    }

    public function printStrategy(): DTEPrinterInterface
    {
        return app($this->printStrategyClass());
    }

    public function folderName(): string
    {
        return $this->name;
    }

    public function totalAmountKey(): string
    {
        return match ($this) {
            self::NOTA_CREDITO, self::NOTA_DEBITO => 'montoTotalOperacion',
            default => 'totalPagar',
        };
    }
}
