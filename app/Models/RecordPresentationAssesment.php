<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserModel;

class RecordPresentationAssesment extends Model
{
    protected $table = 'record_presentasi_assesment';
    
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'form_type',
        'respondent_id',
        'form_name',
        'action_type',
        'nilai_akhir_user',
        'catatan_juri',
        'rekomendasi',
        'aspek_scores',
    ];
    
    protected $casts = [
        'aspek_scores' => 'array',
        'nilai_akhir_user' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Relationship to User
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
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
