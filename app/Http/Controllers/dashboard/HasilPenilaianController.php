<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpkhForm;
use App\Models\ProdusenForm;
use App\Models\RecordUserAssesment;
use Illuminate\Support\Facades\DB;

class HasilPenilaianController extends Controller
{
    public function index()
    {
        $title = 'Hasil Penilaian Final';
        
        // Get BPKH Final Scores
        // Formula: Nilai Form (bobot 45%) + Nilai Presentasi (bobot 35%) + Nilai Exhibition (bobot 20%)
        // Only include nominees (nominasi = true) AND assessed by more than 2 judges in presentation AND exhibition
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
                SELECT COUNT(*)
                FROM bpkh_presentasi_assesment
                WHERE bpkh_presentasi_assesment.respondent_id = bpkh_forms.respondent_id
            ) as total_juri_presentasi'),
            DB::raw('(
                SELECT total_juri_menilai
                FROM bpkh_exhibitions
                WHERE bpkh_exhibitions.respondent_id = bpkh_forms.respondent_id
                LIMIT 1
            ) as total_juri_exhibition')
        )
        ->where('nominasi', true)
        ->get()
        ->filter(function ($item) {
            // Only include if assessed by more than 2 judges in BOTH presentation AND exhibition
            return $item->total_juri_presentasi > 2 && $item->total_juri_exhibition > 2;
        })
        ->map(function ($item) {
            // Calculate final score
            $nilaiForm = $item->nilai_form ?? 0;
            $nilaiPresentasi = $item->nilai_presentasi ?? 0;
            $nilaiExhibition = $item->nilai_exhibition ?? 0;
            
            $item->nilai_final = $nilaiForm + $nilaiPresentasi + $nilaiExhibition;
            return $item;
        })
        ->sortByDesc('nilai_final')
        ->values();

        // Get Produsen Final Scores
        // Only include nominees (nominasi = true) AND assessed by more than 2 judges in presentation AND exhibition
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
                SELECT COUNT(*)
                FROM produsen_presentasi_assesment
                WHERE produsen_presentasi_assesment.respondent_id = produsen_forms.respondent_id
            ) as total_juri_presentasi'),
            DB::raw('(
                SELECT total_juri_menilai
                FROM produsen_exhibitions
                WHERE produsen_exhibitions.respondent_id = produsen_forms.respondent_id
                LIMIT 1
            ) as total_juri_exhibition')
        )
        ->where('nominasi', true)
        ->get()
        ->filter(function ($item) {
            // Only include if assessed by more than 2 judges in BOTH presentation AND exhibition
            return $item->total_juri_presentasi > 2 && $item->total_juri_exhibition > 2;
        })
        ->map(function ($item) {
            // Calculate final score
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
}
