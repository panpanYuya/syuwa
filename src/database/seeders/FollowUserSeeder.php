<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowUserSeeder extends Seeder
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
                'following_id' => 2,
                'followed_id' => 1,
            ],
            [
                'following_id' => 1,
                'followed_id' => 2,
            ],
        ]);
    }
}
