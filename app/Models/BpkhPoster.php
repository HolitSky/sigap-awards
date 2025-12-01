<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpkhPoster extends Model
{
    protected $table = 'input_bpkh_posters';

    protected $fillable = [
        'nama_bpkh',
        'poster_pdf_path',
        'original_filename',
        'original_mime',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];
}
