<?php

use App\Models\UserStatus;
use Illuminate\Database\Seeder;

class UserStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [];

        foreach (config('user.statuses') as $key => $value) {
            $statuses[] = ['name' => $value];
        }

        UserStatus::insert($statuses);
    }
}
