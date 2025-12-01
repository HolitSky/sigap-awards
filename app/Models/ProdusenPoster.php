<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdusenPoster extends Model
{
    protected $table = 'input_produsen_posters';

    protected $fillable = [
        'nama_instansi',
        'poster_pdf_path',
        'original_filename',
        'original_mime',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];
}
