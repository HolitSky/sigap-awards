<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresentationSessionConfig extends Model
{
    protected $table = 'presentation_sessions_config';
    
    protected $fillable = [
        'session_name',
        'session_number',
        'session_type',
        'is_active',
        'order'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'session_number' => 'integer',
        'order' => 'integer'
    ];
    
    /**
     * Get active BPKH sessions
     */
    public static function getActiveBpkhSessions()
    {
        return self::where('session_type', 'bpkh')
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('session_number')
            ->get();
    }
    
    /**
     * Get active Produsen sessions
     */
    public static function getActiveProdusenSessions()
    {
        return self::where('session_type', 'produsen')
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('session_number')
            ->get();
    }
    
    /**
     * Get all active sessions grouped by type
     */
    public static function getAllActiveSessions()
    {
        return self::where('is_active', true)
            ->orderBy('session_type')
            ->orderBy('order')
            ->orderBy('session_number')
            ->get()
            ->groupBy('session_type');
    }
}
