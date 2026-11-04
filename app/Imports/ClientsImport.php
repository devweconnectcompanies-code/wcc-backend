<?php

namespace App\Imports;

use App\Models\Clients\ClientModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientsImport implements ToCollection, WithHeadingRow
{
    /***
     * @param Collection $collection
     * @return Collection
     */
    public function collection(Collection $collection): Collection
    {
        foreach ($collection as $row) {
            collect([
                'name' => $row['name'],
                'address' => $row['address'],
                'district' => $row['district'],
                'state' => $row['state'],
                'phone' => $row['phone'],
                'civil_status' => $row['civil_status'],
                'dui' => $row['dui'],
                'nit' => $row['nit'],
                'reference_name' => $row['reference_name'],
                'reference_phone' => $row['reference_phone'],
                'pppoe_user' => $row['pppoe_user'],
                'pppoe_password' => $row['pppoe_password'],
                'node' => $row['node'],
                'panel' => $row['panel'],
                'equipment' => $row['equipment'],
                'router' => $row['router'],
                'profile' => $row['profile'],
                'price' => $row['price'],
                'installation_price' => $row['installation_price'],
                'installation_date' => $row['installation_date'],
                'renovation_date' => $row['renovation_date'],
                'actual_profile' => $row['actual_profile'],
                'renovation_price' => $row['renovation_price'],
                'end_date' => $row['end_date'],
            ]);
        }
    }
}
