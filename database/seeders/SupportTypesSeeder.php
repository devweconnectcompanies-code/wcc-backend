<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supports\TypeModel;
use Illuminate\Support\Facades\File;

class SupportTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = json_decode(File::get(database_path("seeders/data/support_types.json")), true);

        foreach ($types as $type) {
            TypeModel::query()->create([
                'name' => $type['name'],
                'badge_color' => $type['color'],
                'status_id' => true,
            ]);
        }
    }
}
