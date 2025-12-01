<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\BpkhList;
use App\Models\BpkhPoster;
use App\Models\ProdusenList;
use App\Models\ProdusenPoster;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PosterCmsController extends Controller
{
    /**
     * Display poster management page.
     */
    public function index()
    {
        $title = 'Manajemen Kumpulan Poster';
        $pageTitle = 'Manajemen Kumpulan Poster';
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard.index')],
            ['name' => 'CMS', 'url' => '#'],
            ['name' => 'Kumpulan Poster', 'active' => true],
        ];

        $bpkhPosters = BpkhPoster::orderBy('nama_bpkh')->get();
        $produsenPosters = ProdusenPoster::orderBy('nama_instansi')->get();
        $bpkhList = BpkhList::orderBy('nama_wilayah')->get();
        $produsenList = ProdusenList::orderBy('nama_unit')->get();

        return view('dashboard.pages.cms.kumpulan-poster.index', compact(
            'title',
            'pageTitle',
            'breadcrumbs',
            'bpkhPosters',
            'produsenPosters',
            'bpkhList',
            'produsenList'
        ));
    }

    /**
     * Store a new BPKH poster.
     */
    public function storeBpkh(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bpkh' => 'required|string|max:255',
            'poster' => 'required|file|mimes:pdf,jpeg,jpg,png|max:51200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal, silakan periksa kembali input Anda.');
        }

        try {
            $result = $this->processPosterFile($request->file('poster'), 'posters/bpkh');

            BpkhPoster::create([
                'nama_bpkh' => $request->nama_bpkh,
                'poster_pdf_path' => $result['path'],
                'original_filename' => $result['original_name'],
                'original_mime' => $result['original_mime'],
                'file_size' => $result['size'],
            ]);

            return redirect()->route('dashboard.cms.kumpulan-poster.index')
                ->with('success', 'Poster BPKH berhasil diupload');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses poster: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update existing BPKH poster (rename and optional file replace).
     */
    public function updateBpkh(Request $request, $id)
    {
        $poster = BpkhPoster::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_bpkh' => 'required|string|max:255',
            'poster' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:51200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal, silakan periksa kembali input Anda.');
        }

        try {
            $data = [
                'nama_bpkh' => $request->nama_bpkh,
            ];

            if ($request->hasFile('poster')) {
                if ($poster->poster_pdf_path && Storage::disk('public')->exists($poster->poster_pdf_path)) {
                    Storage::disk('public')->delete($poster->poster_pdf_path);
                }

                $result = $this->processPosterFile($request->file('poster'), 'posters/bpkh');

                $data = array_merge($data, [
                    'poster_pdf_path' => $result['path'],
                    'original_filename' => $result['original_name'],
                    'original_mime' => $result['original_mime'],
                    'file_size' => $result['size'],
                ]);
            }

            $poster->update($data);

            return redirect()->route('dashboard.cms.kumpulan-poster.index')
                ->with('success', 'Poster BPKH berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate poster: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update existing Produsen poster (rename and optional file replace).
     */
    public function updateProdusen(Request $request, $id)
    {
        $poster = ProdusenPoster::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_instansi' => 'required|string|max:255',
            'poster' => 'nullable|file|mimes:pdf,jpeg,jpg,png|max:51200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal, silakan periksa kembali input Anda.');
        }

        try {
            $data = [
                'nama_instansi' => $request->nama_instansi,
            ];

            if ($request->hasFile('poster')) {
                if ($poster->poster_pdf_path && Storage::disk('public')->exists($poster->poster_pdf_path)) {
                    Storage::disk('public')->delete($poster->poster_pdf_path);
                }

                $result = $this->processPosterFile($request->file('poster'), 'posters/produsen');

                $data = array_merge($data, [
                    'poster_pdf_path' => $result['path'],
                    'original_filename' => $result['original_name'],
                    'original_mime' => $result['original_mime'],
                    'file_size' => $result['size'],
                ]);
            }

            $poster->update($data);

            return redirect()->route('dashboard.cms.kumpulan-poster.index')
                ->with('success', 'Poster Produsen berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate poster: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Store a new Produsen poster.
     */
    public function storeProdusen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_instansi' => 'required|string|max:255',
            'poster' => 'required|file|mimes:pdf,jpeg,jpg,png|max:51200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validasi gagal, silakan periksa kembali input Anda.');
        }

        try {
            $result = $this->processPosterFile($request->file('poster'), 'posters/produsen');

            ProdusenPoster::create([
                'nama_instansi' => $request->nama_instansi,
                'poster_pdf_path' => $result['path'],
                'original_filename' => $result['original_name'],
                'original_mime' => $result['original_mime'],
                'file_size' => $result['size'],
            ]);

            return redirect()->route('dashboard.cms.kumpulan-poster.index')
                ->with('success', 'Poster Produsen berhasil diupload');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses poster: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a BPKH poster.
     */
    public function destroyBpkh($id)
    {
        $poster = BpkhPoster::findOrFail($id);

        if ($poster->poster_pdf_path && Storage::disk('public')->exists($poster->poster_pdf_path)) {
            Storage::disk('public')->delete($poster->poster_pdf_path);
        }

        $poster->delete();

        return redirect()->route('dashboard.cms.kumpulan-poster.index')
            ->with('success', 'Poster BPKH berhasil dihapus');
    }

    /**
     * Delete a Produsen poster.
     */
    public function destroyProdusen($id)
    {
        $poster = ProdusenPoster::findOrFail($id);

        if ($poster->poster_pdf_path && Storage::disk('public')->exists($poster->poster_pdf_path)) {
            Storage::disk('public')->delete($poster->poster_pdf_path);
        }

        $poster->delete();

        return redirect()->route('dashboard.cms.kumpulan-poster.index')
            ->with('success', 'Poster Produsen berhasil dihapus');
    }

    /**
     * Process uploaded poster file (compress & convert image to PDF).
     */
    protected function processPosterFile(UploadedFile $file, string $directory): array
    {
        $mime = $file->getMimeType();

        // If already PDF, just store it (tanpa kompres server-side)
        if ($mime === 'application/pdf') {
            $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', $baseName);
            $filename = time() . '_' . $safeBase . '.pdf';

            $path = $file->storeAs($directory, $filename, 'public');
            $size = Storage::disk('public')->size($path);

            return [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'original_mime' => $mime,
                'size' => $size,
            ];
        }

        // Handle image (jpg/png) - compress and store as optimized image
        $tmpPath = $file->getPathname();

        if (in_array($mime, ['image/jpeg', 'image/jpg'], true)) {
            $image = imagecreatefromjpeg($tmpPath);
        } elseif ($mime === 'image/png') {
            $image = imagecreatefrompng($tmpPath);
        } else {
            throw new \RuntimeException('Tipe file tidak didukung.');
        }

        if (!$image) {
            throw new \RuntimeException('Gagal membaca file gambar.');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $maxWidth = 4000;
        $maxHeight = 4000;
        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);

        $newWidth = (int) ($width * $ratio);
        $newHeight = (int) ($height * $ratio);

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG
        if ($mime === 'image/png') {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
            imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        $tmpJpeg = tempnam(sys_get_temp_dir(), 'poster_') . '.jpg';

        $quality = 95;
        $targetSize = 5 * 1024 * 1024; // 5 MB

        do {
            imagejpeg($resized, $tmpJpeg, $quality);
            $filesize = filesize($tmpJpeg);
            $quality -= 5;
        } while ($filesize > $targetSize && $quality >= 60);

        imagedestroy($resized);

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', $baseName);
        $filename = time() . '_' . $safeBase . '.jpg';

        $storagePath = $directory . '/' . $filename;

        Storage::disk('public')->put($storagePath, file_get_contents($tmpJpeg));

        @unlink($tmpJpeg);

        $size = Storage::disk('public')->size($storagePath);

        $maxSize = 5 * 1024 * 1024;
        if ($size > $maxSize) {
            Storage::disk('public')->delete($storagePath);
            throw new \RuntimeException('Poster tidak dapat dikompres sampai ukuran di bawah 5MB. Mohon unggah file dengan resolusi lebih kecil.');
        }

        return [
            'path' => $storagePath,
            'original_name' => $file->getClientOriginalName(),
            'original_mime' => $mime,
            'size' => $size,
        ];
    }
}
