<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FavoritePosterVote;
use App\Models\BpkhForm;
use App\Models\ProdusenForm;

class FavoritePosterVoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        FavoritePosterVote::truncate();

        // Seed from BPKH Forms
        $bpkhForms = BpkhForm::all();
        foreach ($bpkhForms as $form) {
            FavoritePosterVote::create([
                'respondent_id' => $form->respondent_id,
                'participant_name' => $form->nama_bpkh,
                'participant_type' => 'bpkh',
                'petugas' => $form->petugas_bpkh,
                'vote_count' => 0,
                'notes' => null,
            ]);
        }

        // Seed from Produsen Forms
        $produsenForms = ProdusenForm::all();
        foreach ($produsenForms as $form) {
            FavoritePosterVote::create([
                'respondent_id' => $form->respondent_id,
                'participant_name' => $form->nama_instansi,
                'participant_type' => 'produsen',
                'petugas' => $form->nama_petugas,
                'vote_count' => 0,
                'notes' => null,
            ]);
        }

        $this->command->info('Favorite poster votes seeded successfully!');
        $this->command->info('BPKH: ' . $bpkhForms->count() . ' records');
        $this->command->info('Produsen: ' . $produsenForms->count() . ' records');
    }
}
