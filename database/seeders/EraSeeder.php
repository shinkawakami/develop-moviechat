<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('eras')->insert(['era' => '1980年代']);
        DB::table('eras')->insert(['era' => '1990年代']);
        DB::table('eras')->insert(['era' => '2000年代']);
        DB::table('eras')->insert(['era' => '2010年代']);
        DB::table('eras')->insert(['era' => '2020年代']);
        DB::table('eras')->insert(['era' => 'その他']);
    }
}
