<?php

namespace Database\Seeders;

use App\Models\Billing\Options\StatusModel;
use Illuminate\Database\Seeder;

class InvoiceStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Emitida', 'color' => '#697a21'],
            ['name' => 'Pendiente', 'color' => '#023e8a'],
            ['name' => 'Solvente', 'color' => '#386641'],
            ['name' => 'Vencida', 'color' => '#9a031e'],
            ['name' => 'Anulada', 'color' => '#415a77'],
        ];

        foreach ($data as $status) {
            StatusModel::query()
                ->create(
                    [
                        'name' => $status['name'],
                        'badge_color' => $status['color'],
                        'status_id' => 1
                    ]
                );
        }
    }
}
