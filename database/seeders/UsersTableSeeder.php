<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 追記
        DB::table('users')->insert([
            [
                'over_name' => '山田',
                'under_name' => '太郎',
                'over_name_kana' => 'ヤマダ',
                'under_name_kana' => 'タロウ',
                'mail_address' => 'user1@example.com', // ← 修正
                'sex' => 1, // 1: 男性, 2: 女性
                'birth_day' => '1990-01-01',
                'role' => 1, // 1: 管理者, 2: ユーザー
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'over_name' => '鈴木',
                'under_name' => '花子',
                'over_name_kana' => 'スズキ',
                'under_name_kana' => 'ハナコ',
                'mail_address' => 'user2@example.com', // ← 修正
                'sex' => 2,
                'birth_day' => '1995-03-15',
                'role' => 2,
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
