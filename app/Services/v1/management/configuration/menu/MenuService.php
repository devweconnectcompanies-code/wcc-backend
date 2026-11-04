<?php

namespace App\Services\v1\management\configuration\menu;

use App\DTOs\v1\management\configuration\menu\MenuDTO;
use App\Models\Configuration\MenuModel;

class MenuService
{
    public function create(MenuDTO $DTO): MenuModel
    {
        return MenuModel::query()->create($DTO->toArray());
    }

    public function update(MenuModel $menuModel, MenuDTO $DTO): MenuModel
    {
        $menuModel->update($DTO->toArray());
        return $menuModel;
    }
}
