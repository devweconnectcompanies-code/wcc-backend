<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Billing\Options\DocumentTypeModel;
use Illuminate\Support\Facades\File;

class BillingDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = json_decode(File::get(database_path('seeders/data/billing_documents.json')), true);

        foreach ($documents as $document) {
            DocumentTypeModel::create([
                'name' => $document['name'],
                'code' => $document['code'],
                'status_id' => $document['status'],
            ]);
        }
    }
}
