<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert(['name' => 'アクション']);
        DB::table('genres')->insert(['name' => 'アドベンチャー']);
        DB::table('genres')->insert(['name' => 'コメディ']);
        DB::table('genres')->insert(['name' => 'ドラマ']);
        DB::table('genres')->insert(['name' => 'アニメーション']);
        DB::table('genres')->insert(['name' => 'サスペンス']);
        DB::table('genres')->insert(['name' => 'ホラー']);
        DB::table('genres')->insert(['name' => 'SF']);
        DB::table('genres')->insert(['name' => '恋愛']);
        DB::table('genres')->insert(['name' => 'ミステリー']);
        DB::table('genres')->insert(['name' => 'ミュージカル']);
        DB::table('genres')->insert(['name' => 'ファミリー']);
        DB::table('genres')->insert(['name' => 'クライム']);
        DB::table('genres')->insert(['name' => 'ファンタジー']);
        DB::table('genres')->insert(['name' => 'その他']);
    }
}
