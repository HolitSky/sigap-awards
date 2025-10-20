<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BpkhForm;
use App\Models\ProdusenForm;
use App\Models\BpkhExhibition;
use App\Models\ProdusenExhibition;
use App\Models\BpkhPresentationAssesment;
use App\Models\ProdusenPresentationAssesment;

class SyncNominasiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:nominasi {mode=presentation : Mode sync (all/presentation)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data nominasi (nominasi=true) dari Forms. Mode: "all" untuk Exhibition+Presentation, "presentation" untuk Presentation saja';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mode = $this->argument('mode');
        
        $this->info("Starting sync for nominasi data (mode: {$mode})...");
        
        if ($mode === 'all') {
            $this->info('Mode: Sync to Exhibition AND Presentation');
        } else {
            $this->info('Mode: Sync to Presentation ONLY (Exhibition tidak terpengaruh)');
        }
        
        // Sync BPKH nominasi data
        $this->syncBpkhNominasi($mode);
        
        // Sync Produsen nominasi data
        $this->syncProdusenNominasi($mode);
        
        $this->info('Sync completed successfully!');
        return 0;
    }
    
    private function syncBpkhNominasi($mode = 'presentation')
    {
        $this->info('Syncing BPKH nominasi data...');
        
        // Get all BPKH forms with nominasi = true
        $nomineeForms = BpkhForm::where('nominasi', true)->get();
        
        $exhibitionSynced = 0;
        $presentationSynced = 0;
        
        foreach ($nomineeForms as $form) {
            // Sync to Exhibition only if mode = 'all'
            if ($mode === 'all' && $form->nilai_bobot_total !== null) {
                BpkhExhibition::updateOrCreate(
                    ['respondent_id' => $form->respondent_id],
                    [
                        'nama_bpkh' => $form->nama_bpkh,
                        'petugas_bpkh' => $form->petugas_bpkh,
                        'bobot_exhibition' => 20, // default bobot
                        'status' => 'active',
                    ]
                );
                $exhibitionSynced++;
            }
            
            // Sync to Presentation (always, regardless of mode)
            if ($form->total_score !== null && $form->total_score !== '') {
                BpkhPresentationAssesment::updateOrCreate(
                    ['respondent_id' => $form->respondent_id],
                    [
                        'nama_bpkh' => $form->nama_bpkh,
                        'petugas_bpkh' => $form->petugas_bpkh,
                        'bobot_presentasi' => 35, // default bobot
                        'synced_from_form_id' => $form->id,
                        'synced_at' => now(),
                    ]
                );
                $presentationSynced++;
            }
        }
        
        $this->info("BPKH: {$nomineeForms->count()} nominasi found");
        if ($mode === 'all') {
            $this->info("  - {$exhibitionSynced} synced to Exhibition");
        }
        $this->info("  - {$presentationSynced} synced to Presentation");
    }
    
    private function syncProdusenNominasi($mode = 'presentation')
    {
        $this->info('Syncing Produsen nominasi data...');
        
        // Get all Produsen forms with nominasi = true
        $nomineeForms = ProdusenForm::where('nominasi', true)->get();
        
        $exhibitionSynced = 0;
        $presentationSynced = 0;
        
        foreach ($nomineeForms as $form) {
            // Sync to Exhibition only if mode = 'all'
            if ($mode === 'all' && $form->nilai_bobot_total !== null) {
                ProdusenExhibition::updateOrCreate(
                    ['respondent_id' => $form->respondent_id],
                    [
                        'nama_instansi' => $form->nama_instansi,
                        'nama_petugas' => $form->nama_petugas,
                        'bobot_exhibition' => 20, // default bobot
                        'status' => 'active',
                    ]
                );
                $exhibitionSynced++;
            }
            
            // Sync to Presentation (always, regardless of mode)
            if ($form->total_score !== null && $form->total_score !== '') {
                ProdusenPresentationAssesment::updateOrCreate(
                    ['respondent_id' => $form->respondent_id],
                    [
                        'nama_instansi' => $form->nama_instansi,
                        'nama_petugas' => $form->nama_petugas,
                        'bobot_presentasi' => 35, // default bobot
                        'synced_from_form_id' => $form->id,
                        'synced_at' => now(),
                    ]
                );
                $presentationSynced++;
            }
        }
        
        $this->info("Produsen: {$nomineeForms->count()} nominasi found");
        if ($mode === 'all') {
            $this->info("  - {$exhibitionSynced} synced to Exhibition");
        }
        $this->info("  - {$presentationSynced} synced to Presentation");
    }
}
