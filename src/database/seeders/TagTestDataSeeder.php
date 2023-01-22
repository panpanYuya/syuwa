<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 単体テスト用Seeder
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            [
                'id' => 9999998,
                'tag_name' => 'タグテスト1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9999999,
                'tag_name' => 'タグテスト2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
