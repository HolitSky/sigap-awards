<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemenangSigap extends Model
{
    use HasFactory;

    protected $table = 'pemenang_sigap';

    protected $fillable = [
        'kategori',
        'tipe_peserta',
        'nama_pemenang',
        'nama_petugas',
        'juara',
        'deskripsi',
        'foto_path',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    // Kategori constants
    const KATEGORI_POSTER_TERBAIK = 'poster_terbaik';
    const KATEGORI_POSTER_FAVORIT = 'poster_favorit';
    const KATEGORI_PENGELOLA_IGT = 'pengelola_igt_terbaik';
    const KATEGORI_INOVASI_BPKH = 'inovasi_bpkh_terbaik';
    const KATEGORI_INOVASI_PRODUSEN = 'inovasi_produsen_terbaik';

    // Tipe Peserta constants
    const TIPE_BPKH = 'bpkh';
    const TIPE_PRODUSEN = 'produsen';

    // Juara constants
    const JUARA_1 = 'juara_1';
    const JUARA_2 = 'juara_2';
    const JUARA_3 = 'juara_3';
    const JUARA_HARAPAN = 'juara_harapan';

    /**
     * Get kategori options
     */
    public static function getKategoriOptions()
    {
        return [
            self::KATEGORI_POSTER_TERBAIK => 'Poster Terbaik',
            self::KATEGORI_POSTER_FAVORIT => 'Poster Favorit',
            self::KATEGORI_PENGELOLA_IGT => 'Pengelola IGT Terbaik',
            self::KATEGORI_INOVASI_BPKH => 'Inovasi BPKH Terbaik',
            self::KATEGORI_INOVASI_PRODUSEN => 'Inovasi Produsen DG Terbaik',
        ];
    }

    /**
     * Get tipe peserta options
     */
    public static function getTipePesertaOptions()
    {
        return [
            self::TIPE_BPKH => 'BPKH',
            self::TIPE_PRODUSEN => 'Produsen',
        ];
    }

    /**
     * Get juara options
     */
    public static function getJuaraOptions()
    {
        return [
            self::JUARA_1 => 'Juara 1',
            self::JUARA_2 => 'Juara 2',
            self::JUARA_3 => 'Juara 3',
            self::JUARA_HARAPAN => 'Juara Harapan',
        ];
    }

    /**
     * Get kategori label
     */
    public function getKategoriLabelAttribute()
    {
        return self::getKategoriOptions()[$this->kategori] ?? $this->kategori;
    }

    /**
     * Get tipe peserta label
     */
    public function getTipePesertaLabelAttribute()
    {
        return self::getTipePesertaOptions()[$this->tipe_peserta] ?? $this->tipe_peserta;
    }

    /**
     * Get juara label
     */
    public function getJuaraLabelAttribute()
    {
        return self::getJuaraOptions()[$this->juara] ?? $this->juara;
    }

    /**
     * Scope untuk filter by kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk filter by tipe peserta
     */
    public function scopeByTipePeserta($query, $tipe)
    {
        return $query->where('tipe_peserta', $tipe);
    }

    /**
     * Scope untuk active only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('id', 'asc');
    }
}
