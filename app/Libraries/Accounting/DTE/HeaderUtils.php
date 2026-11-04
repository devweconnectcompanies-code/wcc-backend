<?php

namespace App\Libraries\Accounting\DTE;

use App\Enums\v1\Billing\DocumentTypes;
use App\Models\Accounting\DTEModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HeaderUtils
{
    protected const FACTURA_INCREMENT = 1;
    protected const CCF_INCREMENT = 1;
    protected const FSE_INCREMENT = 1;

    protected const NC_INCREMENT = 1;
    protected const ND_INCREMENT = 1;
    protected const MAX_CORRELATIVE_LENGTH = 15;

    /***
     * Ambiente de producción del DTE
     * @return string
     */
    public function ambient(): string
    {
        return '00';
    }

    /***
     * Genera el número de control según documento a emitir.
     *
     * @param DocumentTypes $type
     * @return string
     * @throws \Throwable
     */
    public function controlNumber(DocumentTypes $type): string
    {
        $year = Carbon::now()->year;

        $correlative = DB::transaction(function () use ($type, $year) {
            $lastDTE = DTEModel::query()
                ->where('document_type_id', $type->value)
                ->whereYear('generation_datetime', $year)
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $lastNumber = 0;

            if ($lastDTE && preg_match('/-(\d{15})$/', $lastDTE->control_number, $matches)) {
                $lastNumber = (int)$matches[1];
            }

            $startNumber = $this->getStartNumberForType($type);
            $newNumber = $lastNumber > 0 ? $lastNumber + 1 : $startNumber;

            if (strlen((string)$newNumber) > self::MAX_CORRELATIVE_LENGTH) {
                throw new \RuntimeException("Se ha alcanzado el límite máximo de correlativos para {$type->label()}");
            }

            return $newNumber;
        });

        $tail = str_pad($correlative, self::MAX_CORRELATIVE_LENGTH, "0", STR_PAD_LEFT);

        return "DTE-{$type->code()}-S001P001-{$tail}";
    }

    /***
     * Obtiene el incremento según el tipo de documento.
     *
     * @param DocumentTypes $type
     * @return int
     */
    protected function getStartNumberForType(DocumentTypes $type): int
    {
        return match ($type->value) {
            DocumentTypes::FACTURA->value => self::FACTURA_INCREMENT,
            DocumentTypes::CREDITO_FISCAL->value => self::CCF_INCREMENT,
            DocumentTypes::FACTURA_SUJETO_EXCLUIDO->value => self::FSE_INCREMENT,
            DocumentTypes::NOTA_CREDITO->value => self::NC_INCREMENT,
            DocumentTypes::NOTA_DEBITO->value => self::ND_INCREMENT,
            default => throw new \InvalidArgumentException("{$type->label()} no admitido para generar DTE"),
        };
    }

    /***
     * Crea el código de generación del documento.
     * @return string
     * @throws \Random\RandomException
     */
    public function generationCode(): string
    {
        $code = $this->generateHexChar(8) . '-';
        $code .= $this->generateHexChar(4) . '-';
        $code .= $this->generateHexChar(4) . '-';
        $code .= $this->generateHexChar(4) . '-';
        $code .= $this->generateHexChar(12);

        return $code;
    }

    /***
     * Obtiene la fecha en la que se genera el documento.
     * @return string
     */
    public function getDate(): string
    {
        return Carbon::today()->toDateString();
    }

    /***
     * Obtiene la hora exacta en la que se genera el documento.
     * @return string
     */
    public function getHour(): string
    {
        return Carbon::now()->toTimeString();
    }

    /***
     * Tipo de moneda con la cual se emitirá el documento.
     * @return string
     */
    public function getCurrency(): string
    {
        return 'USD';
    }

    public function getNit(): string
    {
        return '';
    }

    /***
     * Genera cadenas hexadecimales de diferente tamaño.
     * @param int $length
     * @return string
     * @throws \Random\RandomException
     */
    private function generateHexChar(int $length): string
    {
        if ($length <= 0) {
            throw new \InvalidArgumentException("La longitud de la cadena debe ser mayor a 0.");
        }

        $bytes = ceil($length / 2);
        $hexString = bin2hex(random_bytes($bytes));

        return strtoupper(substr($hexString, 0, $length));
    }
}
