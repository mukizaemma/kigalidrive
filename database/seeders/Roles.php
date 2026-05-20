<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            ['name' => 'Employee'],
            ['name' => 'Employer'],
        ];

        foreach ($skills as $skill) {
            DB::table('roles')->insert($skill);
        }
    }
}
