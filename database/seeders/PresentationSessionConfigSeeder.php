<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PresentationSessionConfig;

class PresentationSessionConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        PresentationSessionConfig::truncate();
        
        // Default session configuration
        $sessions = [
            // BPKH Sessions (odd numbers: 1, 3, 5)
            ['session_name' => 'Sesi 1', 'session_number' => 1, 'session_type' => 'bpkh', 'order' => 1],
            ['session_name' => 'Sesi 3', 'session_number' => 3, 'session_type' => 'bpkh', 'order' => 2],
            ['session_name' => 'Sesi 5', 'session_number' => 5, 'session_type' => 'bpkh', 'order' => 3],
            
            // Produsen Sessions (even numbers: 2, 4)
            ['session_name' => 'Sesi 2', 'session_number' => 2, 'session_type' => 'produsen', 'order' => 1],
            ['session_name' => 'Sesi 4', 'session_number' => 4, 'session_type' => 'produsen', 'order' => 2],
        ];
        
        foreach ($sessions as $session) {
            PresentationSessionConfig::create($session);
            $this->command->info("✓ Created: {$session['session_name']} ({$session['session_type']})");
        }
        
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('Session Configuration Seeding Completed!');
        $this->command->info('========================================');
        $this->command->info('BPKH Sessions: ' . PresentationSessionConfig::where('session_type', 'bpkh')->count());
        $this->command->info('Produsen Sessions: ' . PresentationSessionConfig::where('session_type', 'produsen')->count());
    }
}
