<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdusenForm;
use App\Models\BpkhForm;

class SyncFormController extends Controller
{


    public function index()
    {
        return view('dashboard.pages.form.sync.index');
    }

    public function syncFormProdusen()
    {
        $forms = ProdusenForm::all();
        foreach ($forms as $form) {
            $form->sync();
        }
    }

    public function syncFormBpkh()
    {
        $forms = BpkhForm::all();
        foreach ($forms as $form) {
            $form->sync();
        }
    }
}
