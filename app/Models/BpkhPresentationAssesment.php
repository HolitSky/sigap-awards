<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpkhPresentationAssesment extends Model
{
    protected $table = 'bpkh_presentasi_assesment';
    
    protected $fillable = [
        'respondent_id',
        'nama_bpkh',
        'petugas_bpkh',
        'aspek_penilaian',
        'penilaian_per_juri',
        'nilai_final',
        'bobot_presentasi',
        'nilai_final_dengan_bobot',
        'kategori_skor',
        'deskripsi_skor',
        'synced_from_form_id',
        'synced_at',
    ];
    
    protected $casts = [
        'aspek_penilaian' => 'array',
        'penilaian_per_juri' => 'array',
        'nilai_final' => 'decimal:2',
        'bobot_presentasi' => 'decimal:2',
        'nilai_final_dengan_bobot' => 'decimal:2',
        'synced_at' => 'datetime',
    ];
    
    // Scope untuk search
    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }
        
        return $query->where(function ($w) use ($term) {
            $w->where('respondent_id', 'like', "%$term%")
                ->orWhere('nama_bpkh', 'like', "%$term%")
                ->orWhere('petugas_bpkh', 'like', "%$term%");
        });
    }
    
    // Helper untuk calculate nilai final dari semua juri
    public function calculateNilaiFinal()
    {
        $penilaianJuri = $this->penilaian_per_juri ?? [];
        
        if (empty($penilaianJuri)) {
            $this->nilai_final = null;
            $this->nilai_final_dengan_bobot = null;
            $this->kategori_skor = null;
            $this->deskripsi_skor = null;
            return;
        }
        
        $totalNilai = 0;
        $count = 0;
        
        foreach ($penilaianJuri as $penilaian) {
            if (isset($penilaian['nilai_akhir_user'])) {
                $totalNilai += $penilaian['nilai_akhir_user'];
                $count++;
            }
        }
        
        if ($count > 0) {
            $this->nilai_final = round($totalNilai / $count, 2);
            $this->nilai_final_dengan_bobot = round(($this->nilai_final * $this->bobot_presentasi) / 100, 2);
            $this->setKategoriSkor($this->nilai_final);
        }
    }
    
    // Set kategori dan deskripsi skor berdasarkan nilai
    private function setKategoriSkor($nilai)
    {
        if ($nilai >= 81 && $nilai <= 100) {
            $this->kategori_skor = 'Sangat Baik';
            $this->deskripsi_skor = 'Melampaui ekspektasi, menunjukkan dampak dan inovasi signifikan';
        } elseif ($nilai >= 61 && $nilai <= 80) {
            $this->kategori_skor = 'Baik';
            $this->deskripsi_skor = 'Menunjukkan implementasi kuat dan hasil nyata';
        } elseif ($nilai >= 41 && $nilai <= 60) {
            $this->kategori_skor = 'Cukup';
            $this->deskripsi_skor = 'Relevan dan memenuhi sebagian besar indikator';
        } elseif ($nilai >= 21 && $nilai <= 40) {
            $this->kategori_skor = 'Kurang';
            $this->deskripsi_skor = 'Implementasi terbatas, belum menunjukkan hasil optimal';
        } elseif ($nilai >= 1 && $nilai <= 20) {
            $this->kategori_skor = 'Sangat Kurang';
            $this->deskripsi_skor = 'Minim bukti atau penyajian tidak relevan';
        }
    }
    
    // Relationship ke form asli
    public function bpkhForm()
    {
        return $this->belongsTo(BpkhForm::class, 'synced_from_form_id');
    }
}
