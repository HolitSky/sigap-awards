<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpkhForm;
use App\Models\ProdusenForm;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Beranda';
        $countBpkh = BpkhForm::count();
        $countProdusen = ProdusenForm::count();
        $lastSyncBpkh = BpkhForm::max('synced_at');
        $lastSyncProdusen = ProdusenForm::max('synced_at');
        $lastSyncBpkhText = $lastSyncBpkh ? Carbon::parse($lastSyncBpkh)->format('d M Y H:i') : null;
        $lastSyncProdusenText = $lastSyncProdusen ? Carbon::parse($lastSyncProdusen)->format('d M Y H:i') : null;

        return view('dashboard.pages.dashboard', compact(
            'title',
            'countBpkh',
            'countProdusen',
            'lastSyncBpkhText',
            'lastSyncProdusenText'
        ));
    }

    public function bpkh()
    {
        $title = 'Hasil Form BPKH';
        $pageTitle = 'Hasil Form BPKH';
        $breadcrumbs = [
            ['name' => 'Form', 'url' => null],
            ['name' => 'BPKH', 'url' => null, 'active' => true]
        ];

        return view('dashboard.pages.form.bpkh.index', compact('title', 'pageTitle', 'breadcrumbs'));
    }

    public function produsenDg()
    {
        $title = 'Hasil Form Produsen DG';
        $pageTitle = 'Hasil Form Produsen DG';
        $breadcrumbs = [
            ['name' => 'Form', 'url' => null],
            ['name' => 'Produsen DG', 'url' => null, 'active' => true]
        ];

        return view('dashboard.pages.form.produsen.index', compact('title', 'pageTitle', 'breadcrumbs'));
    }
}
