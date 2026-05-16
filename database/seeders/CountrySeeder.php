<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'India'],
            ['name' => 'UAE'],
            ['name' => 'US'],
            ['name' => 'Canada'],
            ['name' => 'UK'],
            ['name' => 'Sri Lanka'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['name' => $country['name']], $country);
        }
    }
}
