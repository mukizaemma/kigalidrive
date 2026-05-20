<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Seed the super admin account (pre-verified, no email confirmation required).
     */
    public function run(): void
    {
        $email = 'admin@iremetech.com';

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'user_id' => User::where('email', $email)->value('user_id') ?? (string) Str::uuid(),
                'role' => 1,
                'status' => 'Active',
                'password' => Hash::make('Ireme@2021'),
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info("Super admin seeded at {$email} (no email sent).");
    }
}
