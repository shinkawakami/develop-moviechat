<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('platforms')->insert(['name' => 'Netflix']);
        DB::table('platforms')->insert(['name' => 'Amazon Prime Video']);
        DB::table('platforms')->insert(['name' => 'U-NEXT']);
        DB::table('platforms')->insert(['name' => 'Hulu']);
        DB::table('platforms')->insert(['name' => 'Disney+']);
        DB::table('platforms')->insert(['name' => 'dTV']);
        DB::table('platforms')->insert(['name' => 'ABEMA']);
        DB::table('platforms')->insert(['name' => 'WOWOW']);
        DB::table('platforms')->insert(['name' => 'YouTube']);
        DB::table('platforms')->insert(['name' => 'TV']);
        DB::table('platforms')->insert(['name' => 'その他']);
    }
}
