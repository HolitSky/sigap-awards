<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\ProdusenForm;
use App\Models\BpkhForm;
use Carbon\Carbon;

class SyncFormController extends Controller
{
    public function index()
    {
        $title = 'Sync Form';
        $countBpkh = BpkhForm::count();
        $countProdusen = ProdusenForm::count();
        $lastSyncBpkh = BpkhForm::max('synced_at');
        $lastSyncProdusen = ProdusenForm::max('synced_at');
        $lastSyncBpkhText = $lastSyncBpkh ? Carbon::parse($lastSyncBpkh)->format('d M Y H:i') : null;
        $lastSyncProdusenText = $lastSyncProdusen ? Carbon::parse($lastSyncProdusen)->format('d M Y H:i') : null;

        return view('dashboard.pages.sync-form.index', compact(
            'title',
            'countBpkh',
            'countProdusen',
            'lastSyncBpkhText',
            'lastSyncProdusenText'
        ));
    }

    public function syncFormProdusen()
    {
        try {
            // Run artisan command
            Artisan::call('produsen:sync-sheets');
            
            // Get output
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Sync Produsen DG berhasil dilakukan!',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sync: ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncFormBpkh()
    {
        try {
            // Run artisan command
            Artisan::call('bpkh:sync-sheets');
            
            // Get output
            $output = Artisan::output();
            
            return response()->json([
                'success' => true,
                'message' => 'Sync BPKH berhasil dilakukan!',
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan sync: ' . $e->getMessage()
            ], 500);
        }
    }
}
