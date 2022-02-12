<?php

use App\Models\User;
use App\Models\UserStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // retrieve user status
        $status = UserStatus::where('name', config('user.statuses.active'))->first();

        // create the system admin
        $this->_createSystemAdmin();

        if (config('app.env') === 'local') {
            factory(User::class, 20)->create([
                'user_status_id' => $status->id
            ]);
        }
    }

    private function _createSystemAdmin()
    {
        // retrieve user status
        $status = UserStatus::where('name', config('user.statuses.active'))->first();

        // create the system admin
        User::create([
            'first_name' => 'ASTRO',
            'last_name' => 'Administrator',
            'email' => 'admin@astro.ph',
            'password' => Hash::make('P@ssw0rd'),
            'user_status_id' => $status->id,
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
