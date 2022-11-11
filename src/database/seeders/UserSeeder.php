<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Datetime;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'user_name' => Str::random(10),
            'email' => "test@test.com",
            'password' => Hash::make("password"),
            'created_at' => new Datetime(),
            'updated_at' => new Datetime()
        ]);
    }
}
