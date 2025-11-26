<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PemenangSigap;
use App\Models\BpkhList;
use App\Models\ProdusenList;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PemenangSigapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Pemenang SIGAP Award';
        $pemenang = PemenangSigap::ordered()->get();

        // Get BPKH and Produsen lists for select2
        $bpkhList = BpkhList::orderBy('nama_wilayah')->get();
        $produsenList = ProdusenList::orderBy('nama_unit')->get();

        return view('dashboard.pages.cms.pemenang-sigap.index', compact('title', 'pemenang', 'bpkhList', 'produsenList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|in:poster_terbaik,poster_favorit,pengelola_igt_terbaik,inovasi_bpkh_terbaik,inovasi_produsen_terbaik',
            'tipe_peserta' => 'required|in:bpkh,produsen',
            'nama_pemenang' => 'required|string|max:255',
            'nama_petugas' => 'nullable|string|max:255',
            'juara' => 'required|in:juara_1,juara_2,juara_3,juara_harapan',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->only(['kategori', 'tipe_peserta', 'nama_pemenang', 'nama_petugas', 'juara', 'deskripsi', 'urutan']);
            $data['is_active'] = $request->has('is_active') ? true : false;
            $data['urutan'] = $request->urutan ?? 0;

            if ($request->hasFile('foto')) {
                $data['foto_path'] = $this->processWinnerImage($request->file('foto'));
            }

            PemenangSigap::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data pemenang berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pemenang = PemenangSigap::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kategori' => 'required|in:poster_terbaik,poster_favorit,pengelola_igt_terbaik,inovasi_bpkh_terbaik,inovasi_produsen_terbaik',
            'tipe_peserta' => 'required|in:bpkh,produsen',
            'nama_pemenang' => 'required|string|max:255',
            'nama_petugas' => 'nullable|string|max:255',
            'juara' => 'required|in:juara_1,juara_2,juara_3,juara_harapan',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:8192',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->only(['kategori', 'tipe_peserta', 'nama_pemenang', 'nama_petugas', 'juara', 'deskripsi', 'urutan']);
            $data['is_active'] = $request->has('is_active') ? true : false;
            $data['urutan'] = $request->urutan ?? 0;

            if ($request->hasFile('foto')) {
                if ($pemenang->foto_path && Storage::disk('public')->exists($pemenang->foto_path)) {
                    Storage::disk('public')->delete($pemenang->foto_path);
                }

                $data['foto_path'] = $this->processWinnerImage($request->file('foto'));
            }

            $pemenang->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data pemenang berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pemenang = PemenangSigap::findOrFail($id);

            // Delete foto if exists
            if ($pemenang->foto_path && Storage::disk('public')->exists($pemenang->foto_path)) {
                Storage::disk('public')->delete($pemenang->foto_path);
            }

            $pemenang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pemenang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get peserta list based on tipe
     */
    public function getPesertaList(Request $request)
    {
        $tipe = $request->get('tipe');

        if ($tipe === 'bpkh') {
            $data = BpkhList::orderBy('nama_wilayah')->get()->map(function($item) {
                return [
                    'id' => $item->nama_wilayah,
                    'text' => $item->nama_wilayah
                ];
            });
        } else {
            $data = ProdusenList::orderBy('nama_unit')->get()->map(function($item) {
                return [
                    'id' => $item->nama_unit,
                    'text' => $item->nama_unit
                ];
            });
        }

        return response()->json($data);
    }

    protected function processWinnerImage(UploadedFile $file): string
    {
        $tmpPath = $file->getPathname();
        $mime = $file->getMimeType();

        $image = null;
        $extension = 'jpg';

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $image = imagecreatefromjpeg($tmpPath);
        } elseif ($mime === 'image/png') {
            $image = imagecreatefrompng($tmpPath);
        } else {
            $filename = time() . '_' . $file->getClientOriginalName();
            return $file->storeAs('pemenang-sigap', $filename, 'public');
        }

        if (!$image) {
            $filename = time() . '_' . $file->getClientOriginalName();
            return $file->storeAs('pemenang-sigap', $filename, 'public');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $maxWidth = 1600;
        $maxHeight = 1600;
        $ratio = min($maxWidth / $width, $maxHeight / $height, 1);

        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        $tmpFile = tempnam(sys_get_temp_dir(), 'winner_');
        $quality = 85;
        $targetSize = 2 * 1024 * 1024;

        do {
            imagejpeg($resized, $tmpFile, $quality);
            $filesize = filesize($tmpFile);
            $quality -= 5;
        } while ($filesize > $targetSize && $quality >= 60);

        imagedestroy($resized);

        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = time() . '_' . $baseName . '.' . $extension;
        $storagePath = 'pemenang-sigap/' . $filename;

        Storage::disk('public')->put($storagePath, file_get_contents($tmpFile));
        @unlink($tmpFile);

        return $storagePath;
    }
}
