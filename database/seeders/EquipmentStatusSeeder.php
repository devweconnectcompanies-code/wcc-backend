<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration\Infrastructure\EquipmentStatusModel;
use Illuminate\Support\Facades\File;

class EquipmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(File::get(database_path("seeders/data/equipment_status.json")), true);

        foreach ($data as $item) {
            EquipmentStatusModel::query()->create([
                'name' => $item['name'],
                'description' => $item['description'],
                'badge_color' => $item['color'],
                'status_id' => 1,
            ]);
        }
    }
}
