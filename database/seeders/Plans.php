<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Plans extends Seeder
{

    public function run(): void
    {
        DB::table('plans')->insert([
            [
                'name' => 'Free',
                'price' => 0,
                'duration' => 30, // 30 days free
                'startDate' => Carbon::now(),
                'endDate' => Carbon::now()->addDays(30),
                'details' => 'Basic plan with limited features for testing the platform.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Standard',
                'price' => 50,
                'duration' => 60, // 60 days access
                'startDate' => Carbon::now(),
                'endDate' => Carbon::now()->addDays(60),
                'details' => 'Includes basic recruitment tools and moderate visibility for job postings.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Professional',
                'price' => 100,
                'duration' => 90, // 90 days access
                'startDate' => Carbon::now(),
                'endDate' => Carbon::now()->addDays(90),
                'details' => 'Enhanced features including advanced job matching and analytics.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Premium',
                'price' => 500,
                'duration' => 180, // 180 days access
                'startDate' => Carbon::now(),
                'endDate' => Carbon::now()->addDays(180),
                'details' => 'Comprehensive plan with top-tier recruitment features and priority support.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
