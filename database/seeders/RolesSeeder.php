<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Root', 'homepage' => '/dashboard'],
            ['name' => 'Administrador', 'homepage' => '/dashboard'],
            ['name' => 'Secretaria', 'homepage' => '/clientes'],
            ['name' => 'Técnico', 'homepage' => '/soportes'],
        ];
        foreach ($roles as $role) {
            Role::create([
                'name' => $role['name'],
                'homepage' => $role['homepage'],
            ]);
        }
    }
}
