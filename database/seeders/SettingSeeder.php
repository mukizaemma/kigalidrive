<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1; 

        if (DB::table('settings')->exists()) {
            return;
        }

        DB::table('settings')->insert([
            'title' => 'Kigali Drive Rentals',
            'company' => 'Kigali Drive Rentals',
            'address' => 'Rubavu District',
            'phone' => '+250 788 316 330',
            'email' => 'kigalidrive rentals@gmail.com',
            'facebook' => 'https://facebook.com',
            'instagram' => 'https://instagram.com',
            'twitter' => 'https://twitter.com',
            'youtube' => 'https://youtube.com',
            'linkedin' => 'https://linkedin.com',
            'linktree' => 'https://linktree.com',
            'donate' => 'Please donate to support us.',
            'logo' => 'path/to/default/logo.png',
            'user_id' => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
