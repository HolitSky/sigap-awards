<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalInfo extends Model
{
    protected $table = 'modal_infos';
    
    protected $fillable = [
        'modal_type',
        'title',
        'subtitle',
        'intro_text',
        'footer_text',
        'show_form_options',
        'meta_links',
        'is_show'
    ];
    
    protected $casts = [
        'is_show' => 'boolean',
        'show_form_options' => 'boolean',
        'meta_links' => 'array'
    ];
    
    /**
     * Get active reminder modal (type = reminder, is_show = true)
     */
    public static function getActiveReminderModal()
    {
        return self::where('modal_type', 'reminder')
            ->where('is_show', true)
            ->first();
    }
    
    /**
     * Get active welcome modal (type = welcome, is_show = true)
     */
    public static function getActiveWelcomeModal()
    {
        return self::where('modal_type', 'welcome')
            ->where('is_show', true)
            ->first();
    }
    
    /**
     * Legacy method - get any active modal
     */
    public static function getActiveModal()
    {
        return self::where('is_show', true)->first();
    }
}
