<?php

namespace App\Console\Commands;

use App\Models\BpkhForm;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ScrapeBpkhFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bpkh:scrape-files
                            {--id= : Specific BPKH Form ID to scrape}
                            {--all : Scrape all BPKH forms}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape and download files from BPKH form meta data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting BPKH Files Scraping...');

        $query = BpkhForm::query();

        if ($this->option('id')) {
            $query->where('id', $this->option('id'));
        } elseif (!$this->option('all')) {
            $this->error('Please specify --id=X or --all flag');
            return 1;
        }

        $forms = $query->get();

        if ($forms->isEmpty()) {
            $this->warn('No BPKH forms found.');
            return 0;
        }

        $this->info("Found {$forms->count()} BPKH form(s) to process.");

        $totalDownloaded = 0;
        $totalFailed = 0;

        foreach ($forms as $form) {
            $this->newLine();
            $this->info("📋 Processing: {$form->nama_bpkh} (ID: {$form->id})");

            $result = $this->processBpkhForm($form);
            $totalDownloaded += $result['downloaded'];
            $totalFailed += $result['failed'];
        }

        $this->newLine();
        $this->info("✅ Scraping completed!");
        $this->info("📥 Total files downloaded: {$totalDownloaded}");
        if ($totalFailed > 0) {
            $this->warn("⚠️  Total files failed: {$totalFailed}");
        }

        return 0;
    }

    /**
     * Process a single BPKH form
     */
    protected function processBpkhForm(BpkhForm $form): array
    {
        $downloaded = 0;
        $failed = 0;

        // Create folder name from BPKH name
        $folderName = $this->sanitizeFolderName($form->nama_bpkh);
        $basePath = "scrapping_script/bpkh_form/{$folderName}";

        // Parse meta data
        $entries = $this->parseMetaData($form->meta);

        if (empty($entries)) {
            $this->warn("  No meta data found for this form.");
            return ['downloaded' => 0, 'failed' => 0];
        }

        // Track current question number for context
        $currentQuestionNumber = null;

        foreach ($entries as [$key, $value]) {
            // Check if this is a question or answer to track context
            // Pattern 1: "soal 1.1", "soal 2.3", etc.
            if (preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) $key, $matches)) {
                $currentQuestionNumber = $matches[1];
            }
            // Pattern 2: "1.1 PEMBENTUKAN...", "2.1 BERBAGI DATA...", etc.
            elseif (preg_match('/^\s*(\d+\.\d+)\s+/', (string) $key, $matches)) {
                $currentQuestionNumber = $matches[1];
            }

            // Check if this is an attachment field
            $isAttachment = preg_match('/^\s*Lampiran/i', (string) $key);

            if (!$isAttachment) {
                continue;
            }

            // Extract URLs from value
            $urls = $this->extractUrls($value);

            if (empty($urls)) {
                continue;
            }

            $this->line("  📎 {$key}");

            foreach ($urls as $url) {
                try {
                    $fileName = $this->generateFileName($key, $url, $currentQuestionNumber);
                    $filePath = "{$basePath}/{$fileName}";

                    $this->line("    ⬇️  Downloading: {$fileName}");

                    // Download file (with SSL verification disabled for development)
                    $response = Http::withOptions([
                        'verify' => false, // Disable SSL verification
                    ])->timeout(120)->get($url);

                    if ($response->successful()) {
                        // Save to storage
                        Storage::put($filePath, $response->body());

                        $fileSize = $this->formatBytes(strlen($response->body()));
                        $this->info("    ✓ Saved: {$filePath} ({$fileSize})");
                        $downloaded++;
                    } else {
                        $this->error("    ✗ Failed: HTTP {$response->status()}");
                        $failed++;
                    }
                } catch (\Exception $e) {
                    $this->error("    ✗ Error: {$e->getMessage()}");
                    $failed++;
                }
            }
        }

        return ['downloaded' => $downloaded, 'failed' => $failed];
    }

    /**
     * Parse meta data into entries
     */
    protected function parseMetaData($meta): array
    {
        $entries = [];

        if (!is_array($meta)) {
            return $entries;
        }

        // Support both legacy associative array and new ordered array format
        $isOrdered = isset($meta[0]) && is_array($meta[0]) &&
                     array_key_exists('key', $meta[0]) &&
                     array_key_exists('value', $meta[0]);

        if ($isOrdered) {
            foreach ($meta as $item) {
                $entries[] = [$item['key'], $item['value']];
            }
        } else {
            foreach ($meta as $k => $v) {
                $entries[] = [$k, $v];
            }
        }

        return $entries;
    }

    /**
     * Extract URLs from value
     */
    protected function extractUrls($value): array
    {
        $links = [];

        if (is_array($value)) {
            foreach ($value as $vv) {
                if (is_string($vv) && preg_match_all('/https?:\/\/\S+/i', $vv, $matches)) {
                    foreach ($matches[0] as $url) {
                        $links[] = $url;
                    }
                }
            }
        } else {
            $raw = (string) $value;
            if (preg_match_all('/https?:\/\/\S+/i', $raw, $matches)) {
                $links = $matches[0];
            }
        }

        return $links;
    }

    /**
     * Generate file name from title and URL
     */
    protected function generateFileName(string $title, string $url, ?string $questionNumber = null): string
    {
        // Clean title - remove "Lampiran" prefix
        $cleanTitle = preg_replace('/^\s*Lampiran\s*/i', '', $title);
        $cleanTitle = preg_replace('/[^a-zA-Z0-9\s\-_]/u', '', $cleanTitle);
        $cleanTitle = trim($cleanTitle);
        $cleanTitle = Str::limit($cleanTitle, 50, '');
        $cleanTitle = preg_replace('/\s+/', '_', $cleanTitle);

        // Extract original filename from URL
        $originalFileName = $this->extractFileNameFromUrl($url);

        // Build filename parts
        $parts = [];

        // Add question number prefix if available
        if ($questionNumber) {
            $parts[] = 'no_' . str_replace('.', '_', $questionNumber);
        }

        // Add cleaned title if not empty
        if (!empty($cleanTitle)) {
            $parts[] = $cleanTitle;
        }

        // Add original filename
        $parts[] = $originalFileName;

        // Combine all parts
        return implode('_', $parts);
    }

    /**
     * Extract filename from URL
     */
    protected function extractFileNameFromUrl(string $url): string
    {
        // Parse URL
        $parsed = parse_url($url);
        $path = $parsed['path'] ?? '';

        // Extract filename from path
        $pathParts = explode('/', $path);
        $fileName = end($pathParts);

        // Remove query parameters if any
        $fileName = preg_replace('/\?.*$/', '', $fileName);

        // If empty or just "private", generate a name
        if (empty($fileName) || $fileName === 'private') {
            // Try to get from URL pattern
            if (preg_match('/\/([^\/\?]+\.(pdf|jpg|jpeg|png|doc|docx|xls|xlsx|zip|rar))/i', $url, $matches)) {
                return $matches[1];
            }

            // Generate from timestamp
            return 'file_' . time() . '.pdf';
        }

        return $fileName;
    }

    /**
     * Sanitize folder name
     */
    protected function sanitizeFolderName(string $name): string
    {
        // Convert to lowercase
        $name = Str::lower($name);

        // Remove "BPKH" prefix if exists
        $name = preg_replace('/^bpkh\s*/i', '', $name);

        // Replace spaces and special chars with underscore
        $name = preg_replace('/[^a-z0-9]+/', '_', $name);

        // Remove leading/trailing underscores
        $name = trim($name, '_');

        // Prefix with bpkh_
        return 'bpkh_' . $name;
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
