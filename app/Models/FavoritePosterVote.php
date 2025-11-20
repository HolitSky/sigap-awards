<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoritePosterVote extends Model
{
    protected $fillable = [
        'respondent_id',
        'participant_name',
        'participant_type',
        'petugas',
        'vote_count',
        'notes',
    ];

    protected $casts = [
        'vote_count' => 'integer',
    ];

    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($w) use ($term) {
            $w->where('respondent_id', 'like', "%$term%")
                ->orWhere('participant_name', 'like', "%$term%")
                ->orWhere('petugas', 'like', "%$term%");
        });
    }

    public function scopeByType($query, $type)
    {
        if (!$type) {
            return $query;
        }

        return $query->where('participant_type', $type);
    }
}
