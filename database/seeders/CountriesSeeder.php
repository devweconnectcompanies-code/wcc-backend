<?php

namespace Database\Seeders;

use App\Models\Configuration\Geography\CountryModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = json_decode(File::get(database_path('seeders/data/countries.json')), true);

        foreach ($countries as $country) {
            $prefix = preg_replace('/\D+/', '', $country['phoneCode']);

            CountryModel::create([
                'es_name' => $country['nameES'],
                'en_name' => $country['nameEN'],
                'iso_2' => $country['iso2'],
                'iso_3' => $country['iso3'],
                'phone_prefix' => (int)$prefix,
                'status_id' => 1,
            ]);
        }
    }
}
