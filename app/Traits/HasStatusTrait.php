<?php

namespace App\Traits;

trait HasStatusTrait
{
    public function getStatusAttribute(): array
    {
        return [
            'id' => (int)$this->status_id,
            'name' => $this->status_id ? 'Activo' : 'Inactivo',
        ];
    }
}
