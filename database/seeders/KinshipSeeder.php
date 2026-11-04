<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration\Clients\KinshipModel;

class KinshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relationships = [
            "Padre",
            "Madre",
            "Hijo/a",
            "Hermano/a",
            "Abuelo/a",
            "Nieto/a",
            "Tío/a",
            "Sobrino/a",
            "Primo/a",
            "Suegro/a",
            "Nuera",
            "Yerno",
            "Cuñado/a",
            "Concuñado/a",
            "Padrastro",
            "Madrastra",
            "Hijastro/a",
            "Hermanastro/a",
            "Bisabuelo/a",
            "Bisnieto/a",
            "Tatarabuelo/a",
            "Tataranieto/a",
            "Cónyuge",
            "Amigo/a"
        ];

        foreach ($relationships as $relationship) {
            KinshipModel::create([
                "name" => $relationship,
                'status_id' => 1
            ]);
        }
    }
}
