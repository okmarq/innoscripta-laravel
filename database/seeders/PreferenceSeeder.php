<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('preferences')->insert([
            'name' => 'source'
        ]);
        DB::table('preferences')->insert([
            'name' => 'category'
        ]);
        DB::table('preferences')->insert([
            'name' => 'author'
        ]);
    }
}
