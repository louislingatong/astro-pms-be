<?php

use App\Models\EmployeeDepartment;
use Illuminate\Database\Seeder;

class EmployeeDepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employeeDepartments = [];

        foreach (config('employee.departments') as $key => $value) {
            $employeeDepartments[] = ['name' => $value];
        }

        EmployeeDepartment::insert($employeeDepartments);
    }
}
