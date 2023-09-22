<?php

namespace Database\Seeders;

use App\Models\Continent;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = config('countryIsoCodes');
        foreach ($countries as $c) {
            Country::updateOrCreate([
                'name' => $c['name']
            ], [
                'name' => $c['name'],
                'iso_alpha_2'=> $c['alpha2'],
                'iso_alpha_3'=>$c['alpha3']
            ]);
        }
    }

}
