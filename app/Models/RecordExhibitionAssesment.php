<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordExhibitionAssesment extends Model
{
    protected $fillable = [
        'exhibition_type',
        'exhibition_id',
        'user_id',
        'user_name',
        'nilai_akhir_user',
        'catatan_juri',
        'rekomendasi',
        'assessed_at',
    ];

    protected $casts = [
        'assessed_at' => 'datetime',
        'nilai_akhir_user' => 'decimal:2',
    ];
}
