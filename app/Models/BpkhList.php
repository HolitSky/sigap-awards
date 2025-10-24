<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpkhList extends Model
{
    use HasFactory;

    protected $table = 'bpkh_list';

    protected $fillable = [
        'nama_wilayah',
        'kode_wilayah',
    ];

    /**
     * Get the users for the BPKH wilayah.
     */
    public function users()
    {
        return $this->hasMany(UserPeserta::class, 'bpkh_id');
    }
}
