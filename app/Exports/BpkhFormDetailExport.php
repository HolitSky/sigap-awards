<?php

namespace App\Exports;

use App\Models\BpkhForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BpkhFormDetailExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $query;
    protected $metaKeys = [];

    public function __construct($query = null)
    {
        $this->query = $query;
        $this->collectMetaKeys();
    }

    protected function collectMetaKeys()
    {
        $forms = $this->query ? $this->query->get() : BpkhForm::all();
        
        $allKeys = [];
        foreach ($forms as $form) {
            if (is_array($form->meta)) {
                $isOrdered = isset($form->meta[0]) && is_array($form->meta[0]) && array_key_exists('key', $form->meta[0]);
                if ($isOrdered) {
                    foreach ($form->meta as $item) {
                        $key = $item['key'] ?? '';
                        if ($key && !in_array($key, $allKeys)) {
                            $allKeys[] = $key;
                        }
                    }
                } else {
                    foreach ($form->meta as $k => $v) {
                        if (!in_array($k, $allKeys)) {
                            $allKeys[] = $k;
                        }
                    }
                }
            }
        }
        
        $this->metaKeys = $allKeys;
    }

    public function collection()
    {
        if ($this->query) {
            return $this->query->get();
        }
        
        return BpkhForm::orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        $baseHeadings = [
            'No',
            'Nama BPKH',
            'Petugas BPKH',
            'Nominasi',
            'Nomor Telepon',
            'Email',
            'Website',
            'Status Nilai',
            'Nilai Final',
            'Nilai Bobot Akhir (45%)',
            'Kategori Penilaian',
            'Catatan',
            'Tanggal Submit'
        ];
        
        // Add metadata columns
        foreach ($this->metaKeys as $key) {
            $baseHeadings[] = $key;
        }
        
        return $baseHeadings;
    }

    public function map($form): array
    {
        static $no = 0;
        $no++;

        $baseData = [
            $no,
            $form->nama_bpkh ?? '-',
            $form->petugas_bpkh ?? '-',
            $form->nominasi ? 'Masuk' : 'Tidak Masuk',
            $form->phone ?? '-',
            $form->email ?? '-',
            $form->website ?? '-',
            $form->status_label ?? $form->status_nilai ?? 'Pending',
            $form->total_score !== null ? $form->total_score : 'Belum Ada',
            $form->nilai_bobot_total !== null ? number_format($form->nilai_bobot_total, 2) : 'Belum Ada',
            $form->kategori_penilaian ?? '-',
            $form->notes ?? '-',
            $form->created_at ? $form->created_at->format('d/m/Y H:i') : '-'
        ];
        
        // Build metadata map
        $metaMap = [];
        if (is_array($form->meta)) {
            $isOrdered = isset($form->meta[0]) && is_array($form->meta[0]) && array_key_exists('key', $form->meta[0]);
            if ($isOrdered) {
                foreach ($form->meta as $item) {
                    $key = $item['key'] ?? '';
                    $value = $item['value'] ?? '';
                    if ($key) {
                        $metaMap[$key] = $this->formatValue($value);
                    }
                }
            } else {
                foreach ($form->meta as $k => $v) {
                    $metaMap[$k] = $this->formatValue($v);
                }
            }
        }
        
        // Add metadata values in order
        foreach ($this->metaKeys as $key) {
            $baseData[] = $metaMap[$key] ?? '-';
        }
        
        return $baseData;
    }

    protected function formatValue($value)
    {
        if (is_array($value)) {
            return implode(', ', array_map(function($v) {
                return is_string($v) ? $v : json_encode($v);
            }, $value));
        }
        
        return $value !== null && $value !== '' ? $value : '-';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 5,   // No
            'B' => 30,  // Nama BPKH
            'C' => 25,  // Petugas
            'D' => 12,  // Nominasi
            'E' => 18,  // Telepon
            'F' => 25,  // Email
            'G' => 25,  // Website
            'H' => 15,  // Status
            'I' => 12,  // Nilai Final
            'J' => 18,  // Nilai Bobot
            'K' => 20,  // Kategori
            'L' => 30,  // Catatan
            'M' => 18,  // Tanggal
        ];
        
        // Add widths for metadata columns (dynamic)
        $column = 'N';
        foreach ($this->metaKeys as $key) {
            $widths[$column] = 35; // Wide columns for metadata
            $column++;
        }
        
        return $widths;
    }
}
