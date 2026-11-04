<?php

namespace App\Services\v1\management\admin\profiles;

use App\DTOs\v1\management\admin\profiles\InternetDTO;
use App\Models\Management\Profiles\InternetModel;

class InternetService
{
    public function create(InternetDTO $internetDTO): InternetModel
    {
        return InternetModel::create($internetDTO->toArray());
    }

    public function update(InternetModel $internet, InternetDTO $internetDTO): InternetModel
    {
        $internet->update($internetDTO->toArray());
        return $internet;
    }
}
