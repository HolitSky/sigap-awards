<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BpkhForm;
use App\Models\ProdusenForm;
use App\Models\BpkhExhibition;
use App\Models\ProdusenExhibition;

class SyncExhibitionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exhibition:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync exhibition data from forms (status_nilai = scored)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting exhibition data sync...');
        
        // Sync BPKH Exhibition
        $this->syncBpkhExhibition();
        
        // Sync Produsen Exhibition
        $this->syncProdusenExhibition();
        
        $this->info('Exhibition data sync completed successfully!');
    }
    
    protected function syncBpkhExhibition()
    {
        $this->info('Syncing BPKH Exhibition data...');
        
        $forms = BpkhForm::where('status_nilai', 'scored')->get();
        
        $synced = 0;
        $updated = 0;
        
        foreach ($forms as $form) {
            $exhibition = BpkhExhibition::updateOrCreate(
                ['respondent_id' => $form->respondent_id],
                [
                    'nama_bpkh' => $form->nama_bpkh,
                    'petugas_bpkh' => $form->petugas_bpkh,
                    'bobot_exhibition' => 20.00,
                    'status' => 'pending',
                ]
            );
            
            if ($exhibition->wasRecentlyCreated) {
                $synced++;
            } else {
                $updated++;
            }
        }
        
        $this->info("BPKH Exhibition: {$synced} new records, {$updated} updated.");
    }
    
    protected function syncProdusenExhibition()
    {
        $this->info('Syncing Produsen Exhibition data...');
        
        $forms = ProdusenForm::where('status_nilai', 'scored')->get();
        
        $synced = 0;
        $updated = 0;
        
        foreach ($forms as $form) {
            $exhibition = ProdusenExhibition::updateOrCreate(
                ['respondent_id' => $form->respondent_id],
                [
                    'nama_instansi' => $form->nama_instansi,
                    'nama_petugas' => $form->nama_petugas,
                    'bobot_exhibition' => 20.00,
                    'status' => 'pending',
                ]
            );
            
            if ($exhibition->wasRecentlyCreated) {
                $synced++;
            } else {
                $updated++;
            }
        }
        
        $this->info("Produsen Exhibition: {$synced} new records, {$updated} updated.");
    }
}
