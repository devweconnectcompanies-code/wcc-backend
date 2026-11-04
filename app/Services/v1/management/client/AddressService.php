<?php

namespace App\Services\v1\management\client;

use App\DTOs\v1\management\client\AddressDTO;
use App\Models\Clients\AddressModel;

class AddressService
{
    public function createAddress(AddressDTO $data): AddressModel
    {
        return AddressModel::create($data->toArray());
    }

    public function updateAddress(AddressModel $address, AddressDTO $data): AddressModel
    {
        $address->update($data->toArray());
        return $address;
    }
}
