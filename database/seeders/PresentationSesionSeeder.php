<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BpkhPresentationSession;
use App\Models\ProdusenPresentationSession;
use App\Models\BpkhPresentationAssesment;
use App\Models\ProdusenPresentationAssesment;

class PresentationSesionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        BpkhPresentationSession::truncate();
        ProdusenPresentationSession::truncate();
        
        // BPKH Sessions Data
        $bpkhSessions = [
            'Sesi 1' => [
                'BPKH Wilayah III Pontianak',
                'BPKH Wilayah VII Makassar',
                'BPKH Wilayah XII Tanjung Pinang',
                'BPKH Wilayah XX Bandar Lampung'
            ],
            'Sesi 3' => [
                'BPKH Wilayah V Banjarbaru',
                'BPKH Wilayah IX Ambon',
                'BPKH Wilayah XVII Manokwari',
                'BPKH Wilayah XXI Palangkaraya'
            ],
            'Sesi 5' => [
                'BPKH Wilayah I Medan',
                'BPKH Wilayah VIII Denpasar',
                'BPKH Wilayah XI Yogyakarta',
                'BPKH Wilayah XVIII Banda Aceh'
            ]
        ];
        
        // Produsen Sessions Data
        $produsenSessions = [
            'Sesi 2' => [
                'Direktorat Penggunaan Kawasan Hutan',
                'Direktorat Perencanaan dan Evaluasi Pengelolaan Daerah Aliran Sungai',
                'Direktorat Pengendalian Kebakaran Hutan'
            ],
            'Sesi 4' => [
                'Direktorat Bina Usaha Pemanfaatan Hutan',
                'Direktorat Rehabilitasi Mangrove',
                'Direktorat Penyiapan Kawasan Perhutanan Sosial'
            ]
        ];
        
        // Seed BPKH Sessions
        foreach ($bpkhSessions as $sessionName => $participants) {
            $order = 1;
            foreach ($participants as $namaBpkh) {
                // Find the participant in assessment table
                $participant = BpkhPresentationAssesment::where('nama_bpkh', $namaBpkh)->first();
                
                if ($participant) {
                    BpkhPresentationSession::create([
                        'session_name' => $sessionName,
                        'respondent_id' => $participant->respondent_id,
                        'nama_bpkh' => $namaBpkh,
                        'order' => $order,
                        'is_active' => true
                    ]);
                    $order++;
                    $this->command->info("✓ Added to {$sessionName}: {$namaBpkh}");
                } else {
                    $this->command->warn("✗ BPKH not found in assessment table: {$namaBpkh}");
                }
            }
        }
        
        // Seed Produsen Sessions
        foreach ($produsenSessions as $sessionName => $participants) {
            $order = 1;
            foreach ($participants as $namaInstansi) {
                // Find the participant in assessment table
                $participant = ProdusenPresentationAssesment::where('nama_instansi', $namaInstansi)->first();
                
                if ($participant) {
                    ProdusenPresentationSession::create([
                        'session_name' => $sessionName,
                        'respondent_id' => $participant->respondent_id,
                        'nama_instansi' => $namaInstansi,
                        'order' => $order,
                        'is_active' => true
                    ]);
                    $order++;
                    $this->command->info("✓ Added to {$sessionName}: {$namaInstansi}");
                } else {
                    $this->command->warn("✗ Produsen not found in assessment table: {$namaInstansi}");
                }
            }
        }
        
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Presentation Session Seeding Completed!');
        $this->command->info('========================================');
        $this->command->info('BPKH Sessions: ' . BpkhPresentationSession::count() . ' participants');
        $this->command->info('Produsen Sessions: ' . ProdusenPresentationSession::count() . ' participants');
    }
}
