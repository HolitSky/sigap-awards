<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaunchDate extends Model
{
    protected $table = 'launch_dates';
    
    protected $fillable = [
        'title',
        'is_range_date',
        'single_date',
        'start_date',
        'end_date',
        'is_active',
        'order'
    ];
    
    protected $casts = [
        'is_range_date' => 'boolean',
        'is_active' => 'boolean',
        'single_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'order' => 'integer'
    ];
    
    /**
     * Get active launch date
     */
    public static function getActiveLaunchDate()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->first();
    }
    
    /**
     * Get all active launch dates
     */
    public static function getAllActiveLaunchDates()
    {
        return self::where('is_active', true)
            ->orderBy('order')
            ->get();
    }
    
    /**
     * Get formatted date for display
     */
    public function getFormattedDateAttribute()
    {
        if ($this->is_range_date && $this->start_date && $this->end_date) {
            $startDay = $this->start_date->format('d');
            $endDay = $this->end_date->format('d');
            return $startDay . '-' . $endDay;
        } elseif ($this->single_date) {
            return $this->single_date->format('d');
        }
        return '';
    }
    
    /**
     * Get month name in Indonesian
     */
    public function getMonthNameAttribute()
    {
        $date = $this->is_range_date ? $this->start_date : $this->single_date;
        
        if (!$date) {
            return '';
        }
        
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $months[$date->format('n')];
    }
    
    /**
     * Get datetime attribute for HTML
     */
    public function getDatetimeAttribute()
    {
        $date = $this->is_range_date ? $this->start_date : $this->single_date;
        return $date ? $date->format('Y-m-d') : '';
    }
}
