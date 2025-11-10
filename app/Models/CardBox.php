<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardBox extends Model
{
    use HasFactory;

    protected $table = 'card_boxes';

    protected $fillable = [
        'title',
        'description',
        'content_type',
        'button_text',
        'link_url',
        'modal_content',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Content type constants
     */
    const TYPE_TEXT_ONLY = 'text_only';
    const TYPE_LINK = 'link';
    const TYPE_MODAL = 'modal';

    /**
     * Scope untuk mendapatkan card box yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Check if content type is text only
     */
    public function isTextOnly()
    {
        return $this->content_type === self::TYPE_TEXT_ONLY;
    }

    /**
     * Check if content type is link
     */
    public function isLink()
    {
        return $this->content_type === self::TYPE_LINK;
    }

    /**
     * Check if content type is modal
     */
    public function isModal()
    {
        return $this->content_type === self::TYPE_MODAL;
    }
}
