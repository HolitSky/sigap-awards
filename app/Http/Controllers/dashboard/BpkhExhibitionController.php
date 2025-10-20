<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpkhExhibition;
use App\Models\RecordExhibitionAssesment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BpkhExhibitionController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Penilaian Exhibition/Poster BPKH';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Exhibition', 'url' => null],
            ['name' => 'BPKH', 'url' => null, 'active' => true]
        ];
        
        $term = $request->string('q')->toString();
        $forms = BpkhExhibition::query()
            ->search($term)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.pages.exhibition.bpkh.index', compact('title', 'pageTitle', 'breadcrumbs', 'forms', 'term'));
    }
    
    public function show(string $respondentId)
    {
        $title = 'Detail Penilaian Exhibition BPKH';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Exhibition', 'url' => route('dashboard.exhibition.bpkh.index')],
            ['name' => 'BPKH', 'url' => route('dashboard.exhibition.bpkh.index')],
            ['name' => 'Detail', 'url' => null, 'active' => true]
        ];
        
        $form = BpkhExhibition::where('respondent_id', $respondentId)->firstOrFail();
        
        // Get assessment history
        $assessmentHistory = RecordExhibitionAssesment::where('exhibition_type', 'bpkh')
            ->where('exhibition_id', $form->id)
            ->latest()
            ->get();
        
        return view('dashboard.pages.exhibition.bpkh.show', compact('title', 'pageTitle', 'breadcrumbs', 'form', 'assessmentHistory'));
    }
    
    public function edit(string $respondentId)
    {
        $title = 'Nilai Exhibition/Poster BPKH';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Exhibition', 'url' => route('dashboard.exhibition.bpkh.index')],
            ['name' => 'BPKH', 'url' => route('dashboard.exhibition.bpkh.index')],
            ['name' => 'Nilai Exhibition', 'url' => null, 'active' => true]
        ];
        
        $form = BpkhExhibition::where('respondent_id', $respondentId)->firstOrFail();
        
        // Get current user's previous assessment if any
        $userAssessment = [];
        
        // Get penilaian data (catatan, rekomendasi, dll)
        if ($form->penilaian_per_juri) {
            foreach ($form->penilaian_per_juri as $penilaian) {
                if ($penilaian['user_id'] == Auth::id()) {
                    $userAssessment = $penilaian;
                    break;
                }
            }
        }
        
        // Get aspek scores detail
        if ($form->aspek_penilaian && isset($form->aspek_penilaian[Auth::id()])) {
            $userAssessment['aspek_scores'] = $form->aspek_penilaian[Auth::id()];
        }
        
        $rekomendasiOptions = [
            'Layak sebagai pemenang kategori',
            'Layak sebagai nominasi utama',
            'Perlu banyak improvisasi'
        ];
        
        return view('dashboard.pages.exhibition.bpkh.score', compact('title', 'pageTitle', 'breadcrumbs', 'form', 'userAssessment', 'rekomendasiOptions'));
    }
    
    public function update(Request $request, string $respondentId)
    {
        $data = $request->validate([
            'kesesuaian_materi' => ['required', 'integer', 'min:1', 'max:100'],
            'kejelasan_informasi' => ['required', 'integer', 'min:1', 'max:100'],
            'kualitas_visual' => ['required', 'integer', 'min:1', 'max:100'],
            'inovasi_kreativitas' => ['required', 'integer', 'min:1', 'max:100'],
            'relevansi_tema' => ['required', 'integer', 'min:1', 'max:100'],
            'catatan_juri' => ['nullable', 'string', 'max:5000'],
            'rekomendasi' => ['required', 'string'],
        ]);
        
        DB::beginTransaction();
        try {
            $form = BpkhExhibition::where('respondent_id', $respondentId)->firstOrFail();
            $userId = Auth::id();
            $userName = Auth::user()->name;
            
            // Bobot per aspek
            $bobotAspek = [
                'kesesuaian_materi' => 30,
                'kejelasan_informasi' => 25,
                'kualitas_visual' => 20,
                'inovasi_kreativitas' => 15,
                'relevansi_tema' => 10,
            ];
            
            // Calculate nilai akhir per aspek (score * bobot / 100)
            $nilaiPerAspek = [];
            $totalNilai = 0;
            foreach ($bobotAspek as $key => $bobot) {
                $nilaiAspek = ($data[$key] * $bobot) / 100;
                $nilaiPerAspek[$key] = $nilaiAspek;
                $totalNilai += $nilaiAspek;
            }
            
            // Store in aspek_penilaian JSON
            $aspekPenilaian = $form->aspek_penilaian ?? [];
            $aspekPenilaian[$userId] = [
                'kesesuaian_materi' => $data['kesesuaian_materi'],
                'kejelasan_informasi' => $data['kejelasan_informasi'],
                'kualitas_visual' => $data['kualitas_visual'],
                'inovasi_kreativitas' => $data['inovasi_kreativitas'],
                'relevansi_tema' => $data['relevansi_tema'],
                'nilai_per_aspek' => $nilaiPerAspek,
                'nilai_akhir_user' => $totalNilai,
            ];
            
            // Store in penilaian_per_juri JSON
            $penilaianPerJuri = $form->penilaian_per_juri ?? [];
            $juriExists = false;
            foreach ($penilaianPerJuri as &$penilaian) {
                if ($penilaian['user_id'] == $userId) {
                    $penilaian['nilai_akhir_user'] = $totalNilai;
                    $penilaian['catatan_juri'] = $data['catatan_juri'];
                    $penilaian['rekomendasi'] = $data['rekomendasi'];
                    $penilaian['updated_at'] = now()->toDateTimeString();
                    $juriExists = true;
                    break;
                }
            }
            
            if (!$juriExists) {
                $penilaianPerJuri[] = [
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'nilai_akhir_user' => $totalNilai,
                    'catatan_juri' => $data['catatan_juri'],
                    'rekomendasi' => $data['rekomendasi'],
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
            
            // Calculate nilai final (average of all juri)
            $totalJuri = count($penilaianPerJuri);
            $sumNilai = array_sum(array_column($penilaianPerJuri, 'nilai_akhir_user'));
            $nilaiFinal = $totalJuri > 0 ? $sumNilai / $totalJuri : 0;
            
            // Calculate nilai final dengan bobot
            $nilaiFinalDenganBobot = ($nilaiFinal * $form->bobot_exhibition) / 100;
            
            // Determine kategori and deskripsi
            $kategoriData = $this->getKategoriPenilaian($nilaiFinal);
            
            // Update form
            $form->update([
                'aspek_penilaian' => $aspekPenilaian,
                'penilaian_per_juri' => $penilaianPerJuri,
                'total_juri_menilai' => $totalJuri,
                'nilai_final' => $nilaiFinal,
                'nilai_final_dengan_bobot' => $nilaiFinalDenganBobot,
                'kategori_penilaian' => $kategoriData['kategori'],
                'deskripsi_kategori' => $kategoriData['deskripsi'],
                'status' => 'assessed',
            ]);
            
            // Record assessment
            RecordExhibitionAssesment::updateOrCreate(
                [
                    'exhibition_type' => 'bpkh',
                    'exhibition_id' => $form->id,
                    'user_id' => $userId,
                ],
                [
                    'user_name' => $userName,
                    'nilai_akhir_user' => $totalNilai,
                    'catatan_juri' => $data['catatan_juri'],
                    'rekomendasi' => $data['rekomendasi'],
                    'assessed_at' => now(),
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('dashboard.exhibition.bpkh.show', $respondentId)
                ->with('success', 'Penilaian exhibition berhasil disimpan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    protected function getKategoriPenilaian($nilai)
    {
        if ($nilai >= 81) {
            return [
                'kategori' => 'Sangat Baik',
                'deskripsi' => 'Sangat informatif, inovatif, dan berdaya tarik tinggi.'
            ];
        } elseif ($nilai >= 61) {
            return [
                'kategori' => 'Baik',
                'deskripsi' => 'Informasi lengkap, visual menarik, dan sesuai tema.'
            ];
        } elseif ($nilai >= 41) {
            return [
                'kategori' => 'Cukup',
                'deskripsi' => 'Konten cukup jelas dan rapi, inovasi masih terbatas.'
            ];
        } elseif ($nilai >= 21) {
            return [
                'kategori' => 'Kurang',
                'deskripsi' => 'Informasi dasar disajikan, namun kurang jelas dan menarik.'
            ];
        } else {
            return [
                'kategori' => 'Sangat Kurang',
                'deskripsi' => 'Substansi dan tampilan tidak memenuhi kriteria.'
            ];
        }
    }
}
