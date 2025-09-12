<?php

namespace App\Http\Controllers\landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Konfigurasi launch date dari Controller
        $launchStart = Carbon::create(2025, 10, 1, 0, 0, 0);
        $launchFinish = Carbon::create(2025, 10, 10, 0, 0, 0);

        // Load team data dari JSON
        $teamDataPath = public_path('sigap-assets/static/team-data.json');
        $teamData = json_decode(file_get_contents($teamDataPath), true);

        // Load journal data dari JSON
        $journalDataPath = public_path('sigap-assets/static/journal-data.json');
        $journalData = json_decode(file_get_contents($journalDataPath), true);

        return view('landing.pages.home.index', compact('launchStart', 'launchFinish', 'teamData', 'journalData'));
    }
}
