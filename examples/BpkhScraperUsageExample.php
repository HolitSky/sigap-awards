<?php

/**
 * Contoh Penggunaan BPKH File Scraper dari Code
 *
 * File ini menunjukkan cara menggunakan hasil scraping BPKH
 * dari dalam aplikasi Laravel
 */

namespace App\Examples;

use Illuminate\Support\Facades\Storage;
use App\Models\BpkhForm;
use Illuminate\Support\Facades\Artisan;

class BpkhScraperUsageExample
{
    /**
     * Contoh 1: Jalankan scraping dari controller
     */
    public function runScrapingFromController()
    {
        // Scrape specific BPKH
        Artisan::call('bpkh:scrape-files', [
            '--id' => 1
        ]);

        $output = Artisan::output();

        return response()->json([
            'success' => true,
            'message' => 'Scraping completed',
            'output' => $output
        ]);
    }

    /**
     * Contoh 2: Scrape all BPKH
     */
    public function scrapeAllBpkh()
    {
        Artisan::call('bpkh:scrape-files', [
            '--all' => true
        ]);

        return Artisan::output();
    }

    /**
     * Contoh 3: List semua file yang sudah di-scrape untuk BPKH tertentu
     */
    public function listScrapedFiles($bpkhId)
    {
        $bpkh = BpkhForm::findOrFail($bpkhId);

        // Generate folder name
        $folderName = $this->sanitizeFolderName($bpkh->nama_bpkh);
        $path = "scrapping_script/bpkh_form/{$folderName}";

        // Check if folder exists
        if (!Storage::exists($path)) {
            return [
                'success' => false,
                'message' => 'Folder belum ada. Silakan scrape terlebih dahulu.',
                'files' => []
            ];
        }

        // Get all files
        $files = Storage::files($path);

        // Get file details
        $fileDetails = [];
        foreach ($files as $file) {
            $fileDetails[] = [
                'name' => basename($file),
                'path' => $file,
                'size' => Storage::size($file),
                'size_human' => $this->formatBytes(Storage::size($file)),
                'last_modified' => Storage::lastModified($file),
                'url' => Storage::url($file), // Jika public
            ];
        }

        return [
            'success' => true,
            'bpkh' => $bpkh->nama_bpkh,
            'folder' => $path,
            'total_files' => count($fileDetails),
            'files' => $fileDetails
        ];
    }

