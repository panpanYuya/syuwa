<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO seederの説明を記述する
        DB::table('users')->insert([
            'user_name' => Str::random(10),
            'email' => "test@test.com",
            'password' => Hash::make("password"),
        ]);
    }
}
