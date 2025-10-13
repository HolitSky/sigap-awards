<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpkhForm;
use Illuminate\Validation\Rule;


class BpkhFormController extends Controller
{
    public function index(Request $request){
        $title='Hasil Form BPKH'; $pageTitle=$title;
       $breadcrumbs=[['name'=>'Form','url'=>null],['name'=>'BPKH','url'=>null,'active'=>true]];
        $term=$request->string('q')->toString();
        $forms=BpkhForm::query()
            ->search($term)
            ->orderBy('sheet_row_number','asc')
            ->orderBy('respondent_id','asc')
            ->paginate(15)
            ->withQueryString();
        return view('dashboard.pages.form.bpkh.index', compact('title','pageTitle','breadcrumbs','forms','term'));
      }


      public function show(string $respondentId){
        $title='Detail Respon BPKH'; $pageTitle=$title;
        $breadcrumbs=[['name'=>'Form','url'=>route('dashboard.form.bpkh.index')],['name'=>'BPKH','url'=>route('dashboard.form.bpkh.index')],['name'=>'Detail','url'=>null,'active'=>true]];
        $form=BpkhForm::where('respondent_id',$respondentId)->firstOrFail();
        return view('dashboard.pages.form.bpkh.show', compact('title','pageTitle','breadcrumbs','form'));
      }



      public function editScore(string $respondentId){
        $title='Nilai Form BPKH'; $pageTitle=$title;
        $breadcrumbs=[['name'=>'Form','url'=>route('dashboard.form.bpkh.index')],['name'=>'BPKH','url'=>route('dashboard.form.bpkh.index')],['name'=>'Nilai','url'=>null,'active'=>true]];
        $form=BpkhForm::where('respondent_id',$respondentId)->firstOrFail();
        return view('dashboard.pages.form.bpkh.score', compact('title','pageTitle','breadcrumbs','form'));
      }


      public function updateScore(Request $request,string $respondentId){
        $data=$request->validate([
          'status_nilai'=>['required',Rule::in(['pending','in_review','scored'])],
          'total_score'=>['nullable','integer','min:0','max:100'],
          'notes'=>['nullable','string','max:5000'],
        ]);
        $form=BpkhForm::where('respondent_id',$respondentId)->firstOrFail();
        $form->update($data);
        return redirect()->route('dashboard.form.bpkh.index')->with('success','Status & nilai tersimpan.');
      }
}