    /**
     * Contoh 4: Download file tertentu
     */
    public function downloadFile($bpkhId, $fileName)
    {
        $bpkh = BpkhForm::findOrFail($bpkhId);

        $folderName = $this->sanitizeFolderName($bpkh->nama_bpkh);
        $filePath = "scrapping_script/bpkh_form/{$folderName}/{$fileName}";

        if (!Storage::exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::download($filePath);
    }

    /**
     * Contoh 5: Get file content (untuk preview PDF, image, dll)
     */
    public function getFileContent($bpkhId, $fileName)
    {
        $bpkh = BpkhForm::findOrFail($bpkhId);

        $folderName = $this->sanitizeFolderName($bpkh->nama_bpkh);
        $filePath = "scrapping_script/bpkh_form/{$folderName}/{$fileName}";

        if (!Storage::exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $mimeType = Storage::mimeType($filePath);
        $content = Storage::get($filePath);

        return response($content)
            ->header('Content-Type', $mimeType);
    }

    /**
     * Contoh 6: Check apakah BPKH sudah di-scrape
     */
    public function isScraped($bpkhId)
    {
        $bpkh = BpkhForm::findOrFail($bpkhId);

        $folderName = $this->sanitizeFolderName($bpkh->nama_bpkh);
        $path = "scrapping_script/bpkh_form/{$folderName}";

        $exists = Storage::exists($path);
        $fileCount = $exists ? count(Storage::files($path)) : 0;

        return [
            'scraped' => $exists && $fileCount > 0,
            'folder' => $path,
            'file_count' => $fileCount
        ];
    }

    /**
     * Contoh 7: Get statistics semua BPKH yang sudah di-scrape
     */
    public function getScrapingStatistics()
    {
        $basePath = "scrapping_script/bpkh_form";

        if (!Storage::exists($basePath)) {
            return [
                'total_bpkh' => 0,
                'total_files' => 0,
                'total_size' => 0,
                'bpkh_list' => []
            ];
        }

        $directories = Storage::directories($basePath);
        $stats = [
            'total_bpkh' => count($directories),
            'total_files' => 0,
            'total_size' => 0,
            'bpkh_list' => []
        ];

        foreach ($directories as $dir) {
            $files = Storage::files($dir);
            $size = 0;

            foreach ($files as $file) {
                $size += Storage::size($file);
            }

            $stats['total_files'] += count($files);
            $stats['total_size'] += $size;

            $stats['bpkh_list'][] = [
                'folder' => basename($dir),
                'file_count' => count($files),
                'size' => $size,
                'size_human' => $this->formatBytes($size)
            ];
        }

        $stats['total_size_human'] = $this->formatBytes($stats['total_size']);

        return $stats;
    }

    /**
     * Contoh 8: Delete scraped files untuk BPKH tertentu
     */
    public function deleteScrapedFiles($bpkhId)
    {
        $bpkh = BpkhForm::findOrFail($bpkhId);

        $folderName = $this->sanitizeFolderName($bpkh->nama_bpkh);
        $path = "scrapping_script/bpkh_form/{$folderName}";

        if (!Storage::exists($path)) {
            return [
                'success' => false,
                'message' => 'Folder tidak ditemukan'
            ];
        }

        // Delete all files in folder
        $files = Storage::files($path);
        foreach ($files as $file) {
            Storage::delete($file);
        }

        // Delete folder
        Storage::deleteDirectory($path);

        return [
            'success' => true,
            'message' => "Berhasil menghapus {count($files)} file",
            'deleted_files' => count($files)
        ];
    }

    /**
     * Contoh 9: Re-scrape BPKH (delete old files then scrape again)
     */
    public function rescrape($bpkhId)
    {
        // Delete old files
        $this->deleteScrapedFiles($bpkhId);

        // Scrape again
        Artisan::call('bpkh:scrape-files', [
            '--id' => $bpkhId
        ]);

        return [
            'success' => true,
            'message' => 'Re-scraping completed',
            'output' => Artisan::output()
        ];
    }

    /**
     * Contoh 10: Zip all files untuk download
     */
    public function zipBpkhFiles($bpkhId)
    {
        $bpkh = BpkhForm::findOrFail($bpkhId);

        $folderName = $this->sanitizeFolderName($bpkh->nama_bpkh);
        $path = "scrapping_script/bpkh_form/{$folderName}";

        if (!Storage::exists($path)) {
            abort(404, 'Folder tidak ditemukan');
        }

        $files = Storage::files($path);

        if (empty($files)) {
            abort(404, 'Tidak ada file untuk di-zip');
        }

        // Create zip file
        $zipFileName = "{$folderName}_" . date('Y-m-d_His') . ".zip";
        $zipPath = "temp/{$zipFileName}";

        $zip = new \ZipArchive();
        $zipFullPath = Storage::path($zipPath);

        if ($zip->open($zipFullPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                $zip->addFile(
                    Storage::path($file),
                    basename($file)
                );
            }
            $zip->close();
        }

        return Storage::download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }

    /**
     * Helper: Sanitize folder name
     */
    protected function sanitizeFolderName(string $name): string
    {
        $name = strtolower($name);
        $name = preg_replace('/^bpkh\s*/i', '', $name);
        $name = preg_replace('/[^a-z0-9]+/', '_', $name);
        $name = trim($name, '_');
        return 'bpkh_' . $name;
    }

    /**
     * Helper: Format bytes to human readable
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

/**
 * CONTOH PENGGUNAAN DI CONTROLLER
 *
 * use App\Examples\BpkhScraperUsageExample;
 *
 * class BpkhController extends Controller
 * {
 *     public function scrape($id)
 *     {
 *         $scraper = new BpkhScraperUsageExample();
 *         return $scraper->runScrapingFromController();
 *     }
 *
 *     public function listFiles($id)
 *     {
 *         $scraper = new BpkhScraperUsageExample();
 *         return $scraper->listScrapedFiles($id);
 *     }
 *
 *     public function download($id, $fileName)
 *     {
 *         $scraper = new BpkhScraperUsageExample();
 *         return $scraper->downloadFile($id, $fileName);
 *     }
 *
 *     public function statistics()
 *     {
 *         $scraper = new BpkhScraperUsageExample();
 *         return $scraper->getScrapingStatistics();
 *     }
 * }
 */

/**
 * CONTOH ROUTES
 *
 * Route::prefix('bpkh')->group(function () {
 *     Route::post('scrape/{id}', [BpkhController::class, 'scrape']);
 *     Route::get('files/{id}', [BpkhController::class, 'listFiles']);
 *     Route::get('download/{id}/{fileName}', [BpkhController::class, 'download']);
 *     Route::get('statistics', [BpkhController::class, 'statistics']);
 *     Route::delete('files/{id}', [BpkhController::class, 'deleteFiles']);
 *     Route::post('rescrape/{id}', [BpkhController::class, 'rescrape']);
 *     Route::get('zip/{id}', [BpkhController::class, 'zipFiles']);
 * });
 */
