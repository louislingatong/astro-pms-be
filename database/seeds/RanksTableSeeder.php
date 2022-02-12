<?php

use App\Models\Rank;
use App\Models\RankType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RanksTableSeeder extends Seeder
{
    /** @var array */
    protected $ranks = [
        [
            'short_name' => 'Master',
            'name' => 'Master',
            'type' => 'rank.types.sr_off_deck'
        ],
        [
            'short_name' => 'C/O',
            'name' => 'Chief Officer',
            'type' => 'rank.types.sr_off_deck'
        ],
        [
            'short_name' => '2/O',
            'name' => '2nd Officer',
            'type' => 'rank.types.jr_off_deck'
        ],
        [
            'short_name' => '3/O',
            'name' => '3rd Officer',
            'type' => 'rank.types.jr_off_deck'
        ],
        [
            'short_name' => 'C/E',
            'name' => 'Chief Engineer',
            'type' => 'rank.types.sr_off_engine'
        ],
        [
            'short_name' => '1AE',
            'name' => '1st Engineer',
            'type' => 'rank.types.sr_off_engine'
        ],
        [
            'short_name' => '2AE',
            'name' => '2nd Engineer',
            'type' => 'rank.types.jr_off_engine'
        ],
        [
            'short_name' => '3AE',
            'name' => '3rd Engineer',
            'type' => 'rank.types.jr_off_engine'
        ],
        [
            'short_name' => 'E/E',
            'name' => 'Electrician',
            'type' => 'rank.types.jr_off_engine'
        ],
        [
            'short_name' => 'BSN',
            'name' => 'Boatswain',
            'type' => 'rank.types.deck_ratings'
        ],
        [
            'short_name' => 'AB',
            'name' => 'Able-Bodied Seaman',
            'type' => 'rank.types.deck_ratings'
        ],
        [
            'short_name' => 'OS',
            'name' => 'Ordinary Seaman',
            'type' => 'rank.types.deck_ratings'
        ],
        [
            'short_name' => 'DC',
            'name' => 'Deck Cadete',
            'type' => 'rank.types.deck_ratings'
        ],
        [
            'short_name' => '4AE',
            'name' => '4th Engineer',
            'type' => 'rank.types.jr_off_engine'
        ],
        [
            'short_name' => 'OLR',
            'name' => 'Oiler',
            'type' => 'rank.types.engine_ratings'
        ],
        [
            'short_name' => 'EC',
            'name' => 'Engine Cadete',
            'type' => 'rank.types.engine_ratings'
        ],
        [
            'short_name' => 'C/CK',
            'name' => 'Chief Cook',
            'type' => 'rank.types.galley_crew'
        ],
        [
            'short_name' => 'MSM',
            'name' => 'Messman',
            'type' => 'rank.types.galley_crew'
        ],
        [
            'short_name' => 'WPR',
            'name' => 'Wiper',
            'type' => 'rank.types.engine_ratings'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ranks = [];

        $timestamp = Carbon::now();

        foreach ($this->ranks as $rank) {
            $rankTypeId = RankType::where('name', config($rank['type']))->first()->id;
            $ranks[] = [
                'short_name' => $rank['short_name'],
                'name' => $rank['name'],
                'rank_type_id' => $rankTypeId,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        Rank::insert($ranks);
    }
}
