<?php

namespace App\Libraries;

use NumberFormatter;

class NumberToLetter
{
    /***
     * Convierte un decimal a string con decimales xx/100
     * @param float $number
     * @return string
     */
    public function convert(float $number): string
    {
        $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);
        $integerPart = floor($number);
        $decimalPart = round(($number - $integerPart) * 100);
        $integerText = strtoupper($formatter->format($integerPart));
        return sprintf('%s DÓLARES CON %02d/100', $integerText, $decimalPart);
    }
}
