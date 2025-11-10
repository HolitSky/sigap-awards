<?php

namespace Database\Seeders;

use App\Models\CardBox;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardBoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example 1: Text Only (Aktif - akan ditampilkan)
        CardBox::create([
            'title' => 'Informasi Penting',
            'description' => 'Jangan lupa, pada 20 November akan dilaksanakan Exhibition dan Penganugerahan SIGAP Award 2025!',
            'content_type' => 'text_only',
            'button_text' => null,
            'link_url' => null,
            'modal_content' => null,
            'order' => 0,
            'is_active' => true,
        ]);

        // Example 2: Link URL (Tidak aktif)
        CardBox::create([
            'title' => 'Vote Pengelola IGT Terbaik 2025',
            'description' => 'Klik tombol di bawah untuk menuju halaman voting.',
            'content_type' => 'link',
            'button_text' => 'Lanjut',
            'link_url' => 'https://form.sigap-award.site/voting2025',
            'modal_content' => null,
            'order' => 1,
            'is_active' => false,
        ]);

        // Example 3: Modal (Tidak aktif)
        CardBox::create([
            'title' => 'Panduan Pendaftaran',
            'description' => 'Klik tombol untuk melihat panduan lengkap pendaftaran.',
            'content_type' => 'modal',
            'button_text' => 'Lihat Panduan',
            'link_url' => null,
            'modal_content' => "Panduan Pendaftaran:\n\n1. Siapkan dokumen persyaratan\n2. Isi formulir pendaftaran online\n3. Upload dokumen yang diperlukan\n4. Tunggu konfirmasi dari panitia\n\nUntuk informasi lebih lanjut, hubungi panitia.",
            'order' => 2,
            'is_active' => false,
        ]);
    }
}
