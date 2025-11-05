<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpkhForm;
use App\Models\ProdusenForm;
use App\Models\RecordUserAssesment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilPenilaianController extends Controller
{
    public function index()
    {
        $title = 'Hasil Penilaian Final';
        
        // Get BPKH Final Scores
        // Formula: Nilai Form (bobot 45%) + Nilai Presentasi (bobot 35%) + Nilai Exhibition (bobot 20%)
        // Show all nominees with judge count info
        $hasilBpkh = BpkhForm::select(
            'bpkh_forms.respondent_id',
            'bpkh_forms.nama_bpkh',
            'bpkh_forms.petugas_bpkh',
            'bpkh_forms.nilai_bobot_total as nilai_form',
            DB::raw('COALESCE((
                SELECT AVG(nilai_final_dengan_bobot)
                FROM bpkh_presentasi_assesment
                WHERE bpkh_presentasi_assesment.respondent_id = bpkh_forms.respondent_id
            ), 0) as nilai_presentasi'),
            DB::raw('COALESCE((
                SELECT AVG(nilai_final_dengan_bobot)
                FROM bpkh_exhibitions
                WHERE bpkh_exhibitions.respondent_id = bpkh_forms.respondent_id
            ), 0) as nilai_exhibition'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_presentasi_assesment
                WHERE record_presentasi_assesment.respondent_id = bpkh_forms.respondent_id
                AND record_presentasi_assesment.form_type = "bpkh"
            ) as total_juri_presentasi'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_exhibition_assesments rea
                JOIN bpkh_exhibitions be ON rea.exhibition_id = be.id
                WHERE be.respondent_id = bpkh_forms.respondent_id
                AND rea.exhibition_type = "bpkh"
            ) as total_juri_exhibition')
        )
        ->where('nominasi', true)
        ->get()
        ->map(function ($item) {
            // Calculate final score (judge counts already from query)
            $nilaiForm = $item->nilai_form ?? 0;
            $nilaiPresentasi = $item->nilai_presentasi ?? 0;
            $nilaiExhibition = $item->nilai_exhibition ?? 0;
            
            $item->nilai_final = $nilaiForm + $nilaiPresentasi + $nilaiExhibition;
            return $item;
        })
        ->sortByDesc('nilai_final')
        ->values();

        // Get Produsen Final Scores
        // Show all nominees with judge count info
        $hasilProdusen = ProdusenForm::select(
            'produsen_forms.respondent_id',
            'produsen_forms.nama_instansi',
            'produsen_forms.nama_petugas as petugas_produsen',
            'produsen_forms.nilai_bobot_total as nilai_form',
            DB::raw('COALESCE((
                SELECT AVG(nilai_final_dengan_bobot)
                FROM produsen_presentasi_assesment
                WHERE produsen_presentasi_assesment.respondent_id = produsen_forms.respondent_id
            ), 0) as nilai_presentasi'),
            DB::raw('COALESCE((
                SELECT AVG(nilai_final_dengan_bobot)
                FROM produsen_exhibitions
                WHERE produsen_exhibitions.respondent_id = produsen_forms.respondent_id
            ), 0) as nilai_exhibition'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_presentasi_assesment
                WHERE record_presentasi_assesment.respondent_id = produsen_forms.respondent_id
                AND record_presentasi_assesment.form_type = "produsen"
            ) as total_juri_presentasi'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_exhibition_assesments rea
                JOIN produsen_exhibitions pe ON rea.exhibition_id = pe.id
                WHERE pe.respondent_id = produsen_forms.respondent_id
                AND rea.exhibition_type = "produsen"
            ) as total_juri_exhibition')
        )
        ->where('nominasi', true)
        ->get()
        ->map(function ($item) {
            // Calculate final score (judge counts already from query)
            $nilaiForm = $item->nilai_form ?? 0;
            $nilaiPresentasi = $item->nilai_presentasi ?? 0;
            $nilaiExhibition = $item->nilai_exhibition ?? 0;
            
            $item->nilai_final = $nilaiForm + $nilaiPresentasi + $nilaiExhibition;
            return $item;
        })
        ->sortByDesc('nilai_final')
        ->values();

        return view('dashboard.pages.hasil.index', compact('title', 'hasilBpkh', 'hasilProdusen'));
    }
    
    /**
     * Poster/Exhibition Final Results (Combined BPKH & Produsen)
     */
    public function posterIndex()
    {
        $title = 'Hasil Penilaian Final Poster/Exhibition';
        
        // Get BPKH Exhibition Scores (ALL participants)
        $hasilBpkh = BpkhForm::select(
            'bpkh_forms.respondent_id',
            'bpkh_forms.nama_bpkh as nama',
            'bpkh_forms.petugas_bpkh as petugas',
            DB::raw('"BPKH" as kategori'),
            DB::raw('COALESCE((
                SELECT nilai_final_dengan_bobot
                FROM bpkh_exhibitions
                WHERE bpkh_exhibitions.respondent_id = bpkh_forms.respondent_id
            ), 0) as nilai_exhibition'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_exhibition_assesments rea
                JOIN bpkh_exhibitions be ON rea.exhibition_id = be.id
                WHERE be.respondent_id = bpkh_forms.respondent_id
                AND rea.exhibition_type = "bpkh"
            ) as total_juri_exhibition')
        )
        ->get();
        
        // Get Produsen Exhibition Scores (ALL participants)
        $hasilProdusen = ProdusenForm::select(
            'produsen_forms.respondent_id',
            'produsen_forms.nama_instansi as nama',
            'produsen_forms.nama_petugas as petugas',
            DB::raw('"Produsen" as kategori'),
            DB::raw('COALESCE((
                SELECT nilai_final_dengan_bobot
                FROM produsen_exhibitions
                WHERE produsen_exhibitions.respondent_id = produsen_forms.respondent_id
            ), 0) as nilai_exhibition'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_exhibition_assesments rea
                JOIN produsen_exhibitions pe ON rea.exhibition_id = pe.id
                WHERE pe.respondent_id = produsen_forms.respondent_id
                AND rea.exhibition_type = "produsen"
            ) as total_juri_exhibition')
        )
        ->get();
        
        // Merge and sort by exhibition score
        $hasilPoster = $hasilBpkh->concat($hasilProdusen)
            ->sortByDesc('nilai_exhibition')
            ->values();
        
        return view('dashboard.pages.hasil.poster_index', compact('title', 'hasilPoster'));
    }
    
    /**
     * Export Poster/Exhibition results
     */
    public function exportPoster(Request $request)
    {
        $format = $request->query('format', 'excel'); // excel, pdf
        
        // Get BPKH Exhibition Scores (ALL participants)
        $hasilBpkh = BpkhForm::select(
            'bpkh_forms.respondent_id',
            'bpkh_forms.nama_bpkh as nama',
            'bpkh_forms.petugas_bpkh as petugas',
            DB::raw('"BPKH" as kategori'),
            DB::raw('COALESCE((
                SELECT nilai_final_dengan_bobot
                FROM bpkh_exhibitions
                WHERE bpkh_exhibitions.respondent_id = bpkh_forms.respondent_id
            ), 0) as nilai_exhibition'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_exhibition_assesments rea
                JOIN bpkh_exhibitions be ON rea.exhibition_id = be.id
                WHERE be.respondent_id = bpkh_forms.respondent_id
                AND rea.exhibition_type = "bpkh"
            ) as total_juri_exhibition')
        )
        ->get();
        
        // Get Produsen Exhibition Scores (ALL participants)
        $hasilProdusen = ProdusenForm::select(
            'produsen_forms.respondent_id',
            'produsen_forms.nama_instansi as nama',
            'produsen_forms.nama_petugas as petugas',
            DB::raw('"Produsen" as kategori'),
            DB::raw('COALESCE((
                SELECT nilai_final_dengan_bobot
                FROM produsen_exhibitions
                WHERE produsen_exhibitions.respondent_id = produsen_forms.respondent_id
            ), 0) as nilai_exhibition'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_exhibition_assesments rea
                JOIN produsen_exhibitions pe ON rea.exhibition_id = pe.id
                WHERE pe.respondent_id = produsen_forms.respondent_id
                AND rea.exhibition_type = "produsen"
            ) as total_juri_exhibition')
        )
        ->get();
        
        // Merge and sort
        $hasilPoster = $hasilBpkh->concat($hasilProdusen)
            ->sortByDesc('nilai_exhibition')
            ->values();
        
        // Prepare data for export
        $data = [];
        foreach ($hasilPoster as $index => $item) {
            $data[] = [
                'Rank' => $index + 1,
                'Kategori' => $item->kategori,
                'Nama' => $item->nama,
                'Petugas' => $item->petugas,
                'Nilai Exhibition' => number_format($item->nilai_exhibition, 2),
                'Juri Penilai Exhibition' => $item->total_juri_exhibition ?? 0,
            ];
        }
        
        if (empty($data)) {
            return back()->withErrors(['error' => 'Tidak ada data untuk diekspor']);
        }
        
        $fileName = 'Hasil_Poster_Exhibition_' . date('Y-m-d');
        
        if ($format === 'excel') {
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
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('dashboard.pages.hasil.exports.pdf-poster', [
                'data' => $data,
                'title' => 'Hasil Penilaian Final Poster/Exhibition'
            ])->setPaper('a4', 'portrait');
            
            return $pdf->download($fileName . '.pdf');
        }
        
        return back()->withErrors(['error' => 'Format tidak valid']);
    }
    
    /**
     * Export BPKH final results
     */
    public function exportBpkh(Request $request)
    {
        $format = $request->query('format', 'excel'); // excel, pdf
        
        // Get BPKH Final Scores (same query as index)
        $hasilBpkh = BpkhForm::select(
            'bpkh_forms.respondent_id',
            'bpkh_forms.nama_bpkh',
            'bpkh_forms.petugas_bpkh',
            'bpkh_forms.nilai_bobot_total as nilai_form',
            DB::raw('COALESCE((
                SELECT AVG(nilai_final_dengan_bobot)
                FROM bpkh_presentasi_assesment
                WHERE bpkh_presentasi_assesment.respondent_id = bpkh_forms.respondent_id
            ), 0) as nilai_presentasi'),
            DB::raw('COALESCE((
                SELECT AVG(nilai_final_dengan_bobot)
                FROM bpkh_exhibitions
                WHERE bpkh_exhibitions.respondent_id = bpkh_forms.respondent_id
            ), 0) as nilai_exhibition'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_presentasi_assesment
                WHERE record_presentasi_assesment.respondent_id = bpkh_forms.respondent_id
                AND record_presentasi_assesment.form_type = "bpkh"
            ) as total_juri_presentasi'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_exhibition_assesments rea
                JOIN bpkh_exhibitions be ON rea.exhibition_id = be.id
                WHERE be.respondent_id = bpkh_forms.respondent_id
                AND rea.exhibition_type = "bpkh"
            ) as total_juri_exhibition')
        )
        ->where('nominasi', true)
        ->get()
        ->map(function ($item, $index) {
            $nilaiForm = $item->nilai_form ?? 0;
            $nilaiPresentasi = $item->nilai_presentasi ?? 0;
            $nilaiExhibition = $item->nilai_exhibition ?? 0;
            
            $item->nilai_final = $nilaiForm + $nilaiPresentasi + $nilaiExhibition;
            $item->rank = $index + 1;
            return $item;
        })
        ->sortByDesc('nilai_final')
        ->values()
        ->map(function ($item, $index) {
            $item->rank = $index + 1;
            return $item;
        });
        
        // Prepare data for export
        $data = [];
        foreach ($hasilBpkh as $item) {
            $data[] = [
                'Rank' => $item->rank,
                'Nama BPKH' => $item->nama_bpkh,
                'Petugas' => $item->petugas_bpkh,
                'Form (45%)' => number_format($item->nilai_form, 2),
                'Presentasi (35%)' => number_format($item->nilai_presentasi, 2),
                'Juri Penilai Presentasi' => $item->total_juri_presentasi ?? 0,
                'Exhibition (20%)' => number_format($item->nilai_exhibition, 2),
                'Juri Penilai Exhibition' => $item->total_juri_exhibition ?? 0,
                'Nilai Final' => number_format($item->nilai_final, 2),
            ];
        }
        
        if (empty($data)) {
            return back()->withErrors(['error' => 'Tidak ada data untuk diekspor']);
        }
        
        $fileName = 'Hasil_Final_BPKH_' . date('Y-m-d');
        
        if ($format === 'excel') {
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
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('dashboard.pages.hasil.exports.pdf', [
                'data' => $data,
                'title' => 'Hasil Penilaian Final BPKH'
            ])->setPaper('a4', 'landscape');
            
            return $pdf->download($fileName . '.pdf');
        }
        
        return back()->withErrors(['error' => 'Format tidak valid']);
    }
    
    /**
     * Export Produsen final results
     */
    public function exportProdusen(Request $request)
    {
        $format = $request->query('format', 'excel'); // excel, pdf
        
        // Get Produsen Final Scores (same query as index)
        $hasilProdusen = ProdusenForm::select(
            'produsen_forms.respondent_id',
            'produsen_forms.nama_instansi',
            'produsen_forms.nama_petugas as petugas_produsen',
            'produsen_forms.nilai_bobot_total as nilai_form',
            DB::raw('COALESCE((
                SELECT AVG(nilai_final_dengan_bobot)
                FROM produsen_presentasi_assesment
                WHERE produsen_presentasi_assesment.respondent_id = produsen_forms.respondent_id
            ), 0) as nilai_presentasi'),
            DB::raw('COALESCE((
                SELECT AVG(nilai_final_dengan_bobot)
                FROM produsen_exhibitions
                WHERE produsen_exhibitions.respondent_id = produsen_forms.respondent_id
            ), 0) as nilai_exhibition'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_presentasi_assesment
                WHERE record_presentasi_assesment.respondent_id = produsen_forms.respondent_id
                AND record_presentasi_assesment.form_type = "produsen"
            ) as total_juri_presentasi'),
            DB::raw('(
                SELECT COUNT(DISTINCT user_id)
                FROM record_exhibition_assesments rea
                JOIN produsen_exhibitions pe ON rea.exhibition_id = pe.id
                WHERE pe.respondent_id = produsen_forms.respondent_id
                AND rea.exhibition_type = "produsen"
            ) as total_juri_exhibition')
        )
        ->where('nominasi', true)
        ->get()
        ->map(function ($item, $index) {
            $nilaiForm = $item->nilai_form ?? 0;
            $nilaiPresentasi = $item->nilai_presentasi ?? 0;
            $nilaiExhibition = $item->nilai_exhibition ?? 0;
            
            $item->nilai_final = $nilaiForm + $nilaiPresentasi + $nilaiExhibition;
            $item->rank = $index + 1;
            return $item;
        })
        ->sortByDesc('nilai_final')
        ->values()
        ->map(function ($item, $index) {
            $item->rank = $index + 1;
            return $item;
        });
        
        // Prepare data for export
        $data = [];
        foreach ($hasilProdusen as $item) {
            $data[] = [
                'Rank' => $item->rank,
                'Nama Instansi' => $item->nama_instansi,
                'Petugas' => $item->petugas_produsen,
                'Form (45%)' => number_format($item->nilai_form, 2),
                'Presentasi (35%)' => number_format($item->nilai_presentasi, 2),
                'Juri Penilai Presentasi' => $item->total_juri_presentasi ?? 0,
                'Exhibition (20%)' => number_format($item->nilai_exhibition, 2),
                'Juri Penilai Exhibition' => $item->total_juri_exhibition ?? 0,
                'Nilai Final' => number_format($item->nilai_final, 2),
            ];
        }
        
        if (empty($data)) {
            return back()->withErrors(['error' => 'Tidak ada data untuk diekspor']);
        }
        
        $fileName = 'Hasil_Final_Produsen_' . date('Y-m-d');
        
        if ($format === 'excel') {
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
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('dashboard.pages.hasil.exports.pdf', [
                'data' => $data,
                'title' => 'Hasil Penilaian Final Produsen DG'
            ])->setPaper('a4', 'landscape');
            
            return $pdf->download($fileName . '.pdf');
        }
        
        return back()->withErrors(['error' => 'Format tidak valid']);
    }
}
