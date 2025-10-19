<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncAllBobot extends Command
{
    protected $signature = 'sync:all-bobot';

    protected $description = 'Sync nilai_bobot_total untuk semua form (BPKH & Produsen) yang sudah dinilai';

    public function handle()
    {
        $this->info('🚀 Memulai sinkronisasi nilai bobot untuk semua form...');
        $this->newLine();

        // Run BPKH sync
        $this->info('═══════════════════════════════════════════');
        $this->info('1️⃣  BPKH FORMS');
        $this->info('═══════════════════════════════════════════');
        $this->call('bpkh:sync-bobot');
        
        $this->newLine();
        
        // Run Produsen sync
        $this->info('═══════════════════════════════════════════');
        $this->info('2️⃣  PRODUSEN FORMS');
        $this->info('═══════════════════════════════════════════');
        $this->call('produsen:sync-bobot');

        $this->newLine();
        $this->info('✨ Sinkronisasi semua nilai bobot selesai!');

        return Command::SUCCESS;
    }
}
