<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BpkhListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bpkhList = [
            ['nama_wilayah' => 'BPKH Wilayah I Medan', 'kode_wilayah' => 'I'],
            ['nama_wilayah' => 'BPKH Wilayah II Palembang', 'kode_wilayah' => 'II'],
            ['nama_wilayah' => 'BPKH Wilayah III Pontianak', 'kode_wilayah' => 'III'],
            ['nama_wilayah' => 'BPKH Wilayah IV Samarinda', 'kode_wilayah' => 'IV'],
            ['nama_wilayah' => 'BPKH Wilayah V Banjarbaru', 'kode_wilayah' => 'V'],
            ['nama_wilayah' => 'BPKH Wilayah VI Manado', 'kode_wilayah' => 'VI'],
            ['nama_wilayah' => 'BPKH Wilayah VII Makassar', 'kode_wilayah' => 'VII'],
            ['nama_wilayah' => 'BPKH Wilayah VIII Denpasar', 'kode_wilayah' => 'VIII'],
            ['nama_wilayah' => 'BPKH Wilayah IX Ambon', 'kode_wilayah' => 'IX'],
            ['nama_wilayah' => 'BPKH Wilayah X Jayapura', 'kode_wilayah' => 'X'],
            ['nama_wilayah' => 'BPKH Wilayah XI Yogyakarta', 'kode_wilayah' => 'XI'],
            ['nama_wilayah' => 'BPKH Wilayah XII Tanjungpinang', 'kode_wilayah' => 'XII'],
            ['nama_wilayah' => 'BPKH Wilayah XIII Pangkalpinang', 'kode_wilayah' => 'XIII'],
            ['nama_wilayah' => 'BPKH Wilayah XIV Kupang', 'kode_wilayah' => 'XIV'],
            ['nama_wilayah' => 'BPKH Wilayah XV Gorontalo', 'kode_wilayah' => 'XV'],
            ['nama_wilayah' => 'BPKH Wilayah XVI Palu', 'kode_wilayah' => 'XVI'],
            ['nama_wilayah' => 'BPKH Wilayah XVII Manokwari', 'kode_wilayah' => 'XVII'],
            ['nama_wilayah' => 'BPKH Wilayah XVIII Banda Aceh', 'kode_wilayah' => 'XVIII'],
            ['nama_wilayah' => 'BPKH Wilayah XIX Pekanbaru', 'kode_wilayah' => 'XIX'],
            ['nama_wilayah' => 'BPKH Wilayah XX Bandar Lampung', 'kode_wilayah' => 'XX'],
            ['nama_wilayah' => 'BPKH Wilayah XXI Palangkaraya', 'kode_wilayah' => 'XXI'],
            ['nama_wilayah' => 'BPKH Wilayah XXII Kendari', 'kode_wilayah' => 'XXII'],
        ];

        foreach ($bpkhList as $bpkh) {
            DB::table('bpkh_list')->insert([
                'nama_wilayah' => $bpkh['nama_wilayah'],
                'kode_wilayah' => $bpkh['kode_wilayah'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
