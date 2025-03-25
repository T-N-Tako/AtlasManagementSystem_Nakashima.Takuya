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
            ['subject_name' => '国語', 'created_at' => now(), 'updated_at' => now()],
            ['subject_name' => '数学', 'created_at' => now(), 'updated_at' => now()],
            ['subject_name' => '英語', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
