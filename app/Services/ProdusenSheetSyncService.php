<?php

namespace App\Services;

use App\Models\ProdusenForm;
use Carbon\Carbon;
use Revolution\Google\Sheets\Facades\Sheets;

class ProdusenSheetSyncService
{
    private array $keyHeaders = [
        'Respondent ID' => 'respondent_id',
        'Nama Instansi/Direktorat Produsen DG' => 'nama_instansi',
        'Petugas Produsen DG' => 'nama_petugas',
        'Nomor Telepon / Nomor WhatsApp Aktif' => 'phone',
        'Situs Website' => 'website',
    ];

    public function sync(): int
    {
        $spreadsheetId = config('google.sheets.spreadsheet_id_produsen');
        $range = config('google.sheets.range');

        $rows = Sheets::spreadsheet($spreadsheetId)->range($range)->get();

        if ($rows->isEmpty()) {
            return 0;
        }

        $header = collect($rows->pull(0))->map(fn ($h) => trim((string) $h))->toArray();

        $count = 0;

        foreach ($rows as $i => $row) {
            $rowArr = collect($row)->map(fn ($v) => trim((string) ($v ?? '')))->toArray();
            $assoc = array_combine($header, array_pad($rowArr, count($header), ''));

            $respondentId = $assoc['Respondent ID'] ?? null;
            if (!$respondentId) {
                continue;
            }

            // Preserve header order in meta
            $orderedMeta = [];
            foreach ($header as $h) {
                $orderedMeta[] = ['key' => $h, 'value' => $assoc[$h] ?? ''];
            }

            $payload = [
                'synced_at' => Carbon::now(),
                'sheet_row_number' => $i + 2,
                'meta' => $orderedMeta,
            ];

            foreach ($this->keyHeaders as $sheetHeader => $dbCol) {
                $payload[$dbCol] = $assoc[$sheetHeader] ?? null;
            }

            $payload['respondent_id'] = $respondentId;

            ProdusenForm::updateOrCreate(['respondent_id' => $respondentId], $payload);
            $count++;
        }

        return $count;
    }
}


