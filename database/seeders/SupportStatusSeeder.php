<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supports\StatusModel;
use Illuminate\Support\Facades\File;

class SupportStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = json_decode(File::get(database_path("seeders/data/supports_status.json")));

        foreach ($status as $item) {
            StatusModel::query()->create([
                'name' => $item->name,
                'badge_color' => $item->badge,
                'status_id' => true,
            ]);
        }
    }
}
