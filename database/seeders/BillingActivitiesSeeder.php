<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Billing\Options\ActivityModel;
use Maatwebsite\Excel\Facades\Excel;

class BillingActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/imports/catalogos.xlsx');
        $rows = Excel::toArray([], $path)[0];

        foreach ($rows as $row) {
            $lenght = strlen($row[0]);
            $code = $lenght <= 4 ? "0" . $row[0] : $row[0];
            ActivityModel::create([
                'code' => $code,
                'name' => $row[1],
                'status_id' => 1,
            ]);
        }
    }
}
