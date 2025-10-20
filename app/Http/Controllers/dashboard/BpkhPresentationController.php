<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpkhPresentationAssesment;
use App\Models\RecordPresentationAssesment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BpkhPresentationController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Penilaian Presentasi BPKH';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Presentasi', 'url' => null],
            ['name' => 'BPKH', 'url' => null, 'active' => true]
        ];
        
        $term = $request->string('q')->toString();
        $forms = BpkhPresentationAssesment::query()
            ->search($term)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Fix missing kategori_skor for records that have nilai_final but no kategori
        foreach ($forms as $form) {
            if ($form->nilai_final !== null && empty($form->kategori_skor)) {
                $form->calculateNilaiFinal();
                $form->save();
            }
        }
        
        // Define presentation sessions
        $sessions = [
            'Sesi 1' => [
                'BPKH Wilayah III Pontianak',
                'BPKH Wilayah VII Makassar',
                'BPKH Wilayah XII Tanjung Pinang',
                'BPKH Wilayah XX Bandar Lampung'
            ],
            'Sesi 3' => [
                'BPKH Wilayah V Banjarbaru',
                'BPKH Wilayah IX Ambon',
                'BPKH Wilayah XVII Manokwari',
                'BPKH Wilayah XXI Palangkaraya'
            ],
            'Sesi 5' => [
                'BPKH Wilayah I Medan',
                'BPKH Wilayah VIII Denpasar',
                'BPKH Wilayah XI Yogyakarta',
                'BPKH Wilayah XVIII Banda Aceh'
            ]
        ];
        
        return view('dashboard.pages.presentation.bpkh.index', compact('title', 'pageTitle', 'breadcrumbs', 'forms', 'term', 'sessions'));
    }
    
    public function show(string $respondentId)
    {
        $title = 'Detail Penilaian Presentasi BPKH';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Presentasi', 'url' => route('dashboard.presentation.bpkh.index')],
            ['name' => 'BPKH', 'url' => route('dashboard.presentation.bpkh.index')],
            ['name' => 'Detail', 'url' => null, 'active' => true]
        ];
        
        $form = BpkhPresentationAssesment::where('respondent_id', $respondentId)->firstOrFail();
        
        // Get assessment history
        $assessmentHistory = RecordPresentationAssesment::byForm('bpkh', $respondentId)
            ->latest()
            ->get();
        
        return view('dashboard.pages.presentation.bpkh.show', compact('title', 'pageTitle', 'breadcrumbs', 'form', 'assessmentHistory'));
    }
    
    public function edit(string $respondentId)
    {
        $title = 'Nilai Presentasi BPKH';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Presentasi', 'url' => route('dashboard.presentation.bpkh.index')],
            ['name' => 'BPKH', 'url' => route('dashboard.presentation.bpkh.index')],
            ['name' => 'Nilai Presentasi', 'url' => null, 'active' => true]
        ];
        
        $form = BpkhPresentationAssesment::where('respondent_id', $respondentId)->firstOrFail();
        
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
            'Perlu pembinaan lebih lanjut'
        ];
        
        return view('dashboard.pages.presentation.bpkh.score', compact('title', 'pageTitle', 'breadcrumbs', 'form', 'userAssessment', 'rekomendasiOptions'));
    }
    
    public function update(Request $request, string $respondentId)
    {
        $data = $request->validate([
            'substansi_capaian' => ['required', 'integer', 'min:1', 'max:100'],
            'implementasi_strategi' => ['required', 'integer', 'min:1', 'max:100'],
            'kedalaman_analisis' => ['required', 'integer', 'min:1', 'max:100'],
            'kejelasan_alur' => ['required', 'integer', 'min:1', 'max:100'],
            'kemampuan_menjawab' => ['required', 'integer', 'min:1', 'max:100'],
            'kreativitas_daya_tarik' => ['required', 'integer', 'min:1', 'max:100'],
            'catatan_juri' => ['nullable', 'string', 'max:5000'],
            'rekomendasi' => ['required', 'string'],
        ]);
        
        $form = BpkhPresentationAssesment::where('respondent_id', $respondentId)->firstOrFail();
        
        DB::beginTransaction();
        try {
            // Calculate nilai akhir per user (weighted average)
            $aspekScores = [
                'substansi_capaian' => ['score' => $data['substansi_capaian'], 'bobot' => 30],
                'implementasi_strategi' => ['score' => $data['implementasi_strategi'], 'bobot' => 20],
                'kedalaman_analisis' => ['score' => $data['kedalaman_analisis'], 'bobot' => 15],
                'kejelasan_alur' => ['score' => $data['kejelasan_alur'], 'bobot' => 10],
                'kemampuan_menjawab' => ['score' => $data['kemampuan_menjawab'], 'bobot' => 15],
                'kreativitas_daya_tarik' => ['score' => $data['kreativitas_daya_tarik'], 'bobot' => 10],
            ];
            
            $nilaiAkhirUser = 0;
            foreach ($aspekScores as $aspek => $detail) {
                $nilaiAkhirUser += ($detail['score'] * $detail['bobot']) / 100;
            }
            $nilaiAkhirUser = round($nilaiAkhirUser, 2);
            
            // Update atau tambah penilaian user ini
            $penilaianJuri = $form->penilaian_per_juri ?? [];
            $found = false;
            
            foreach ($penilaianJuri as &$penilaian) {
                if ($penilaian['user_id'] == Auth::id()) {
                    $penilaian['user_name'] = Auth::user()->name;
                    $penilaian['nilai_akhir_user'] = $nilaiAkhirUser;
                    $penilaian['catatan'] = $data['catatan_juri'];
                    $penilaian['rekomendasi'] = $data['rekomendasi'];
                    $penilaian['assessed_at'] = now()->toDateTimeString();
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $penilaianJuri[] = [
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'nilai_akhir_user' => $nilaiAkhirUser,
                    'catatan' => $data['catatan_juri'],
                    'rekomendasi' => $data['rekomendasi'],
                    'assessed_at' => now()->toDateTimeString(),
                ];
            }
            
            $form->penilaian_per_juri = $penilaianJuri;
            
            // Update aspek penilaian (store all judges' scores)
            $currentAspek = $form->aspek_penilaian ?? [];
            $currentAspek[Auth::id()] = $aspekScores;
            $form->aspek_penilaian = $currentAspek;
            
            // Recalculate nilai final
            $form->calculateNilaiFinal();
            $form->save();
            
            // Record assessment history
            RecordPresentationAssesment::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'user_email' => Auth::user()->email,
                'user_role' => Auth::user()->role,
                'form_type' => 'bpkh',
                'respondent_id' => $respondentId,
                'form_name' => $form->nama_bpkh,
                'action_type' => 'presentation_assessment',
                'nilai_akhir_user' => $nilaiAkhirUser,
                'catatan_juri' => $data['catatan_juri'],
                'rekomendasi' => $data['rekomendasi'],
                'aspek_scores' => $aspekScores,
            ]);
            
            DB::commit();
            
            return redirect()
                ->route('dashboard.presentation.bpkh.show', $respondentId)
                ->with('success', 'Penilaian presentasi berhasil disimpan!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    public function history(string $respondentId)
    {
        $records = RecordPresentationAssesment::byForm('bpkh', $respondentId)
            ->latest()
            ->get();
        
        return response()->json($records);
    }
    
    public function bulkScoreForm(Request $request)
    {
        $title = 'Penilaian Kolektif Presentasi BPKH';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Presentasi', 'url' => route('dashboard.presentation.bpkh.index')],
            ['name' => 'BPKH', 'url' => route('dashboard.presentation.bpkh.index')],
            ['name' => 'Penilaian Kolektif', 'url' => null, 'active' => true]
        ];
        
        // Get selected IDs from query string
        $ids = $request->query('ids');
        if (!$ids) {
            return redirect()->route('dashboard.presentation.bpkh.index')
                ->withErrors(['error' => 'Tidak ada peserta yang dipilih']);
        }
        
        $selectedIds = explode(',', $ids);
        
        // Get forms data
        $forms = BpkhPresentationAssesment::whereIn('respondent_id', $selectedIds)->get();
        
        if ($forms->isEmpty()) {
            return redirect()->route('dashboard.presentation.bpkh.index')
                ->withErrors(['error' => 'Data peserta tidak ditemukan']);
        }
        
        // Get previous assessments for current user
        $userAssessments = [];
        foreach ($forms as $form) {
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
            
            $userAssessments[$form->respondent_id] = $userAssessment;
        }
        
        // Get aspek penilaian structure
        $aspekPenilaian = [
            'substansi_capaian' => ['label' => 'Substansi & Capaian', 'bobot' => 30],
            'implementasi_strategi' => ['label' => 'Implementasi & Strategi', 'bobot' => 20],
            'kedalaman_analisis' => ['label' => 'Kedalaman Analisis', 'bobot' => 15],
            'kejelasan_alur' => ['label' => 'Kejelasan Alur', 'bobot' => 10],
            'kemampuan_menjawab' => ['label' => 'Kemampuan Menjawab', 'bobot' => 15],
            'kreativitas_daya_tarik' => ['label' => 'Kreativitas & Daya Tarik', 'bobot' => 10],
        ];
        
        $rekomendasiOptions = [
            'Layak sebagai pemenang kategori',
            'Layak sebagai nominasi utama',
            'Perlu pembinaan lebih lanjut'
        ];
        
        return view('dashboard.pages.presentation.bpkh.collective_score', compact(
            'title', 'pageTitle', 'breadcrumbs', 'forms', 'aspekPenilaian', 'rekomendasiOptions', 'userAssessments'
        ));
    }
    
    public function bulkScoreStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $participants = $request->input('participants', []);
            
            if (empty($participants)) {
                return back()->withErrors(['error' => 'Tidak ada data penilaian yang dikirim']);
            }
            
            $successCount = 0;
            
            foreach ($participants as $respondentId => $data) {
                // Validate each participant data
                $validated = validator($data, [
                    'substansi_capaian' => ['required', 'integer', 'min:1', 'max:100'],
                    'implementasi_strategi' => ['required', 'integer', 'min:1', 'max:100'],
                    'kedalaman_analisis' => ['required', 'integer', 'min:1', 'max:100'],
                    'kejelasan_alur' => ['required', 'integer', 'min:1', 'max:100'],
                    'kemampuan_menjawab' => ['required', 'integer', 'min:1', 'max:100'],
                    'kreativitas_daya_tarik' => ['required', 'integer', 'min:1', 'max:100'],
                    'catatan_juri' => ['nullable', 'string', 'max:5000'],
                    'rekomendasi' => ['required', 'string'],
                ])->validate();
                
                $form = BpkhPresentationAssesment::where('respondent_id', $respondentId)->firstOrFail();
                
                // Calculate nilai akhir per user
                $aspekScores = [
                    'substansi_capaian' => ['score' => $validated['substansi_capaian'], 'bobot' => 30],
                    'implementasi_strategi' => ['score' => $validated['implementasi_strategi'], 'bobot' => 20],
                    'kedalaman_analisis' => ['score' => $validated['kedalaman_analisis'], 'bobot' => 15],
                    'kejelasan_alur' => ['score' => $validated['kejelasan_alur'], 'bobot' => 10],
                    'kemampuan_menjawab' => ['score' => $validated['kemampuan_menjawab'], 'bobot' => 15],
                    'kreativitas_daya_tarik' => ['score' => $validated['kreativitas_daya_tarik'], 'bobot' => 10],
                ];
                
                $nilaiAkhirUser = 0;
                foreach ($aspekScores as $aspek => $detail) {
                    $nilaiAkhirUser += ($detail['score'] * $detail['bobot']) / 100;
                }
                $nilaiAkhirUser = round($nilaiAkhirUser, 2);
                
                // Update penilaian juri
                $penilaianJuri = $form->penilaian_per_juri ?? [];
                $found = false;
                
                foreach ($penilaianJuri as &$penilaian) {
                    if ($penilaian['user_id'] == Auth::id()) {
                        $penilaian['user_name'] = Auth::user()->name;
                        $penilaian['nilai_akhir_user'] = $nilaiAkhirUser;
                        $penilaian['catatan'] = $validated['catatan_juri'];
                        $penilaian['rekomendasi'] = $validated['rekomendasi'];
                        $penilaian['assessed_at'] = now()->toDateTimeString();
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $penilaianJuri[] = [
                        'user_id' => Auth::id(),
                        'user_name' => Auth::user()->name,
                        'nilai_akhir_user' => $nilaiAkhirUser,
                        'catatan' => $validated['catatan_juri'],
                        'rekomendasi' => $validated['rekomendasi'],
                        'assessed_at' => now()->toDateTimeString(),
                    ];
                }
                
                $form->penilaian_per_juri = $penilaianJuri;
                
                // Update aspek penilaian
                $currentAspek = $form->aspek_penilaian ?? [];
                $currentAspek[Auth::id()] = $aspekScores;
                $form->aspek_penilaian = $currentAspek;
                
                // Recalculate nilai final
                $form->calculateNilaiFinal();
                $form->save();
                
                // Record assessment history
                RecordPresentationAssesment::create([
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'user_email' => Auth::user()->email,
                    'user_role' => Auth::user()->role,
                    'form_type' => 'bpkh',
                    'respondent_id' => $respondentId,
                    'form_name' => $form->nama_bpkh,
                    'action_type' => 'presentation_assessment',
                    'nilai_akhir_user' => $nilaiAkhirUser,
                    'catatan_juri' => $validated['catatan_juri'],
                    'rekomendasi' => $validated['rekomendasi'],
                    'aspek_scores' => $aspekScores,
                ]);
                
                $successCount++;
            }
            
            DB::commit();
            
            return redirect()
                ->route('dashboard.presentation.bpkh.index')
                ->with('success', "Berhasil menyimpan penilaian untuk {$successCount} peserta!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
