<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdusenExhibition extends Model
{
    protected $fillable = [
        'respondent_id',
        'nama_instansi',
        'nama_petugas',
        'aspek_penilaian',
        'penilaian_per_juri',
        'bobot_exhibition',
        'nilai_final',
        'nilai_final_dengan_bobot',
        'kategori_penilaian',
        'deskripsi_kategori',
        'total_juri_menilai',
        'status',
    ];

    protected $casts = [
        'aspek_penilaian' => 'array',
        'penilaian_per_juri' => 'array',
        'bobot_exhibition' => 'decimal:2',
        'nilai_final' => 'decimal:2',
        'nilai_final_dengan_bobot' => 'decimal:2',
    ];

    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($w) use ($term) {
            $w->where('respondent_id', 'like', "%$term%")
                ->orWhere('nama_instansi', 'like', "%$term%")
                ->orWhere('nama_petugas', 'like', "%$term%");
        });
    }

    /**
     * Calculate final score from all jury assessments
     */
    public function calculateNilaiFinal()
    {
        if (empty($this->penilaian_per_juri)) {
            $this->nilai_final = null;
            $this->nilai_final_dengan_bobot = null;
            $this->total_juri_menilai = 0;
            $this->kategori_penilaian = null;
            $this->deskripsi_kategori = null;
            return;
        }

        // Calculate average from all jury scores
        $totalNilai = 0;
        $jumlahJuri = count($this->penilaian_per_juri);

        foreach ($this->penilaian_per_juri as $penilaian) {
            $totalNilai += $penilaian['nilai_akhir_user'];
        }

        $nilaiFinal = $jumlahJuri > 0 ? $totalNilai / $jumlahJuri : 0;
        
        $this->nilai_final = round($nilaiFinal, 2);
        $this->nilai_final_dengan_bobot = round($nilaiFinal * ($this->bobot_exhibition / 100), 2);
        $this->total_juri_menilai = $jumlahJuri;

        // Determine category
        $kategori = $this->determineCategory($nilaiFinal);
        $this->kategori_penilaian = $kategori['kategori'];
        $this->deskripsi_kategori = $kategori['deskripsi'];
    }

    /**
     * Determine category based on score
     */
    private function determineCategory($nilai)
    {
        if ($nilai >= 85) {
            return [
                'kategori' => 'Sangat Baik',
                'deskripsi' => 'Exhibition sangat menarik, informatif, dan sesuai tema.'
            ];
        } elseif ($nilai >= 75) {
            return [
                'kategori' => 'Baik',
                'deskripsi' => 'Exhibition menarik dan informatif dengan beberapa area untuk perbaikan.'
            ];
        } elseif ($nilai >= 65) {
            return [
                'kategori' => 'Cukup',
                'deskripsi' => 'Exhibition cukup baik namun perlu peningkatan dalam beberapa aspek.'
            ];
        } elseif ($nilai >= 50) {
            return [
                'kategori' => 'Kurang',
                'deskripsi' => 'Informasi dasar disajikan, namun kurang jelas dan menarik.'
            ];
        } else {
            return [
                'kategori' => 'Sangat Kurang',
                'deskripsi' => 'Substansi dan tampilan tidak memenuhi kriteria.'
            ];
        }
    }
}
