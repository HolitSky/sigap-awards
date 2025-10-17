<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordUserAssesment extends Model
{
    protected $table = 'record_assesment';
    
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'form_type',
        'respondent_id',
        'form_name',
        'action_type',
        'total_score',
        'meta_changes',
        'notes',
    ];
    
    protected $casts = [
        'meta_changes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Relationship to User
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    
    // Scope untuk filter by form
    public function scopeByForm($query, $formType, $respondentId)
    {
        return $query->where('form_type', $formType)
                     ->where('respondent_id', $respondentId);
    }
    
    // Scope untuk latest assessment
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
