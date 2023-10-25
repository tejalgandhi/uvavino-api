<?php

namespace Database\Seeders;

use App\Models\Continent;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

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
                'iso_alpha_2'=> strtoupper($c['alpha2']),
                'iso_alpha_3'=>strtoupper($c['alpha3']),
                'phone_code'=>strtoupper($c['phonecode'])
            ]);
        }
    }

}
