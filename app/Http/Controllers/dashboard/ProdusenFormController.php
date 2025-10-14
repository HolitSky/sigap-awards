<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdusenForm;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProdusenFormController extends Controller
{
    public function index(Request $request){
        $title='Hasil Form Produsen'; $pageTitle=$title;
       $breadcrumbs=[["name"=>"Penilaian Form","url"=>null],["name"=>"Produsen","url"=>null,"active"=>true]];
        $term=$request->string('q')->toString();
        $forms=ProdusenForm::query()
            ->search($term)
            ->orderBy('sheet_row_number','asc')
            ->orderBy('respondent_id','asc')
            ->paginate(15)
            ->withQueryString();
        $statusLabels = [
            'pending'   => 'Pending',
            'in_review' => 'Dalam Review',
            'scored'    => 'Sudah Final',
        ];
        $forms->getCollection()->transform(function ($form) use ($statusLabels) {
            $form->status_label = $statusLabels[$form->status_nilai] ?? $form->status_nilai;
            return $form;
        });
        return view('dashboard.pages.form.produsen.index', compact('title','pageTitle','breadcrumbs','forms','term'));
      }


      public function show(string $respondentId){
        $title='Detail Respon Produsen'; $pageTitle=$title;
        $breadcrumbs=[["name"=>"Penilaian Form","url"=>route('dashboard.form.produsen-dg.index')],["name"=>"Produsen","url"=>route('dashboard.form.produsen-dg.index')],["name"=>"Detail","url"=>null,"active"=>true]];
        $form=ProdusenForm::where('respondent_id',$respondentId)->firstOrFail();
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
        return view('dashboard.pages.form.produsen.show', compact('title','pageTitle','breadcrumbs','form','spLabels'));
      }



      public function editScore(string $respondentId){
        $title='Nilai Ulang Form Produsen'; $pageTitle=$title;
        $breadcrumbs=[["name"=>"Penilaian Form","url"=>route('dashboard.form.produsen-dg.index')],["name"=>"Produsen","url"=>route('dashboard.form.produsen-dg.index')],["name"=>"Nilai Ulang Form","url"=>null,"active"=>true]];
        $form=ProdusenForm::where('respondent_id',$respondentId)->firstOrFail();
        $statusLabels = [
            'pending'   => 'Pending',
            'in_review' => 'Dalam Review',
            'scored'    => 'Sudah Final',
        ];
        return view('dashboard.pages.form.produsen.score', compact('title','pageTitle','breadcrumbs','form','statusLabels'));
      }


      public function updateScore(Request $request,string $respondentId){
        $data=$request->validate([
          'status_nilai'=>['required',Rule::in(['pending','in_review','scored'])],
          'total_score'=>['nullable','integer','min:0','max:100'],
          'notes'=>['nullable','string','max:5000'],
          'answers'=>['array'],
          'answers.*'=>['nullable','numeric','min:0','max:100'],
        ]);
        $form=ProdusenForm::where('respondent_id',$respondentId)->firstOrFail();
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

        $form->update($updatePayload);
        return redirect()->route('dashboard.form.produsen-dg.show', $respondentId)->with('success','Status & nilai tersimpan.');
      }
}
