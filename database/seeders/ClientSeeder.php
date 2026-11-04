<?php

namespace Database\Seeders;

use App\Imports\ClientsImport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = storage_path('app/imports/clients.xlsx');
        $client = Excel::toCollection(new ClientsImport, $path)[0];

        foreach ($client as $row) {
            $name = strtolower($row['name']);
            dd(ucwords($name));
        }
    }
}
