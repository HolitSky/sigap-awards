<?php

namespace Database\Seeders;

use App\Models\MenuChoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example: Menu dengan Main Menu Modal + Sub-menu (Aktif)
        MenuChoice::create([
            'main_menu_title' => 'Menu SIGAP Award 2025',
            'use_main_menu' => true,
            'menu_items' => [
                [
                    'title' => 'Upload Poster SIGAP Award 2025',
                    'type' => 'modal',
                    'link' => null,
                    'icon' => '🖼️',
                    'submenu' => [
                        [
                            'title' => 'Upload Poster BPKH',
                            'link' => 'https://form.sigap-award.site/upload-poster-bpkh'
                        ],
                        [
                            'title' => 'Upload Poster Produsen',
                            'link' => 'https://form.sigap-award.site/upload-poster-produsen'
                        ]
                    ]
                ],
                [
                    'title' => 'Kriteria Poster SIGAP Award 2025',
                    'type' => 'link',
                    'link' => '/poster-criteria',
                    'icon' => '📋'
                ],
                [
                    'title' => 'Rekapan Presentasi Peserta Sigap Award 2025',
                    'type' => 'link',
                    'link' => '/result-presentation',
                    'icon' => '📑'
                ],
                [
                    'title' => 'Lihat CV Juri SIGAP Award 2025',
                    'type' => 'link',
                    'link' => '/cv-juri',
                    'icon' => '👨‍⚖️'
                ],
                [
                    'title' => 'Pengumuman: List Peserta Tahap Presentasi',
                    'type' => 'link',
                    'link' => '/announcement',
                    'icon' => '📢'
                ],
            ],
            'is_active' => true,
        ]);

        // Example: Menu Langsung Tampil dengan Sub-menu (Tidak aktif)
        MenuChoice::create([
            'main_menu_title' => null,
            'use_main_menu' => false,
            'menu_items' => [

                [
                    'title' => 'Upload Poster SIGAP Award 2025',
                    'type' => 'modal',
                    'link' => null,
                    'icon' => '📝',
                    'submenu' => [
                        [
                            'title' => 'Upload Poster BPKH',
                            'link' => 'https://form.sigap-award.site/bpkh'
                        ],
                        [
                            'title' => 'Upload Poster Produsen',
                            'link' => 'https://form.sigap-award.site/produsen'
                        ]
                    ]
                ],
                 [
                    'title' => 'Kriteria Poster SIGAP Award 2025',
                    'type' => 'link',
                    'link' => '/poster-criteria',
                    'icon' => '📋'
                ],
                [
                    'title' => 'Rekapan Presentasi Peserta Sigap Award 2025',
                    'type' => 'link',
                    'link' => '/result-presentation',
                    'icon' => '📑'
                ],

            ],
            'is_active' => false,
        ]);
    }
}
