<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProdusenForm;
use App\Models\ProdusenPresentationAssesment;

class SyncProdusenPresentationAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:produsen-presentation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Produsen forms with status scored to presentation assessment table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Produsen Presentation Assessment sync...');
        
        // Get all Produsen forms with status 'scored'
        $scoredForms = ProdusenForm::where('status_nilai', 'scored')->get();
        
        $this->info("Found {$scoredForms->count()} scored Produsen forms");
        
        $synced = 0;
        $updated = 0;
        
        foreach ($scoredForms as $form) {
            // Check if already exists
            $assessment = ProdusenPresentationAssesment::where('respondent_id', $form->respondent_id)->first();
            
            if ($assessment) {
                // Update existing
                $assessment->nama_instansi = $form->nama_instansi;
                $assessment->nama_petugas = $form->nama_petugas;
                $assessment->synced_from_form_id = $form->id;
                $assessment->synced_at = now();
                $assessment->save();
                
                $updated++;
                $this->line("Updated: {$form->nama_instansi}");
            } else {
                // Create new
                ProdusenPresentationAssesment::create([
                    'respondent_id' => $form->respondent_id,
                    'nama_instansi' => $form->nama_instansi,
                    'nama_petugas' => $form->nama_petugas,
                    'bobot_presentasi' => 35, // Default bobot
                    'synced_from_form_id' => $form->id,
                    'synced_at' => now(),
                ]);
                
                $synced++;
                $this->line("Created: {$form->nama_instansi}");
            }
        }
        
        $this->info("Sync completed!");
        $this->info("New records: {$synced}");
        $this->info("Updated records: {$updated}");
        
        return Command::SUCCESS;
    }
}
