<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 単体テスト用Seeder
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 9999997,
                'user_name' => Str::random(10),
                'email' => "test@test.com",
                'password' => Hash::make("password"),
                'birthday' => '2002/10/10 19:24:40',
                'created_at' => new DateTime(),
                'updated_at' => new Datetime()
            ],
            [
                'id' => 9999998,
                'user_name' => Str::random(10),
                'email' => "test01@test01.com",
                'password' => Hash::make("password"),
                'birthday' => '2002/10/10 19:24:40',
                'created_at' => new Datetime(),
                'updated_at' => new Datetime()
            ],
            [
                'id' => 9999999,
                'user_name' => Str::random(10),
                'email' => "test02@test02.com",
                'password' => Hash::make("password"),
                'birthday' => '2002/10/10 19:24:40',
                'created_at' => new Datetime(),
                'updated_at' => new Datetime()
            ],
        ]);
    }
}