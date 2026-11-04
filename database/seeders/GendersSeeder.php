<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration\Clients\GenderModel;

class GendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $array = ['Masculino', 'Femenino'];
        foreach ($array as $value) {
            GenderModel::create([
                'name' => $value,
                'status_id' => 1,
            ]);
        }
    }
}
