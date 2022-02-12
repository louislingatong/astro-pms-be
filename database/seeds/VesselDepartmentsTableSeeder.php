<?php

use App\Models\VesselDepartment;
use Illuminate\Database\Seeder;

class VesselDepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vesselDepartments = [];

        foreach (config('vessel.departments') as $key => $value) {
            $vesselDepartments[] = ['name' => $value];
        }

        VesselDepartment::insert($vesselDepartments);
    }
}
