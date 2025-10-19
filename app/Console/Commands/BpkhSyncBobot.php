<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BpkhForm;

class BpkhSyncBobot extends Command
{
    protected $signature = 'bpkh:sync-bobot';

    protected $description = 'Sync dan hitung ulang nilai_bobot_total untuk form BPKH yang sudah dinilai';

    public function handle()
    {
        $this->info('🔄 Memulai sinkronisasi nilai bobot untuk BPKH Forms...');
        $this->newLine();

        // Get forms yang nilai_bobot_total nya kosong tapi total_score ada
        $formsToUpdate = BpkhForm::whereNull('nilai_bobot_total')
            ->whereNotNull('total_score')
            ->get();

        // Get forms yang sudah ada nilai_bobot_total nya
        $formsAlreadyCalculated = BpkhForm::whereNotNull('nilai_bobot_total')
            ->whereNotNull('total_score')
            ->count();

        if ($formsToUpdate->isEmpty()) {
            $this->info('✅ Tidak ada data yang perlu diupdate.');
            $this->info("📊 Total form yang sudah memiliki nilai bobot: {$formsAlreadyCalculated}");
            return Command::SUCCESS;
        }

        $this->info("📋 Ditemukan {$formsToUpdate->count()} form yang perlu dihitung nilai bobotnya.");
        $this->newLine();

        $updated = 0;
        $failed = 0;

        $this->withProgressBar($formsToUpdate, function ($form) use (&$updated, &$failed) {
            try {
                // Calculate nilai_bobot_total
                $bobot = $form->bobot ?? 45;
                $nilaiBobot = ($form->total_score * $bobot) / 100;

                // Update tanpa trigger events dan tanpa create record
                $form->update([
                    'nilai_bobot_total' => $nilaiBobot
                ]);

                $updated++;
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("❌ Gagal update form {$form->respondent_id}: {$e->getMessage()}");
            }
        });

        $this->newLine(2);
        
        // Summary
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('📊 RINGKASAN SINKRONISASI');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info("✅ Berhasil dihitung: {$updated} form");
        if ($failed > 0) {
            $this->error("❌ Gagal: {$failed} form");
        }
        $this->info("📝 Sudah memiliki nilai bobot: {$formsAlreadyCalculated} form");
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->newLine();

        if ($updated > 0) {
            $this->info('🎉 Sinkronisasi nilai bobot BPKH selesai!');
        }

        return Command::SUCCESS;
    }
}
