<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpkhPresentationSession extends Model
{
    protected $table = 'sesi_bpkh_presentasi';
    
    protected $fillable = [
        'session_name',
        'respondent_id',
        'nama_bpkh',
        'order',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];
    
    /**
     * Get all sessions grouped by session name
     */
    public static function getGroupedSessions()
    {
        return self::where('is_active', true)
            ->orderBy('session_name')
            ->orderBy('order')
            ->get()
            ->groupBy('session_name');
    }
    
    /**
     * Get participants for a specific session
     */
    public static function getSessionParticipants($sessionName)
    {
        return self::where('session_name', $sessionName)
            ->where('is_active', true)
            ->orderBy('order')
            ->pluck('nama_bpkh')
            ->toArray();
    }
}
