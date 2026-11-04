<?php

namespace App\Strategies\v1\Supports\Process;

use App\Contracts\v1\Supports\SupportTypeInterface;
use App\Models\Supports\SupportModel;

class Uninstall extends BaseSupport implements SupportTypeInterface
{

    public function handle(array $data, string $ticket): SupportModel
    {
        return $this->createSupport($data, $ticket);
    }

    public function update(SupportModel $support, array $data): SupportModel
    {
        return $this->updateSupport($support, $data);
    }
}
