<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration\Clients\PhoneTypeModel;

class PhoneTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Teléfono fijo', 'Telefóno celular'];

        foreach ($types as $type) {
            PhoneTypeModel::create([
                'name' => $type,
                'status_id' => 1
            ]);
        }
    }
}
