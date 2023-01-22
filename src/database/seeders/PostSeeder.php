<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            [
                'user_id' => 1,
                'text' => 'この日本酒は純米大吟醸のお酒です。',
                'created_at' => date('2022-12-16 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 1,
                'text' => 'このワインは赤です。',
                'created_at' => date('2022-12-17 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 2,
                'text' => 'このワインはカベルネソーヴィニヨンです。',
                'created_at' => date('2022-12-18 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 2,
                'text' => 'このワインはドイツ原産ブドウを使用しています。',
                'created_at' => date('2022-12-19 21:00'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

        ]);
    }
}
