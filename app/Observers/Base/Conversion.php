<?php

namespace App\Observers\Base;

use Illuminate\Support\Collection;
use Carbon\Carbon;

class Conversion
{
    public function convert($data): Collection
    {
        return collect($data)->map(function ($val) {
            return $this->convertDate($val);
        });
    }

    private function convertDate($val)
    {
        if ($val instanceof \DateTimeInterface) {
            return Carbon::parse($val)->setTimezone('America/El_Salvador')->format('Y-m-d H:i:s');
        }

        return $val;
    }
}
