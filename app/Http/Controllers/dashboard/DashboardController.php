<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Beranda';
        return view('dashboard.pages.dashboard', compact('title'));
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
