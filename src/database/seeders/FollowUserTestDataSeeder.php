<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowUserTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('follow_users')->insert([
            [
                'following_id' => 1,
                'followed_id' => 9999999,
            ],
        ]);
    }
}
