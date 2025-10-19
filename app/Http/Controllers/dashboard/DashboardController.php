<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BpkhForm;
use App\Models\ProdusenForm;
use App\Models\BpkhPresentationAssesment;
use App\Models\ProdusenPresentationAssesment;
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

        // Count presentasi yang sudah dinilai (nilai_final_dengan_bobot tidak null)
        $countPresentasiBpkh = BpkhPresentationAssesment::whereNotNull('nilai_final_dengan_bobot')->count();
        $countPresentasiProdusen = ProdusenPresentationAssesment::whereNotNull('nilai_final_dengan_bobot')->count();

        // Get chart data - Top forms by nilai_bobot_total
        $chartBpkh = BpkhForm::whereNotNull('nilai_bobot_total')
            ->orderBy('nilai_bobot_total', 'desc')
            ->get()
            ->map(function ($form) {
                return [
                    'label' => $form->nama_bpkh,
                    'value' => $form->nilai_bobot_total ?? 0
                ];
            });

        $chartProdusen = ProdusenForm::whereNotNull('nilai_bobot_total')
            ->orderBy('nilai_bobot_total', 'desc')
            ->get()
            ->map(function ($form) {
                return [
                    'label' => $form->nama_instansi,
                    'value' => $form->nilai_bobot_total ?? 0
                ];
            });

        // Get bobot percentage for chart labels
        $bobotBpkh = BpkhForm::whereNotNull('bobot')->value('bobot') ?? 45;
        $bobotProdusen = ProdusenForm::whereNotNull('bobot')->value('bobot') ?? 45;

        // Add role display
        $user = Auth::user();
        $roleDisplay = $this->getRoleDisplay($user->role);

        return view('dashboard.pages.dashboard', compact(
            'title',
            'countBpkh',
            'countProdusen',
            'lastSyncBpkhText',
            'lastSyncProdusenText',
            'countPresentasiBpkh',
            'countPresentasiProdusen',
            'chartBpkh',
            'chartProdusen',
            'bobotBpkh',
            'bobotProdusen',
            'roleDisplay'
        ));
    }

    /**
     * Get display name for role
     */
    protected function getRoleDisplay($role)
    {
        return match ($role) {
            'panitia' => 'Juri',
            'peserta' => 'Peserta',
            'admin' => 'Admin',
            'superadmin' => 'Superadmin',
            default => ucfirst($role),
        };
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
