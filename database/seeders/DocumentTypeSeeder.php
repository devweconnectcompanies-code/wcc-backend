<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration\Clients\DocumentTypeModel;
use Illuminate\Support\Facades\File;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = json_decode(File::get(database_path('/seeders/data/document_types.json')), true);

        foreach ($documents as $document) {
            DocumentTypeModel::create([
                'name' => $document['name'],
                'code' => $document['code'],
                'status_id' => 1
            ]);
        }
    }
}
