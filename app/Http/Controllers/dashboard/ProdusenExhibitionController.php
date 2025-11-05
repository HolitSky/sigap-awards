<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdusenExhibition;
use App\Models\RecordExhibitionAssesment;
use App\Models\ProdusenPresentationSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ProdusenExhibitionController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Penilaian Exhibition/Poster Produsen DG';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Exhibition', 'url' => null],
            ['name' => 'Produsen DG', 'url' => null, 'active' => true]
        ];
        
        $term = $request->string('q')->toString();
        $forms = ProdusenExhibition::query()
            ->search($term)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get presentation sessions (same sessions used for exhibition)
        $sessionsGrouped = ProdusenPresentationSession::getGroupedSessions();
        $sessions = [];
        foreach ($sessionsGrouped as $sessionName => $participants) {
            $sessions[$sessionName] = $participants->pluck('nama_instansi')->toArray();
        }
        
        // Check which sessions are completed by current user for exhibition
        $currentUserId = Auth::id();
        $completedSessions = [];
        
        foreach ($sessions as $sessionName => $participants) {
            $allCompleted = true;
            foreach ($participants as $participantName) {
                // Find form for this participant
                $form = $forms->firstWhere('nama_instansi', $participantName);
                
                if (!$form) {
                    $allCompleted = false;
                    break;
                }
                
                // Check if current user has assessed this exhibition
                $hasAssessed = false;
                if ($form->penilaian_per_juri) {
                    foreach ($form->penilaian_per_juri as $penilaian) {
                        if ($penilaian['user_id'] == $currentUserId) {
                            $hasAssessed = true;
                            break;
                        }
                    }
                }
                
                if (!$hasAssessed) {
                    $allCompleted = false;
                    break;
                }
            }
            
            if ($allCompleted) {
                $completedSessions[] = $sessionName;
            }
        }
        
        return view('dashboard.pages.exhibition.produsen.index', compact('title', 'pageTitle', 'breadcrumbs', 'forms', 'term', 'sessions', 'completedSessions'));
    }
    
    public function show(string $respondentId)
    {
        $title = 'Detail Penilaian Exhibition Produsen DG';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Exhibition', 'url' => route('dashboard.exhibition.produsen.index')],
            ['name' => 'Produsen DG', 'url' => route('dashboard.exhibition.produsen.index')],
            ['name' => 'Detail', 'url' => null, 'active' => true]
        ];
        
        $form = ProdusenExhibition::where('respondent_id', $respondentId)->firstOrFail();
        
        // Get assessment history
        $assessmentHistory = RecordExhibitionAssesment::where('exhibition_type', 'produsen')
            ->where('exhibition_id', $form->id)
            ->latest()
            ->get();
        
        return view('dashboard.pages.exhibition.produsen.show', compact('title', 'pageTitle', 'breadcrumbs', 'form', 'assessmentHistory'));
    }
    
    public function edit(string $respondentId)
    {
        $title = 'Nilai Exhibition/Poster Produsen DG';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Exhibition', 'url' => route('dashboard.exhibition.produsen.index')],
            ['name' => 'Produsen DG', 'url' => route('dashboard.exhibition.produsen.index')],
            ['name' => 'Nilai Exhibition', 'url' => null, 'active' => true]
        ];
        
        $form = ProdusenExhibition::where('respondent_id', $respondentId)->firstOrFail();
        
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
        
        return view('dashboard.pages.exhibition.produsen.score', compact('title', 'pageTitle', 'breadcrumbs', 'form', 'userAssessment', 'rekomendasiOptions'));
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
            $form = ProdusenExhibition::where('respondent_id', $respondentId)->firstOrFail();
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
                    $penilaian['catatan'] = $data['catatan_juri'];
                    $penilaian['rekomendasi'] = $data['rekomendasi'];
                    $penilaian['assessed_at'] = now()->toDateTimeString();
                    $juriExists = true;
                    break;
                }
            }
            
            if (!$juriExists) {
                $penilaianPerJuri[] = [
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'nilai_akhir_user' => $totalNilai,
                    'catatan' => $data['catatan_juri'],
                    'rekomendasi' => $data['rekomendasi'],
                    'assessed_at' => now()->toDateTimeString(),
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
                    'exhibition_type' => 'produsen',
                    'exhibition_id' => $form->id,
                    'user_id' => $userId,
                ],
                [
                    'user_name' => $userName,
                    'nilai_akhir_user' => $totalNilai,
                    'catatan' => $data['catatan_juri'],
                    'rekomendasi' => $data['rekomendasi'],
                    'assessed_at' => now(),
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('dashboard.exhibition.produsen.show', $respondentId)
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
    
    /**
     * Show bulk score form for exhibition
     */
    public function bulkScoreForm(Request $request)
    {
        $title = 'Penilaian Kolektif Exhibition Produsen DG';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Exhibition', 'url' => route('dashboard.exhibition.produsen.index')],
            ['name' => 'Produsen DG', 'url' => route('dashboard.exhibition.produsen.index')],
            ['name' => 'Penilaian Kolektif', 'url' => null, 'active' => true]
        ];
        
        // Get selected IDs from query string
        $ids = $request->query('ids');
        if (!$ids) {
            return redirect()->route('dashboard.exhibition.produsen.index')
                ->withErrors(['error' => 'Tidak ada peserta yang dipilih']);
        }
        
        $selectedIds = explode(',', $ids);
        
        // Get forms data
        $forms = ProdusenExhibition::whereIn('respondent_id', $selectedIds)->get();
        
        if ($forms->isEmpty()) {
            return redirect()->route('dashboard.exhibition.produsen.index')
                ->withErrors(['error' => 'Data peserta tidak ditemukan']);
        }
        
        // Sort forms based on session order
        $sessionOrder = [];
        $sessionData = ProdusenPresentationSession::whereIn('respondent_id', $selectedIds)
            ->orderBy('session_name')
            ->orderBy('order')
            ->get();
        
        foreach ($sessionData as $index => $session) {
            $sessionOrder[$session->respondent_id] = $index;
        }
        
        // Sort forms collection based on session order
        $forms = $forms->sortBy(function($form) use ($sessionOrder) {
            return $sessionOrder[$form->respondent_id] ?? 999;
        })->values();
        
        // Get previous assessments for current user
        $userAssessments = [];
        foreach ($forms as $form) {
            $userAssessment = [
                'aspek_scores' => [],
                'catatan' => null,
                'rekomendasi' => null,
            ];
            
            // Get penilaian data
            if ($form->penilaian_per_juri) {
                foreach ($form->penilaian_per_juri as $penilaian) {
                    if ($penilaian['user_id'] == Auth::id()) {
                        $userAssessment['catatan'] = $penilaian['catatan'] ?? $penilaian['catatan_juri'] ?? null;
                        $userAssessment['rekomendasi'] = $penilaian['rekomendasi'] ?? null;
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
        
        // Define aspek penilaian for exhibition (same as individual form)
        $aspekPenilaian = [
            'kesesuaian_materi' => ['label' => 'Kesesuaian Materi dengan Kuesioner', 'bobot' => 30],
            'kejelasan_informasi' => ['label' => 'Kejelasan Informasi dan Struktur Penyajian', 'bobot' => 25],
            'kualitas_visual' => ['label' => 'Kualitas Visual dan Desain Grafis', 'bobot' => 20],
            'inovasi_kreativitas' => ['label' => 'Inovasi dan Kreativitas Penyajian', 'bobot' => 15],
            'relevansi_tema' => ['label' => 'Relevansi dengan Tema dan Tujuan SIGAP Award 2025', 'bobot' => 10],
        ];
        
        return view('dashboard.pages.exhibition.produsen.collective_score', compact(
            'title', 'pageTitle', 'breadcrumbs',
            'forms', 'userAssessments', 'aspekPenilaian'
        ));
    }
    
    /**
     * Store bulk scores for exhibition
     */
    public function bulkScoreStore(Request $request)
    {
        $participants = $request->input('participants', []);
        
        if (empty($participants)) {
            return redirect()->back()->withErrors(['error' => 'Tidak ada data penilaian']);
        }
        
        DB::beginTransaction();
        try {
            $userId = Auth::id();
            $userName = Auth::user()->name;
            
            foreach ($participants as $respondentId => $scores) {
                $form = ProdusenExhibition::where('respondent_id', $respondentId)->firstOrFail();
                
                // Calculate weighted score (same as individual)
                $totalNilai = 
                    ($scores['kesesuaian_materi'] * 0.30) +
                    ($scores['kejelasan_informasi'] * 0.25) +
                    ($scores['kualitas_visual'] * 0.20) +
                    ($scores['inovasi_kreativitas'] * 0.15) +
                    ($scores['relevansi_tema'] * 0.10);
                
                // Store aspek scores (same structure as individual)
                $aspekScores = $form->aspek_penilaian ?? [];
                $aspekScores[$userId] = [
                    'kesesuaian_materi' => $scores['kesesuaian_materi'],
                    'kejelasan_informasi' => $scores['kejelasan_informasi'],
                    'kualitas_visual' => $scores['kualitas_visual'],
                    'inovasi_kreativitas' => $scores['inovasi_kreativitas'],
                    'relevansi_tema' => $scores['relevansi_tema'],
                ];
                $form->aspek_penilaian = $aspekScores;
                
                // Update or add penilaian_per_juri
                $penilaianPerJuri = $form->penilaian_per_juri ?? [];
                $found = false;
                foreach ($penilaianPerJuri as $key => $penilaian) {
                    if ($penilaian['user_id'] == $userId) {
                        $penilaianPerJuri[$key] = [
                            'user_id' => $userId,
                            'user_name' => $userName,
                            'nilai_akhir_user' => $totalNilai,
                            'catatan' => $scores['catatan_juri'] ?? null,
                            'rekomendasi' => $scores['rekomendasi'] ?? null,
                            'assessed_at' => now(),
                        ];
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $penilaianPerJuri[] = [
                        'user_id' => $userId,
                        'user_name' => $userName,
                        'nilai_akhir_user' => $totalNilai,
                        'catatan' => $scores['catatan_juri'] ?? null,
                        'rekomendasi' => $scores['rekomendasi'] ?? null,
                        'assessed_at' => now(),
                    ];
                }
                
                $form->penilaian_per_juri = $penilaianPerJuri;
                $form->calculateNilaiFinal();
                $form->save();
                
                // Record assessment
                RecordExhibitionAssesment::create([
                    'exhibition_type' => 'produsen',
                    'exhibition_id' => $form->id,
                    'user_id' => $userId,
                    'user_name' => $userName,
                    'nilai_akhir_user' => $totalNilai,
                    'catatan' => $scores['catatan_juri'] ?? null,
                    'rekomendasi' => $scores['rekomendasi'] ?? null,
                    'assessed_at' => now(),
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('dashboard.exhibition.produsen.index')
                ->with('success', 'Penilaian kolektif exhibition berhasil disimpan untuk ' . count($participants) . ' peserta!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menyimpan penilaian: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    /**
     * Export exhibition assessment data
     */
    public function export(Request $request, string $respondentId)
    {
        $format = $request->query('format', 'excel'); // excel, pdf
        $type = $request->query('type', 'summary'); // summary, detail
        
        $form = ProdusenExhibition::where('respondent_id', $respondentId)->firstOrFail();
        
        $fileName = 'Exhibition_Produsen_' . $form->nama_instansi . '_' . date('Y-m-d');
        
        if ($format === 'excel') {
            return $this->exportExcel($form, $type, $fileName);
        } elseif ($format === 'pdf') {
            return $this->exportPdf($form, $type, $fileName);
        }
        
        return back()->withErrors(['error' => 'Format tidak valid']);
    }
    
    /**
     * Export to CSV
     */
    protected function exportCsv($form, $type, $fileName)
    {
        $data = $this->prepareExportData($form, $type);
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '.csv"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write headers
            fputcsv($file, array_keys($data[0]));
            
            // Write data
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
    
    /**
     * Export to Excel
     */
    protected function exportExcel($form, $type, $fileName)
    {
        $data = $this->prepareExportData($form, $type);
        
        return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            protected $data;
            
            public function __construct($data)
            {
                $this->data = $data;
            }
            
            public function array(): array
            {
                return array_map(function($row) {
                    return array_values($row);
                }, $this->data);
            }
            
            public function headings(): array
            {
                return array_keys($this->data[0]);
            }
        }, $fileName . '.xlsx');
    }
    
    /**
     * Export to PDF
     */
    protected function exportPdf($form, $type, $fileName)
    {
        $data = $this->prepareExportData($form, $type);
        
        $pdf = Pdf::loadView('dashboard.pages.exhibition.exports.pdf', [
            'form' => $form,
            'data' => $data,
            'type' => $type,
            'title' => 'Penilaian Exhibition Produsen DG - ' . $form->nama_instansi
        ]);
        
        return $pdf->download($fileName . '.pdf');
    }
    
    /**
     * Prepare data for export
     */
    protected function prepareExportData($form, $type)
    {
        $data = [];
        
        if ($type === 'summary') {
            // Summary export - one row per jury
            foreach ($form->penilaian_per_juri ?? [] as $penilaian) {
                $data[] = [
                    'Nama Instansi' => $form->nama_instansi,
                    'Penanggung Jawab' => $form->penanggung_jawab,
                    'Nama Juri' => $penilaian['user_name'] ?? 'N/A',
                    'Nilai Akhir Juri' => number_format($penilaian['nilai_akhir_user'] ?? 0, 2),
                    'Rekomendasi' => $penilaian['rekomendasi'] ?? '-',
                    'Catatan' => $penilaian['catatan'] ?? $penilaian['catatan_juri'] ?? '-',
                    'Waktu Penilaian' => isset($penilaian['assessed_at']) ? \Carbon\Carbon::parse($penilaian['assessed_at'])->format('d/m/Y H:i') : '-',
                    'Nilai Final' => number_format($form->nilai_final ?? 0, 2),
                    'Bobot Exhibition' => $form->bobot_exhibition . '%',
                    'Nilai Final Dengan Bobot' => number_format($form->nilai_final_dengan_bobot ?? 0, 2),
                    'Kategori' => $form->kategori_penilaian ?? '-',
                ];
            }
        } else {
            // Detail export - one row per jury with aspect scores
            foreach ($form->penilaian_per_juri ?? [] as $penilaian) {
                $userId = $penilaian['user_id'];
                $aspekScores = $form->aspek_penilaian[$userId] ?? [];
                
                $data[] = [
                    'Nama Instansi' => $form->nama_instansi,
                    'Penanggung Jawab' => $form->penanggung_jawab,
                    'Nama Juri' => $penilaian['user_name'] ?? 'N/A',
                    'Kesesuaian Materi (30%)' => $aspekScores['kesesuaian_materi'] ?? '-',
                    'Kejelasan Informasi (25%)' => $aspekScores['kejelasan_informasi'] ?? '-',
                    'Kualitas Visual (20%)' => $aspekScores['kualitas_visual'] ?? '-',
                    'Inovasi Kreativitas (15%)' => $aspekScores['inovasi_kreativitas'] ?? '-',
                    'Relevansi Tema (10%)' => $aspekScores['relevansi_tema'] ?? '-',
                    'Nilai Akhir Juri' => number_format($penilaian['nilai_akhir_user'] ?? 0, 2),
                    'Rekomendasi' => $penilaian['rekomendasi'] ?? '-',
                    'Catatan' => $penilaian['catatan'] ?? $penilaian['catatan_juri'] ?? '-',
                    'Waktu Penilaian' => isset($penilaian['assessed_at']) ? \Carbon\Carbon::parse($penilaian['assessed_at'])->format('d/m/Y H:i') : '-',
                    'Nilai Final' => number_format($form->nilai_final ?? 0, 2),
                    'Bobot Exhibition' => $form->bobot_exhibition . '%',
                    'Nilai Final Dengan Bobot' => number_format($form->nilai_final_dengan_bobot ?? 0, 2),
                    'Kategori' => $form->kategori_penilaian ?? '-',
                ];
            }
        }
        
        // If no jury data, add basic info
        if (empty($data)) {
            $data[] = [
                'Nama Instansi' => $form->nama_instansi,
                'Penanggung Jawab' => $form->penanggung_jawab,
                'Status' => 'Belum ada penilaian',
            ];
        }
        
        return $data;
    }
}
