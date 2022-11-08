<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class TmpUserRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //TODO コメントを追加する
        DB::table('tmp_user_registrations')->insert([
            'user_name' => Str::random(10),
            'email' => "testtest@test.com",
            'password' => Hash::make("password"),
            'birthday' =>date('1999-12-1'),
        ]);
    }
}
