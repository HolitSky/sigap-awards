<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModalInfo;
use Carbon\Carbon;

class ModalInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama jika ada
        ModalInfo::truncate();

        // Modal 1: Welcome Modal (muncul pertama, setelah 3 detik)
        ModalInfo::create([
            'modal_type' => 'welcome',
            'title' => 'Selamat Datang di SIGAP Award 2025',
            'subtitle' => null, // Not used for welcome modal
            'intro_text' => 'Yuk, unggah poster Anda sekarang! Terima kasih atas partisipasi Anda di SIGAP Awards 2025! 🙏🏻',
            'footer_text' => 'Silahkan melakukan voting untuk memilih Pengelola IGT terbaik tahun 2025! 🗳️✨',
            'show_form_options' => false,
            'meta_links' => [
                [
                    'title' => 'Produsen Data Geospasial',
                    'subtitle' => 'Untuk perusahaan/organisasi produsen data geospasial',
                    'icon' => '📊',
                    'link_url' => 'javascript:void(0)',
                    'bg_color' => 'rgba(40, 167, 69, 0.1)',
                    'is_active' => true
                ],
                [
                    'title' => 'Balai Pemantapan Kawasan Hutan (BPKH)',
                    'subtitle' => 'Untuk instansi BPKH yang ingin berpartisipasi',
                    'icon' => '🏢',
                    'link_url' => 'javascript:void(0)',
                    'bg_color' => 'rgba(102, 126, 234, 0.1)',
                    'is_active' => true
                ],
                [
                    'title' => 'Voting Pengelola IGT 2025',
                    'subtitle' => 'Menuju halaman voting 2025',
                    'icon' => '🗳️',
                    'link_url' => 'https://form.sigap-award.site/voting2025',
                    'bg_color' => 'rgba(234, 84, 85, 0.08)',
                    'is_active' => true
                ]
            ],
            'is_show' => true
        ]);

        // Modal 2: Reminder Modal (muncul kedua, setelah 6 detik)
        ModalInfo::create([
            'modal_type' => 'reminder',
            'title' => 'Hai #SobatGeoSPESIAL !',
            'subtitle' => 'Saatnya unggah poster Anda! Tetap pantau pengumuman resmi dari kami!😁🙏🏻',
            'intro_text' => null,
            'footer_text' => null,
            'show_form_options' => false,
            'meta_links' => null, // Not used for reminder modal
            'is_show' => true
        ]);
    }
}
