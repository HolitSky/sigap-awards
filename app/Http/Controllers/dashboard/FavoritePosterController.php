<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoritePosterVote;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FavoritePosterController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Penilaian Poster Favorit';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Poster Favorit', 'url' => null, 'active' => true]
        ];

        $term = $request->string('q')->toString();
        $typeFilter = $request->string('type')->toString();

        $votes = FavoritePosterVote::query()
            ->search($term)
            ->when($typeFilter, function($query) use ($typeFilter) {
                $query->byType($typeFilter);
            })
            ->orderBy('vote_count', 'desc')
            ->orderBy('participant_name', 'asc')
            ->get();

        return view('dashboard.pages.exhibition.favorite.index', compact(
            'title',
            'pageTitle',
            'breadcrumbs',
            'votes',
            'term',
            'typeFilter'
        ));
    }

    public function edit(string $id)
    {
        $title = 'Input Penilaian Poster Favorit';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Poster Favorit', 'url' => route('dashboard.favorite-poster.index')],
            ['name' => 'Input Penilaian', 'url' => null, 'active' => true]
        ];

        $vote = FavoritePosterVote::findOrFail($id);

        return view('dashboard.pages.exhibition.favorite.edit', compact(
            'title',
            'pageTitle',
            'breadcrumbs',
            'vote'
        ));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'vote_count' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::beginTransaction();
        try {
            $vote = FavoritePosterVote::findOrFail($id);
            $vote->update($data);

            DB::commit();

            return redirect()
                ->route('dashboard.favorite-poster.index')
                ->with('success', 'Penilaian poster favorit berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkEdit(Request $request)
    {
        $title = 'Input Penilaian Kolektif Poster Favorit';
        $pageTitle = $title;
        $breadcrumbs = [
            ['name' => 'Penilaian Poster Favorit', 'url' => route('dashboard.favorite-poster.index')],
            ['name' => 'Input Kolektif', 'url' => null, 'active' => true]
        ];

        // Get selected IDs from query string
        $ids = $request->query('ids');
        if (!$ids) {
            return redirect()->route('dashboard.favorite-poster.index')
                ->withErrors(['error' => 'Tidak ada peserta yang dipilih']);
        }

        $selectedIds = explode(',', $ids);

        // Get votes data
        $votes = FavoritePosterVote::whereIn('id', $selectedIds)
            ->orderBy('participant_type', 'asc')
            ->orderBy('participant_name', 'asc')
            ->get();

        if ($votes->isEmpty()) {
            return redirect()->route('dashboard.favorite-poster.index')
                ->withErrors(['error' => 'Data peserta tidak ditemukan']);
        }

        return view('dashboard.pages.exhibition.favorite.bulk_edit', compact(
            'title',
            'pageTitle',
            'breadcrumbs',
            'votes'
        ));
    }

    public function bulkUpdate(Request $request)
    {
        $participants = $request->input('participants', []);

        if (empty($participants)) {
            return redirect()->back()->withErrors(['error' => 'Tidak ada data penilaian']);
        }

        DB::beginTransaction();
        try {
            foreach ($participants as $id => $data) {
                $vote = FavoritePosterVote::findOrFail($id);
                $vote->update([
                    'vote_count' => $data['vote_count'] ?? 0,
                    'notes' => $data['notes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('dashboard.favorite-poster.index')
                ->with('success', 'Penilaian kolektif poster favorit berhasil disimpan untuk ' . count($participants) . ' peserta!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menyimpan penilaian: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function exportAll(Request $request)
    {
        $format = $request->query('format', 'excel'); // excel, pdf

        $votes = FavoritePosterVote::orderBy('vote_count', 'desc')
            ->orderBy('participant_name', 'asc')
            ->get();

        $fileName = 'Poster_Favorit_All_' . date('Y-m-d');

        // Prepare data
        $data = [];
        foreach ($votes as $vote) {
            $data[] = [
                'Respondent ID' => $vote->respondent_id,
                'Nama' => $vote->participant_name,
                'Kategori' => $vote->participant_type === 'bpkh' ? 'BPKH' : 'Produsen DG',
                'Petugas' => $vote->petugas ?? '-',
                'Jumlah Vote' => $vote->vote_count,
                'Catatan' => $vote->notes ?? '-',
            ];
        }

        if (empty($data)) {
            return back()->withErrors(['error' => 'Tidak ada data untuk diekspor']);
        }

        if ($format === 'excel') {
            return Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
                protected $data;

                public function __construct($data)
                {
                    $this->data = $data;
                }

                public function array(): array
                {
                    return array_map(function($row) {
                        return array_values($row);
                    }, $this->data);
                }

                public function headings(): array
                {
                    return array_keys($this->data[0]);
                }
            }, $fileName . '.xlsx');
        } elseif ($format === 'pdf') {
            $pdf = Pdf::loadView('dashboard.pages.exhibition.favorite.export_pdf', [
                'data' => $data,
                'title' => 'Penilaian Poster Favorit - Semua Data'
            ])->setPaper('a4', 'portrait');

            return $pdf->download($fileName . '.pdf');
        }

        return back()->withErrors(['error' => 'Format tidak valid']);
    }
}
