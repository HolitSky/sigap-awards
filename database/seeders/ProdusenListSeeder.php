<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdusenListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produsenList = [
            'Pusat Pengembangan Hutan Berkelanjutan',
            'Direktorat RPKHPWPH',
            'Direktorat Pengukuhan KH',
            'Direktorat Penggunaan KH',
            'Direktorat Perencanaan Konservasi',
            'Direktorat Konservasi Kawasan',
            'Direktorat Konservasi Spesies dan Genetik',
            'Direktorat Pemulihan Ekosistem dan Bina Areal Preservasi',
            'Direktorat Pemanfaatan Jasa Lingkungan',
            'Direktorat PEPDAS',
            'Direktorat Teknik Konservasi Tanah dan Reklamasi Hutan',
            'Direktorat Penghijauan dan Perbenihan Tanaman Hutan',
            'Direktorat RH',
            'Direktorat Rehabilitasi Mangrove',
            'Direktorat BRPH',
            'Direktorat BUPH',
            'Direktorat PUPH',
            'Direktorat BPPHH',
            'Direktorat PKPS',
            'Direktorat PKTHA',
            'Direktorat PPSA dan Keperdataan Kehutanan',
            'Direktorat Pengendalian Kebakaran Hutan',
        ];

        foreach ($produsenList as $produsen) {
            DB::table('produsen_list')->insert([
                'nama_unit' => $produsen,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
