<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use App\Models\CardBox;
use App\Models\LaunchDate;
use App\Models\MenuChoice;
use App\Models\ModalInfo;
use App\Models\PemenangSigap;
use App\Models\BpkhPoster;
use App\Models\ProdusenPoster;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Get active launch date from database (dynamic)
        $launchDate = LaunchDate::getActiveLaunchDate();

        // Determine launch dates based on database or fallback
        if ($launchDate) {
            switch ($launchDate->date_type) {
                case 'range':
                    $rangeDate = true;
                    $rangeDateStart = $launchDate->start_date;
                    $rangeDateEnd = $launchDate->end_date;
                    $singleDate = null;
                    $launchFinish = $launchDate->end_date;
                    break;

                case 'single':
                    $rangeDate = false;
                    $rangeDateStart = null;
                    $rangeDateEnd = null;
                    $singleDate = $launchDate->single_date;
                    $launchFinish = $launchDate->single_date;
                    break;

                case 'month_only':
                    // Month only - use first day of the month for countdown
                    $rangeDate = false;
                    $rangeDateStart = null;
                    $rangeDateEnd = null;
                    $singleDate = null;
                    if ($launchDate->month_year) {
                        list($year, $month) = explode('-', $launchDate->month_year);
                        $launchFinish = Carbon::create($year, $month, 1, 0, 0, 0);
                    } else {
                        $launchFinish = Carbon::now();
                    }
                    break;

                case 'coming_soon':
                    // Coming soon - use a far future date
                    $rangeDate = false;
                    $rangeDateStart = null;
                    $rangeDateEnd = null;
                    $singleDate = null;
                    $launchFinish = Carbon::create(2099, 12, 31, 0, 0, 0);
                    break;

                default:
                    $rangeDate = false;
                    $rangeDateStart = null;
                    $rangeDateEnd = null;
                    $singleDate = $launchDate->single_date;
                    $launchFinish = $launchDate->single_date ?? Carbon::now();
            }
        } else {
            // Fallback if no active launch date in database
            $rangeDate = false;
            $rangeDateStart = null;
            $rangeDateEnd = null;
            $singleDate = Carbon::create(2025, 12, 1, 0, 0, 0);
            $launchFinish = Carbon::create(2025, 12, 1, 0, 0, 0);
        }

        // Start date for countdown (can be static or dynamic)
        $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);

        // Get active modal infos from database (dynamic)
        $reminderModal = ModalInfo::getActiveReminderModal();
        $welcomeModal = ModalInfo::getActiveWelcomeModal();

        // Get active card boxes from database (dynamic)
        $cardBoxes = CardBox::active()->ordered()->get();

        // Get active menu choice from database (dynamic)
        $menuChoice = MenuChoice::active()->first();

        // Load team data dari JSON
        $teamDataPath = public_path('sigap-assets/static/team-data.json');
        $teamData = json_decode(file_get_contents($teamDataPath), true);

        // Load journal data dari JSON
        $journalDataPath = public_path('sigap-assets/static/journal-data.json');
        $journalData = json_decode(file_get_contents($journalDataPath), true);

        return view('landing.pages.home.index', compact('launchStart', 'launchFinish', 'teamData', 'journalData', 'rangeDate', 'rangeDateStart', 'rangeDateEnd', 'singleDate', 'launchDate', 'reminderModal', 'welcomeModal', 'cardBoxes', 'menuChoice'));
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

    public function resultPoster2025()
    {
        // Konsisten dengan halaman lain yang pakai launch date
        $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);
        $launchFinish = Carbon::create(2025, 10, 17, 0, 0, 0);

        $bpkhPosters = BpkhPoster::orderBy('nama_bpkh')->get();
        $produsenPosters = ProdusenPoster::orderBy('nama_instansi')->get();

        return view('landing.pages.home.result-poster-2025', compact(
            'launchStart',
            'launchFinish',
            'bpkhPosters',
            'produsenPosters'
        ));
    }

    public function resultWinner()
    {
        // Konsisten dengan halaman lain yang pakai launch date
        $launchStart = Carbon::create(2025, 10, 3, 0, 0, 0);
        $launchFinish = Carbon::create(2025, 10, 17, 0, 0, 0);

        // Ambil pemenang aktif per kategori sesuai urutan yang diminta
        $produsenWinners = PemenangSigap::active()
            ->byKategori(PemenangSigap::KATEGORI_INOVASI_PRODUSEN)
            ->ordered()
            ->get();

        $bpkhWinners = PemenangSigap::active()
            ->byKategori(PemenangSigap::KATEGORI_INOVASI_BPKH)
            ->ordered()
            ->get();

        $pengelolaIgtWinners = PemenangSigap::active()
            ->byKategori(PemenangSigap::KATEGORI_PENGELOLA_IGT)
            ->ordered()
            ->get();

        $posterTerbaikWinners = PemenangSigap::active()
            ->byKategori(PemenangSigap::KATEGORI_POSTER_TERBAIK)
            ->ordered()
            ->get();

        $posterFavoritWinners = PemenangSigap::active()
            ->byKategori(PemenangSigap::KATEGORI_POSTER_FAVORIT)
            ->ordered()
            ->get();

        return view('landing.pages.home.result-winner', compact(
            'launchStart',
            'launchFinish',
            'produsenWinners',
            'bpkhWinners',
            'pengelolaIgtWinners',
            'posterTerbaikWinners',
            'posterFavoritWinners'
        ));
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
