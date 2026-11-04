<?php

namespace App\Contracts\v1\Supports;

use App\Models\Supports\SupportModel;

interface SupportTypeInterface
{
    public function handle(array $data, string $ticket): SupportModel;

    public function update(SupportModel $support, array $data);
}
