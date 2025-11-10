<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_menu_title',
        'use_main_menu',
        'menu_items',
        'is_active',
    ];

    protected $casts = [
        'use_main_menu' => 'boolean',
        'is_active' => 'boolean',
        'menu_items' => 'array',
    ];

    /**
     * Scope untuk filter yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper method untuk cek apakah menggunakan main menu
     */
    public function usesMainMenu(): bool
    {
        return $this->use_main_menu === true;
    }

    /**
     * Helper method untuk get menu items
     */
    public function getMenuItems(): array
    {
        return $this->menu_items ?? [];
    }

    /**
     * Constants untuk menu item types
     */
    const ITEM_TYPE_LINK = 'link';
    const ITEM_TYPE_MODAL = 'modal';
}
