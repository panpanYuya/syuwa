<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 単体テスト用Seeder
     *
     * @return void
     */
    public function run()
    {
        DB::table('images')->insert([
            [
                'id' => 9999992,
                'post_id' => 9999992,
                'img_url' => 'http://localhost:9900/syuwa-post-img/testFile.png',
                'created_at' => date('2022-12-16 21:00'),
                'updated_at' => date('2022-12-16 21:00'),
            ],
            [
                'id' => 9999993,
                'post_id' => 9999993,
                'img_url' => 'http://localhost:9900/syuwa-post-img/testFile.png',
                'created_at' => date('2022-12-17 21:00'),
                'updated_at' => date('2022-12-17 21:00'),
            ],
            [
                'id' => 9999994,
                'post_id' => 9999994,
                'img_url' => 'http://localhost:9900/syuwa-post-img/testFile.png',
                'created_at' => date('2022-12-18 21:00'),
                'updated_at' => date('2022-12-18 21:00'),
            ],
            [
                'id' => 9999995,
                'post_id' => 9999995,
                'img_url' => 'http://localhost:9900/syuwa-post-img/testFile.png',
                'created_at' => date('2022-12-19 21:00'),
                'updated_at' => date('2022-12-19 21:00'),
            ],
            [
                'id' => 9999996,
                'post_id' => 9999996,
                'img_url' => 'http://localhost:9900/syuwa-post-img/testFile.png',
                'created_at' => date('2022-12-20 21:00'),
                'updated_at' => date('2022-12-20 21:00'),
            ],
            [
                'id' => 9999997,
                'post_id' => 9999997,
                'img_url' => 'http://localhost:9900/syuwa-post-img/testFile.png',
                'created_at' => date('2022-12-21 21:00'),
                'updated_at' => date('2022-12-21 21:00'),
            ],
            [
                'id' => 9999998,
                'post_id' => 9999998,
                'img_url' => 'http://localhost:9900/syuwa-post-img/testFile.png',
                'created_at' => date('2022-12-22 21:00'),
                'updated_at' => date('2022-12-22 21:00'),
            ],
            [
                'id' => 9999999,
                'post_id' => 9999999,
                'img_url' => 'http://localhost:9900/syuwa-post-img/testFile.png',
                'created_at' => date('2022-12-23 21:00'),
                'updated_at' => date('2022-12-23 21:00'),
            ],
        ]);
    }
}
