<?php

/**
 * Script untuk cek struktur meta data BPKH
 * Untuk memahami pattern nomor soal
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BpkhForm;

echo "=== Checking BPKH Meta Structure ===\n\n";

$form = BpkhForm::find(1);

if (!$form) {
    echo "BPKH ID 1 not found\n";
    exit;
}

echo "BPKH: {$form->nama_bpkh}\n\n";

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

echo "Total entries: " . count($entries) . "\n\n";
echo "First 20 entries (showing keys only):\n";
echo "─────────────────────────────────────────\n";

$count = 0;
foreach ($entries as $i => [$key, $value]) {
    if ($count >= 20) break;

    // Check patterns
    $isQuestion = preg_match('/^\s*(\d+)\s*\./', (string) $key, $m);
    $isAnswer = preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) $key, $am);
    $isLampiran = preg_match('/^\s*Lampiran/i', (string) $key);

    $type = '';
    if ($isQuestion) {
        $type = " [QUESTION: {$m[1]}]";
    } elseif ($isAnswer) {
        $type = " [ANSWER: {$am[1]}]";
    } elseif ($isLampiran) {
        $type = " [LAMPIRAN]";
    }

    echo sprintf("%3d. %s%s\n", $i + 1, substr($key, 0, 80), $type);
    $count++;
}

echo "\n\nSearching for pattern around 'Lampiran':\n";
echo "─────────────────────────────────────────\n";

$currentQuestion = null;
foreach ($entries as $i => [$key, $value]) {
    // Track question number
    if (preg_match('/^\s*(\d+)\s*\./', (string) $key, $m)) {
        $currentQuestion = $m[1];
    }

    // Track answer
    if (preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) $key, $am)) {
        $currentQuestion = $am[1];
    }

    // Show lampiran with context
    if (preg_match('/^\s*Lampiran/i', (string) $key)) {
        echo "\nEntry #{$i}:\n";
        echo "  Current Question Context: " . ($currentQuestion ?? 'NONE') . "\n";
        echo "  Key: {$key}\n";

        // Show previous entry
        if ($i > 0) {
            echo "  Previous Entry: {$entries[$i-1][0]}\n";
        }

        // Check if value has URL
        $hasUrl = false;
        if (is_array($value)) {
            foreach ($value as $v) {
                if (is_string($v) && preg_match('/https?:\/\//i', $v)) {
                    $hasUrl = true;
                    break;
                }
            }
        } else {
            $hasUrl = preg_match('/https?:\/\//i', (string) $value);
        }
        echo "  Has URL: " . ($hasUrl ? 'YES' : 'NO') . "\n";
    }
}
