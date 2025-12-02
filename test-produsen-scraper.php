<?php

/**
 * Test script untuk melihat data Produsen DG dan meta data
 *
 * Cara pakai:
 * php test-produsen-scraper.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ProdusenForm;

echo "=== Produsen DG Forms Data Test ===\n\n";

// Count total
$total = ProdusenForm::count();
echo "Total Produsen Forms: {$total}\n\n";

// Get first 3 forms
$forms = ProdusenForm::limit(3)->get();

foreach ($forms as $form) {
    echo "─────────────────────────────────────────\n";
    echo "ID: {$form->id}\n";
    echo "Nama Instansi: {$form->nama_instansi}\n";
    echo "Petugas: {$form->nama_petugas}\n";
    echo "Status: {$form->status_nilai}\n";

    if ($form->meta && is_array($form->meta)) {
        echo "\nMeta Data:\n";

        // Parse meta data
        $entries = [];
        $isOrdered = isset($form->meta[0]) && is_array($form->meta[0]) &&
                     array_key_exists('key', $form->meta[0]) &&
                     array_key_exists('value', $form->meta[0]);

        if ($isOrdered) {
            foreach ($form->meta as $item) {
                $entries[] = [$item['key'], $item['value']];
            }
        } else {
            foreach ($form->meta as $k => $v) {
                $entries[] = [$k, $v];
            }
        }

        // Show only attachment fields
        $attachmentCount = 0;
        foreach ($entries as [$key, $value]) {
            $isAttachment = preg_match('/^\s*Lampiran/i', (string) $key);

            if ($isAttachment) {
                $attachmentCount++;
                echo "  📎 {$key}\n";

                // Extract URLs
                $urls = [];
                if (is_array($value)) {
                    foreach ($value as $vv) {
                        if (is_string($vv) && preg_match_all('/https?:\/\/\S+/i', $vv, $matches)) {
                            foreach ($matches[0] as $url) {
                                $urls[] = $url;
                            }
                        }
                    }
                } else {
                    $raw = (string) $value;
                    if (preg_match_all('/https?:\/\/\S+/i', $raw, $matches)) {
                        $urls = $matches[0];
                    }
                }

                foreach ($urls as $url) {
                    // Truncate URL for display
                    $displayUrl = strlen($url) > 80 ? substr($url, 0, 80) . '...' : $url;
                    echo "     → {$displayUrl}\n";
                }
            }
        }

        if ($attachmentCount === 0) {
            echo "  (Tidak ada lampiran)\n";
        } else {
            echo "  Total lampiran: {$attachmentCount}\n";
        }
    } else {
        echo "\nMeta Data: (kosong)\n";
    }

    echo "\n";
}

echo "─────────────────────────────────────────\n";
echo "\n✅ Test selesai!\n";
echo "\nUntuk scrape files, jalankan:\n";
echo "  php artisan produsen:scrape-files --all\n";
echo "  php artisan produsen:scrape-files --id=1\n";
