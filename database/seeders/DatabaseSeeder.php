<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        
        User::create([
            'username' => 'superadmin',
            'userpassword' => Hash::make('superadmin'),
            'useractive' => '1',
            'userfullname' => 'superadmin',
            'usercreatedat' => now()->toDateTimeString(),
            'usercreatedby' => '1'
        ]);

    }
}
