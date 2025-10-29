<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use App\Models\LaunchDate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Konfigurasi launch date dari Controller
        $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);
        $launchFinish = Carbon::create(2025, 11, 20, 0, 0, 0);

        // Get active launch date from database (dynamic)
        $launchDate = LaunchDate::getActiveLaunchDate();
        
        // Fallback to default if no active launch date
        $rangeDate = $launchDate ? $launchDate->is_range_date : false;
        $rangeDateStart = $launchDate && $launchDate->is_range_date ? $launchDate->start_date : Carbon::create(2025, 10, 23, 0, 0, 0);
        $rangeDateEnd = $launchDate && $launchDate->is_range_date ? $launchDate->end_date : Carbon::create(2025, 10, 24, 0, 0, 0);
        $singleDate = $launchDate && !$launchDate->is_range_date ? $launchDate->single_date : null;

        // Load team data dari JSON
        $teamDataPath = public_path('sigap-assets/static/team-data.json');
        $teamData = json_decode(file_get_contents($teamDataPath), true);

        // Load journal data dari JSON
        $journalDataPath = public_path('sigap-assets/static/journal-data.json');
        $journalData = json_decode(file_get_contents($journalDataPath), true);

        return view('landing.pages.home.index', compact('launchStart', 'launchFinish', 'teamData', 'journalData', 'rangeDate', 'rangeDateStart', 'rangeDateEnd', 'singleDate', 'launchDate'));
    }

    public function voteMenu(Request $request)
    {

        return view('landing.pages.votes.index');
    }


    public function thanksForSubmit()
    {
         // Konfigurasi launch date dari Controller
         $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);
         $launchFinish = Carbon::create(2025, 10, 16, 0, 0, 0);
        return view('landing.pages.home.thanks-for-submit', compact('launchStart', 'launchFinish'));
    }

    public function announcement()
    {
        // Data pengumuman peserta yang lolos ke tahap presentasi
        $announcements = [
            'Produsen Data Geospasial' => [
                'Direktorat Penggunaan Kawasan Hutan',
                'Direktorat Bina Usaha Pemanfaatan Hutan',
                'Direktorat Perencanaan dan Evaluasi Pengelolaan Daerah Aliran Sungai',
                'Direktorat Rehabilitasi Mangrove',
                'Direktorat Pengendalian Kebakaran Hutan',
                'Direktorat Penyiapan Kawasan Perhutanan Sosial',
            ],
            'Balai Pemantapan Kawasan Hutan' => [
                'BPKH Wilayah I Medan',
                'BPKH Wilayah III Pontianak',
                'BPKH Wilayah V Banjarbaru',
                'BPKH Wilayah VII Makassar',
                'BPKH Wilayah VIII Denpasar',
                'BPKH Wilayah IX Ambon',
                'BPKH Wilayah XI Yogyakarta',
                'BPKH Wilayah XII Tanjung Pinang',
                'BPKH Wilayah XVII Manokwari',
                'BPKH Wilayah XVIII Banda Aceh',
                'BPKH Wilayah XX Bandar Lampung',
                'BPKH Wilayah XXI Palangkaraya',
            ],
        ];

        // Konfigurasi launch date dari Controller
        $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);
        $launchFinish = Carbon::create(2025, 10, 17, 0, 0, 0);

        return view('landing.pages.home.announcement', compact('announcements', 'launchStart', 'launchFinish'));
    }

    public function resultPresentation()
    {
        // Opsional: tetap konsisten dengan halaman lain yang pakai launch date
        $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);
        $launchFinish = Carbon::create(2025, 10, 17, 0, 0, 0);
        return view('landing.pages.home.result-presentation', compact('launchStart', 'launchFinish'));
    }

    public function cvJuri()
    {
        // Konfigurasi launch date dari Controller
        $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);
        $launchFinish = Carbon::create(2025, 10, 17, 0, 0, 0);

        // Path ke PDF file
        $pdfPath = 'sigap-assets/pdf/CV Juri SIGAP AWARD 2025.pdf';

        return view('landing.pages.home.cv-juri', compact('launchStart', 'launchFinish', 'pdfPath'));
    }

    public function posterCriteria()
    {
        // Konfigurasi launch date dari Controller
        $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);
        $launchFinish = Carbon::create(2025, 10, 17, 0, 0, 0);

        // Path ke PDF file
        $pdfPath = 'sigap-assets/pdf/Kriteria Poster SIGAP Award 2025.pdf';

        return view('landing.pages.home.poster-criteria', compact('launchStart', 'launchFinish', 'pdfPath'));
    }
}
