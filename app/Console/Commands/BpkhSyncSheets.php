<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BpkhSheetSyncService;

class BpkhSyncSheets extends Command
{
    protected $signature = 'bpkh:sync-sheets';

    protected $description = 'Sync BPKH forms from Google Sheets into local DB';

    public function handle(BpkhSheetSyncService $svc)
    {
        $count = $svc->sync();
        $this->info("Synced {$count} rows.");
        return self::SUCCESS;
    }
}


