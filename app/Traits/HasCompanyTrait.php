<?php

namespace App\Traits;

trait HasCompanyTrait
{
    public function getCompanyAttribute(): array
    {
        $companyId = (int)$this->company_id;

        $name = match ($companyId) {
            1 => 'Netplus',
            2 => 'Cable Color',
        };
        return [
            'id' => $companyId,
            'name' => $name
        ];
    }
}
