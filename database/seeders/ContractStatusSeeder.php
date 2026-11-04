<?php

namespace Database\Seeders;

use App\Models\Configuration\Clients\ContractStateModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = ['Activo', 'Inactivo', 'Cancelado/Rescindido', 'Vencido', 'En revisión', 'Renovado'];

        foreach ($status as $item) {
            ContractStateModel::create([
                'name' => $item,
                'status_id' => 1,
            ]);
        }
    }
}
