<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration\Clients\ClientTypeModel;

class ClientTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Residencial', 'Corporativo', 'Gratuito'];

        foreach ($types as $type) {
            ClientTypeModel::create([
                'name' => $type,
                'status_id' => 1
            ]);
        }
    }
}
