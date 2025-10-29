<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaunchDate;
use Carbon\Carbon;

class LaunchDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        LaunchDate::truncate();

        // Create default launch date (range date) - URUTAN 1 (order=0)
        LaunchDate::create([
            'title' => 'Tahap Presentasi',
            'is_range_date' => true,
            'single_date' => null,
            'start_date' => Carbon::create(2025, 10, 23),
            'end_date' => Carbon::create(2025, 10, 24),
            'is_active' => true,
            'order' => 0  // URUTAN 1 - Ini yang akan tampil di landing
        ]);

        // Create another example (single date) - URUTAN 2 (order=1)
        LaunchDate::create([
            'title' => 'Penganugerahan Sigap Award',
            'is_range_date' => false,
            'single_date' => Carbon::create(2025, 10, 1),
            'start_date' => null,
            'end_date' => null,
            'is_active' => false,
            'order' => 1  // URUTAN 2 - Tidak aktif
        ]);
    }
}
