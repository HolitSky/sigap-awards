<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdusenList extends Model
{
    use HasFactory;

    protected $table = 'produsen_list';

    protected $fillable = [
        'nama_unit',
    ];

    /**
     * Get the users for the Produsen unit.
     */
    public function users()
    {
        return $this->hasMany(UserPeserta::class, 'produsen_id');
    }
}
