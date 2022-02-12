<?php

use App\Models\IntervalUnit;
use Illuminate\Database\Seeder;

class IntervalUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $intervalUnits = [];

        foreach (config('interval.units') as $key => $value) {
            $intervalUnits[] = ['name' => $value];
        }

        IntervalUnit::insert($intervalUnits);
    }
}
