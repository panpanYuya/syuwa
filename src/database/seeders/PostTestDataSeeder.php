<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 単体テスト用Seeder
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            [
                'id' => 9999992,
                'user_id' => 9999997,
                'text' => 'テストデータその1になります',
                'created_at' => date('2022-12-16 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9999993,
                'user_id' => 9999998,
                'text' => 'テストデータその2になります',
                'created_at' => date('2022-12-17 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9999994,
                'user_id' => 9999999,
                'text' => 'テストデータその3になります',
                'created_at' => date('2022-12-18 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9999995,
                'user_id' => 9999997,
                'text' => 'テストデータその4になります',
                'created_at' => date('2022-12-19 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9999996,
                'user_id' => 9999998,
                'text' => 'テストデータその5になります',
                'created_at' => date('2022-12-20 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9999997,
                'user_id' => 9999999,
                'text' => 'テストデータその6になります',
                'created_at' => date('2022-12-21 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9999998,
                'user_id' => 9999997,
                'text' => 'テストデータその7になります',
                'created_at' => date('2022-12-22 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9999999,
                'user_id' => 9999998,
                'text' => 'テストデータその8になります',
                'created_at' => date('2022-12-23 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

        ]);
    }
}
