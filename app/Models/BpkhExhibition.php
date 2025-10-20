<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpkhExhibition extends Model
{
    protected $fillable = [
        'respondent_id',
        'nama_bpkh',
        'petugas_bpkh',
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
                ->orWhere('nama_bpkh', 'like', "%$term%")
                ->orWhere('petugas_bpkh', 'like', "%$term%");
        });
    }
}
