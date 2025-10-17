<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpkhForm;
use App\Models\RecordUserAssesment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class BpkhFormController extends Controller
{
    public function index(Request $request){
        $title='Hasil Form BPKH'; $pageTitle=$title;
       $breadcrumbs=[['name'=>'Penilaian Form','url'=>null],['name'=>'BPKH','url'=>null,'active'=>true]];
        $term=$request->string('q')->toString();
        $forms=BpkhForm::query()
            ->search($term)
            ->orderBy('sheet_row_number','asc')
            ->orderBy('respondent_id','asc')
            ->get();
        $statusLabels = [
            'pending'   => 'Pending',
            'in_review' => 'Dalam Review',
            'scored'    => 'Sudah Final',
        ];
        $forms->transform(function ($form) use ($statusLabels) {
            $form->status_label = $statusLabels[$form->status_nilai] ?? $form->status_nilai;
            return $form;
        });
        return view('dashboard.pages.form.bpkh.index', compact('title','pageTitle','breadcrumbs','forms','term'));
      }


      public function show(string $respondentId){
        $title='Detail Respon BPKH'; $pageTitle=$title;
        $breadcrumbs=[['name'=>'Penilaian Form','url'=>route('dashboard.form.bpkh.index')],['name'=>'BPKH','url'=>route('dashboard.form.bpkh.index')],['name'=>'Detail','url'=>null,'active'=>true]];
        $form=BpkhForm::where('respondent_id',$respondentId)->firstOrFail();
        $statusLabels = [
            'pending'   => 'Pending',
            'in_review' => 'Dalam Review',
            'scored'    => 'Sudah Final',
        ];
        $form->status_label = $statusLabels[$form->status_nilai] ?? $form->status_nilai;
        $spLabels = [
            1 => 'Tata Kelola dan Institusi',
            2 => 'Kebijakan dan Hukum',
            3 => 'Finansial',
            4 => 'Data',
            5 => 'Inovasi',
            6 => 'Standard',
            7 => 'Kemitraan',
            8 => 'Kapasitas & Pendidikan',
            9 => 'Komunikasi & Keterlibatan',
        ];
        
        // Get latest assessor from record table
        $latestAssessment = RecordUserAssesment::byForm('bpkh', $respondentId)
            ->latest()
            ->first();
        
        return view('dashboard.pages.form.bpkh.show', compact('title','pageTitle','breadcrumbs','form','spLabels','latestAssessment'));
      }



      public function editScore(string $respondentId){
        $title='Nilai Ulang Form BPKH'; $pageTitle=$title;
        $breadcrumbs=[['name'=>'Penilaian Form','url'=>route('dashboard.form.bpkh.index')],['name'=>'BPKH','url'=>route('dashboard.form.bpkh.index')],['name'=>'Nilai Ulang Form','url'=>null,'active'=>true]];
        $form=BpkhForm::where('respondent_id',$respondentId)->firstOrFail();
        $statusLabels = [
            'pending'   => 'Pending',
            'in_review' => 'Dalam Review',
            'scored'    => 'Sudah Final',
        ];
        return view('dashboard.pages.form.bpkh.score', compact('title','pageTitle','breadcrumbs','form','statusLabels'));
      }


      public function updateScore(Request $request,string $respondentId){
        $data=$request->validate([
          'status_nilai'=>['required',Rule::in(['pending','in_review','scored'])],
          'total_score'=>['nullable','integer','min:0','max:100'],
          'notes'=>['nullable','string','max:5000'],
          'answers'=>['array'],
          'answers.*'=>['nullable','numeric','min:0','max:100'],
        ]);
        $form=BpkhForm::where('respondent_id',$respondentId)->firstOrFail();
        // Update meta answers (support both formats)
        $meta = $form->meta ?? [];
        if (!empty($data['answers'])) {
            if (is_array($meta) && isset($meta[0]) && is_array($meta[0]) && array_key_exists('key',$meta[0])) {
                // ordered format
                foreach ($meta as &$item) {
                    if (preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) ($item['key'] ?? ''), $m)) {
                        $code = $m[1];
                        if (array_key_exists($code, $data['answers'])) {
                            $item['value'] = (string) ($data['answers'][$code] ?? '');
                        }
                    }
                }
                unset($item);
            } elseif (is_array($meta)) {
                // associative format
                foreach ($data['answers'] as $code => $val) {
                    $key = 'soal '.$code;
                    if (array_key_exists($key, $meta)) {
                        $meta[$key] = (string) $val;
                    }
                }
            }
        }

        $updatePayload = [
            'status_nilai' => $data['status_nilai'],
            'total_score' => $data['total_score'] ?? null,
            'notes' => $data['notes'] ?? null,
            'juri_penilai' => Auth::user()->name ?? null,
            'meta' => $meta,
        ];

        // Track meta changes (jawaban soal yang di-edit)
        $metaChanges = [];
        if (!empty($data['answers'])) {
            $oldMeta = $form->meta ?? [];
            foreach ($data['answers'] as $code => $newValue) {
                $oldValue = null;
                
                // Get old value from meta
                if (is_array($oldMeta) && isset($oldMeta[0]) && is_array($oldMeta[0]) && array_key_exists('key', $oldMeta[0])) {
                    // Ordered format
                    foreach ($oldMeta as $item) {
                        if (preg_match('/^\s*soal\s+([0-9]+(?:\.[0-9]+)*)/i', (string) ($item['key'] ?? ''), $m)) {
                            if ($m[1] == $code) {
                                $oldValue = $item['value'] ?? '';
                                break;
                            }
                        }
                    }
                } elseif (is_array($oldMeta)) {
                    // Associative format
                    $key = 'soal '.$code;
                    $oldValue = $oldMeta[$key] ?? '';
                }
                
                // Track if changed
                if ((string)$oldValue !== (string)$newValue) {
                    $metaChanges[$code] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }
        
        $form->update($updatePayload);
        
        // Create assessment record with total_score and meta_changes
        RecordUserAssesment::create([
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'user_role' => Auth::user()->role ?? 'juri',
            'form_type' => 'bpkh',
            'respondent_id' => $respondentId,
            'form_name' => $form->nama_bpkh,
            'action_type' => 'scoring',
            'total_score' => $data['total_score'] ?? null,
            'meta_changes' => !empty($metaChanges) ? $metaChanges : null,
            'notes' => $data['notes'] ?? null,
        ]);
        
        return redirect()->route('dashboard.form.bpkh.show', $respondentId)->with('success','✅ Penilaian berhasil disimpan! Status dan nilai telah diperbarui.');
      }
      
      // Get assessment history
      public function getAssessmentHistory(string $respondentId)
      {
          $records = RecordUserAssesment::byForm('bpkh', $respondentId)
              ->latest()
              ->get();
          
          return response()->json([
              'success' => true,
              'data' => $records
          ]);
      }
}
