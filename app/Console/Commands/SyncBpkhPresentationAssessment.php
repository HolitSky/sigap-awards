<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BpkhForm;
use App\Models\BpkhPresentationAssesment;

class SyncBpkhPresentationAssessment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:bpkh-presentation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync BPKH forms with status scored to presentation assessment table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting BPKH Presentation Assessment sync...');
        
        // Get all BPKH forms with status 'scored'
        $scoredForms = BpkhForm::where('status_nilai', 'scored')->get();
        
        $this->info("Found {$scoredForms->count()} scored BPKH forms");
        
        $synced = 0;
        $updated = 0;
        
        foreach ($scoredForms as $form) {
            // Check if already exists
            $assessment = BpkhPresentationAssesment::where('respondent_id', $form->respondent_id)->first();
            
            if ($assessment) {
                // Update existing
                $assessment->nama_bpkh = $form->nama_bpkh;
                $assessment->petugas_bpkh = $form->petugas_bpkh;
                $assessment->synced_from_form_id = $form->id;
                $assessment->synced_at = now();
                $assessment->save();
                
                $updated++;
                $this->line("Updated: {$form->nama_bpkh}");
            } else {
                // Create new
                BpkhPresentationAssesment::create([
                    'respondent_id' => $form->respondent_id,
                    'nama_bpkh' => $form->nama_bpkh,
                    'petugas_bpkh' => $form->petugas_bpkh,
                    'bobot_presentasi' => 35, // Default bobot
                    'synced_from_form_id' => $form->id,
                    'synced_at' => now(),
                ]);
                
                $synced++;
                $this->line("Created: {$form->nama_bpkh}");
            }
        }
        
        $this->info("Sync completed!");
        $this->info("New records: {$synced}");
        $this->info("Updated records: {$updated}");
        
        return Command::SUCCESS;
    }
}
