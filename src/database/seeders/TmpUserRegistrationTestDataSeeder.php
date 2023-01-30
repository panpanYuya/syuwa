<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Datetime;

class TmpUserRegistrationTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('tmp_user_registrations')->insert([
            [
                'user_id' => 1000000,
                'user_name' => '新しいテストユーザー',
                'email' => "newTestTmpUser@gmail.com",
                'password' => Hash::make("password"),
                'birthday' => date('1999-12-1'),
                'token' => 'successusertests',
                'created_at' => new Datetime(),
                'updated_at' => new Datetime()
            ],
            [
                'user_id' => null,
                'user_name' => Str::random(10),
                'email' => "testtest@gmail.com",
                'password' => Hash::make("password"),
                'birthday' => date('1999-12-1'),
                'token' => Str::random(16),
                'created_at' => new Datetime(),
                'updated_at' => new Datetime()
            ],
            [
                'user_id' => null,
                'user_name' => 'testNewUser',
                'email' => "newtestUser@gmail.com",
                'password' => Hash::make("password"),
                'birthday' => date('1999-12-1'),
                'token' => 'passwordsuccessu',
                'created_at' => new Datetime(),
                'updated_at' => new Datetime()
            ],
            [
                'user_id' => null,
                'user_name' => 'testExpieredUser',
                'email' => "testExpieredUser@gmail.com",
                'password' => Hash::make("password"),
                'birthday' => date('1999-12-1'),
                'token' => 'passwordfaildata',
                'created_at' => '2021/11/19 16:52:15',
                'updated_at' => '2021/11/19 16:52:15'
            ]
        ]);
    }
}
