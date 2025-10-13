<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProdusenSheetSyncService;

class ProdusenSyncSheets extends Command
{
    protected $signature = 'produsen:sync-sheets';

    protected $description = 'Sync Produsen forms from Google Sheets into local DB';

    public function handle(ProdusenSheetSyncService $svc)
    {
        $count = $svc->sync();
        $this->info("Synced {$count} rows.");
        return self::SUCCESS;
    }
}


