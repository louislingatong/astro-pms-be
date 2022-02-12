<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserStatusesSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(VesselDepartmentsTableSeeder::class);
        $this->call(VesselsTableSeeder::class);
        $this->call(RankTypesTableSeeder::class);
        $this->call(RanksTableSeeder::class);
        $this->call(IntervalUnitsTableSeeder::class);
    }
}
