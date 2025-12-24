<?php

namespace Rappasoft\LaravelLivewireTables\Commands;

use Illuminate\Console\Command;
use Rappasoft\LaravelLivewireTables\Models\TablePersistState;

class CleanupExpiredPersistStates extends Command
{
    protected $signature = 'livewire-tables:cleanup-expired-states';

    protected $description = 'Cleanup expired table persist states from database';

    public function handle()
    {
        $this->info('Cleaning up expired persist states...');

        $deletedCount = TablePersistState::cleanupExpired();

        $this->info("Successfully deleted {$deletedCount} expired persist state(s).");

        return 0;
    }
}
