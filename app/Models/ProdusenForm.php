<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdusenForm extends Model
{
    protected $fillable = [
        'respondent_id',
        'nama_instansi',
        'nama_petugas',
        'phone',
        'website',
        'status_nilai',
        'total_score',
        'notes',
        'sheet_row_number',
        'meta',
        'synced_at',
        'juri_penilai',
    ];

    protected $casts = [
        'meta' => 'array',
        'synced_at' => 'datetime',
    ];

    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($w) use ($term) {
            $w->where('respondent_id', 'like', "%$term%")
                ->orWhere('nama_instansi', 'like', "%$term%")
                ->orWhere('nama_petugas', 'like', "%$term%")
                ->orWhere('phone', 'like', "%$term%")
                ->orWhere('website', 'like', "%$term%");
        });
    }
}
