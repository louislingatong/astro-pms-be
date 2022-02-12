<?php

use App\Models\RankType;
use Illuminate\Database\Seeder;

class RankTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rankTypes = [];

        foreach (config('rank.types') as $key => $value) {
            $rankTypes[] = ['name' => $value];
        }

        RankType::insert($rankTypes);
    }
}
