<?php

use App\Models\Vessel;
use App\Models\VesselOwner;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VesselsTableSeeder extends Seeder
{
    /** @var array */
    protected $vessels = [
        [
            'code_name' => 'GLZ',
            'name' => 'GL LA PAZ',
            'owner' => 'LEALTY MARINE CORP.'
        ],
        [
            'code_name' => 'GLU',
            'name' => 'GL IGUAZU',
            'owner' => 'LEALTY MARINE CORP.'
        ],
        [
            'code_name' => 'VSG',
            'name' => 'VALIANT SPRING',
            'owner' => 'Safargo Shipping Pte. Ltd'
        ],
        [
            'code_name' => 'VSR',
            'name' => 'VALIANT SUMMER',
            'owner' => 'Safargo Shipping Pte. Ltd'
        ],
        [
            'code_name' => 'VSP',
            'name' => 'VALIANT SPLENDOR',
            'owner' => 'Safargo Shipping Pte. Ltd'
        ],
        [
            'code_name' => 'VST',
            'name' => 'VALIANT SPIRIT',
            'owner' => 'Safargo Shipping Pte. Ltd'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vessels = [];

        $timestamp = Carbon::now();

        foreach ($this->vessels as $vessel) {
            /** @var VesselOwner $owner */
            $owner = VesselOwner::firstOrCreate(['name' => $vessel['owner']]);
            $vessels[] = [
                'vessel_owner_id' => $owner->getAttribute('id'),
                'code_name' => $vessel['code_name'],
                'name' => $vessel['name'],
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        Vessel::insert($vessels);
    }
}
