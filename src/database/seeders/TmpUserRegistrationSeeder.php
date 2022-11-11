<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Datetime;

class TmpUserRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tmp_user_registrations')->insert([
            'user_name' => Str::random(10),
            'email' => "testtest@test.com",
            'password' => Hash::make("password"),
            'birthday' => date('1999-12-1'),
            'token' => Str::random(16),
            'created_at' => new Datetime(),
            'updated_at' => new Datetime()
        ]);
    }
}
