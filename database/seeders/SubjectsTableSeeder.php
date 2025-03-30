<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 国語、数学、英語を追加
        // 追記
        DB::table('subjects')->insert([
            ['subject' => '国語', 'created_at' => now(), 'updated_at' => now()],
            ['subject' => '数学', 'created_at' => now(), 'updated_at' => now()],
            ['subject' => '英語', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // main_categories に「教科」を追加
        $mainCategoryId = DB::table('main_categories')->insertGetId([
            'main_category' => '教科',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // sub_categories に「国語・数学・英語」を追加
        DB::table('sub_categories')->insert([
            ['main_category_id' => $mainCategoryId, 'sub_category' => '国語', 'created_at' => now(), 'updated_at' => now()],
            ['main_category_id' => $mainCategoryId, 'sub_category' => '数学', 'created_at' => now(), 'updated_at' => now()],
            ['main_category_id' => $mainCategoryId, 'sub_category' => '英語', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
