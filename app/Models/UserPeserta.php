<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserPeserta extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_peserta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'foto',
        'kategori',
        'bpkh_id',
        'produsen_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the BPKH wilayah for the user.
     */
    public function bpkh()
    {
        return $this->belongsTo(BpkhList::class, 'bpkh_id');
    }

    /**
     * Get the Produsen unit for the user.
     */
    public function produsen()
    {
        return $this->belongsTo(ProdusenList::class, 'produsen_id');
    }

    /**
     * Get the kategori display name.
     */
    public function getKategoriNameAttribute()
    {
        if ($this->kategori === 'bpkh') {
            return $this->bpkh ? $this->bpkh->nama_wilayah : '-';
        } elseif ($this->kategori === 'produsen') {
            return $this->produsen ? $this->produsen->nama_unit : '-';
        }
        return '-';
    }
}
