<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpkhForm extends Model
{
    protected $fillable = [
        'respondent_id',
        'nama_bpkh',
        'petugas_bpkh',
        'phone',
        'website',
        'status_nilai',
        'total_score',
        'notes',
        'sheet_row_number',
        'meta',
        'synced_at',
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
                ->orWhere('nama_bpkh', 'like', "%$term%")
                ->orWhere('petugas_bpkh', 'like', "%$term%")
                ->orWhere('phone', 'like', "%$term%")
                ->orWhere('website', 'like', "%$term%");
        });
    }
}
