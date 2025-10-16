<?php

namespace App\Services;

use App\Models\BpkhForm;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Revolution\Google\Sheets\Facades\Sheets;

class BpkhSheetSyncService
{
    private array $keyHeaders = [
        'Respondent ID' => 'respondent_id',
        'Nama BPKH' => 'nama_bpkh',
        'Petugas BPKH' => 'petugas_bpkh',
        'Nomor Telepon / Nomor WhatsApp Aktif' => 'phone',
        'Situs Website' => 'website',
    ];

    public function sync(): int
    {
        $spreadsheetId = config('google.sheets.spreadsheet_id_bpkh');
        $range = config('google.sheets.range');

        $rows = Sheets::spreadsheet($spreadsheetId)->range($range)->get();

        if ($rows->isEmpty()) {
            return 0;
        }

        $header = collect($rows->pull(0))->map(fn ($h) => trim((string) $h))->toArray();
        
        // Handle duplicate column names by appending suffix
        $headerCounts = [];
        $uniqueHeader = [];
        foreach ($header as $h) {
            if (!isset($headerCounts[$h])) {
                $headerCounts[$h] = 0;
                $uniqueHeader[] = $h;
            } else {
                $headerCounts[$h]++;
                $uniqueHeader[] = $h . ' (' . $headerCounts[$h] . ')';
            }
        }

        $count = 0;

        foreach ($rows as $i => $row) {
            $rowArr = collect($row)->map(fn ($v) => trim((string) ($v ?? '')))->toArray();
            $assoc = array_combine($uniqueHeader, array_pad($rowArr, count($uniqueHeader), ''));

            $respondentId = $assoc['Respondent ID'] ?? null;
            if (!$respondentId) {
                continue;
            }

            // Debug logging for attachment columns
            foreach ($uniqueHeader as $idx => $uh) {
                $originalHeader = $header[$idx];
                if (stripos($originalHeader, 'Lampiran') !== false) {
                    $rawValue = $row[$idx] ?? null;
                    $processedValue = $assoc[$uh] ?? '';
                    Log::info("BPKH Sync Debug - Row " . ($i + 2), [
                        'respondent_id' => $respondentId,
                        'column' => $originalHeader,
                        'unique_column' => $uh,
                        'raw_value' => $rawValue,
                        'processed_value' => $processedValue,
                        'is_empty' => empty($processedValue)
                    ]);
                }
            }

            // Build meta as an ordered list of key/value pairs to preserve header order consistently across DB engines
            $orderedMeta = [];
            foreach ($uniqueHeader as $idx => $uh) {
                $originalHeader = $header[$idx];
                $orderedMeta[] = ['key' => $originalHeader, 'value' => $assoc[$uh] ?? ''];
            }

            $payload = [
                'synced_at' => Carbon::now(),
                'sheet_row_number' => $i + 2, // +2: header row + 1-indexed
                'meta' => $orderedMeta,
            ];

            foreach ($this->keyHeaders as $sheetHeader => $dbCol) {
                $payload[$dbCol] = $assoc[$sheetHeader] ?? null;
            }

            $payload['respondent_id'] = $respondentId;

            BpkhForm::updateOrCreate(['respondent_id' => $respondentId], $payload);
            $count++;
        }

        return $count;
    }
}


