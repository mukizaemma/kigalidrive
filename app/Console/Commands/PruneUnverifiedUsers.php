<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PruneUnverifiedUsers extends Command
{
    protected $signature = 'users:prune-unverified';

    protected $description = 'Remove accounts that never verified email within the configured TTL (admins excluded)';

    public function handle(): int
    {
        $hours = (int) config('registration.verification_ttl_hours', 24);
        $cutoff = now()->subHours($hours);

        $query = User::query()
            ->whereNull('email_verified_at')
            ->where('created_at', '<', $cutoff)
            ->whereNotIn('role', [1, 2])
            ->where('email', '!=', 'admin@iremetech.com');

        $count = $query->count();
        $query->delete();

        $this->info("Removed {$count} unverified user(s) older than {$hours} hour(s).");

        return self::SUCCESS;
    }
}
