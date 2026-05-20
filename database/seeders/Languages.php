<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Languages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['name' => 'English'],
            ['name' => 'Kinyarwanda'],
            ['name' => 'Kiswahili'],
            ['name' => 'Chinese'],
            ['name' => 'Hindi'],
            ['name' => 'Spanish'],
            ['name' => 'French'],
            ['name' => 'Arabic'],
            ['name' => 'Bengali'],
            ['name' => 'Portuguese'],
            ['name' => 'Russian'],
            ['name' => 'Urdu'],
        ];

        foreach ($languages as $language) {
            DB::table('languages')->insert($language);
        }
    }
}
