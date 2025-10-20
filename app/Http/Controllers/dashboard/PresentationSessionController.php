<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpkhPresentationSession;
use App\Models\ProdusenPresentationSession;
use App\Models\BpkhPresentationAssesment;
use App\Models\ProdusenPresentationAssesment;

class PresentationSessionController extends Controller
{
    /**
     * Display session management page
     */
    public function index()
    {
        $title = 'Manajemen Sesi Presentasi';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard.index')],
            ['name' => 'Manajemen Sesi Presentasi', 'url' => null, 'active' => true]
        ];
        
        // Get all sessions grouped
        $bpkhSessions = BpkhPresentationSession::getGroupedSessions();
        $produsenSessions = ProdusenPresentationSession::getGroupedSessions();
        
        // Get available participants (not yet assigned to any session)
        $availableBpkh = BpkhPresentationAssesment::whereNotIn('respondent_id', 
            BpkhPresentationSession::where('is_active', true)->pluck('respondent_id')
        )->get();
        
        $availableProdusen = ProdusenPresentationAssesment::whereNotIn('respondent_id',
            ProdusenPresentationSession::where('is_active', true)->pluck('respondent_id')
        )->get();
        
        return view('dashboard.pages.presentation_session.index', compact(
            'title', 'pageTitle', 'breadcrumbs',
            'bpkhSessions', 'produsenSessions',
            'availableBpkh', 'availableProdusen'
        ));
    }
    
    /**
     * Store BPKH session participant
     */
    public function storeBpkh(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string',
            'respondent_id' => 'required|string',
        ]);
        
        // Get participant data
        $participant = BpkhPresentationAssesment::where('respondent_id', $request->respondent_id)->first();
        
        if (!$participant) {
            return back()->with('error', 'Data BPKH tidak ditemukan');
        }
        
        // Check if already exists
        $exists = BpkhPresentationSession::where('respondent_id', $request->respondent_id)
            ->where('is_active', true)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'BPKH sudah terdaftar di sesi lain');
        }
        
        // Get max order for this session
        $maxOrder = BpkhPresentationSession::where('session_name', $request->session_name)
            ->max('order') ?? 0;
        
        BpkhPresentationSession::create([
            'session_name' => $request->session_name,
            'respondent_id' => $request->respondent_id,
            'nama_bpkh' => $participant->nama_bpkh,
            'order' => $maxOrder + 1,
            'is_active' => true
        ]);
        
        return back()->with('success', 'Peserta berhasil ditambahkan ke ' . $request->session_name);
    }
    
    /**
     * Store Produsen session participant
     */
    public function storeProdusen(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string',
            'respondent_id' => 'required|string',
        ]);
        
        // Get participant data
        $participant = ProdusenPresentationAssesment::where('respondent_id', $request->respondent_id)->first();
        
        if (!$participant) {
            return back()->with('error', 'Data Produsen tidak ditemukan');
        }
        
        // Check if already exists
        $exists = ProdusenPresentationSession::where('respondent_id', $request->respondent_id)
            ->where('is_active', true)
            ->exists();
            
        if ($exists) {
            return back()->with('error', 'Produsen sudah terdaftar di sesi lain');
        }
        
        // Get max order for this session
        $maxOrder = ProdusenPresentationSession::where('session_name', $request->session_name)
            ->max('order') ?? 0;
        
        ProdusenPresentationSession::create([
            'session_name' => $request->session_name,
            'respondent_id' => $request->respondent_id,
            'nama_instansi' => $participant->nama_instansi,
            'order' => $maxOrder + 1,
            'is_active' => true
        ]);
        
        return back()->with('success', 'Peserta berhasil ditambahkan ke ' . $request->session_name);
    }
    
    /**
     * Delete BPKH session participant
     */
    public function destroyBpkh($id)
    {
        $session = BpkhPresentationSession::findOrFail($id);
        $session->delete();
        
        return back()->with('success', 'Peserta berhasil dihapus dari sesi');
    }
    
    /**
     * Delete Produsen session participant
     */
    public function destroyProdusen($id)
    {
        $session = ProdusenPresentationSession::findOrFail($id);
        $session->delete();
        
        return back()->with('success', 'Peserta berhasil dihapus dari sesi');
    }
    
    /**
     * Update order of participants
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bpkh,produsen',
            'items' => 'required|array'
        ]);
        
        $model = $request->type === 'bpkh' ? BpkhPresentationSession::class : ProdusenPresentationSession::class;
        
        foreach ($request->items as $item) {
            $model::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        
        return response()->json(['success' => true, 'message' => 'Urutan berhasil diupdate']);
    }
}
