<?php

namespace App\Exports;

use App\Models\ProdusenForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdusenFormExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function collection()
    {
        if ($this->query) {
            return $this->query->get();
        }
        
        return ProdusenForm::orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Instansi',
            'Nama Petugas',
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
    }

    public function map($form): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $form->nama_instansi ?? '-',
            $form->nama_petugas ?? '-',
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
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 30,
            'C' => 25,
            'D' => 12,
            'E' => 18,
            'F' => 25,
            'G' => 25,
            'H' => 15,
            'I' => 12,
            'J' => 18,
            'K' => 20,
            'L' => 30,
            'M' => 18,
        ];
    }
}
