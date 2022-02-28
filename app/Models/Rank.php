<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rank extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'short_name',
        'name',
        'rank_type_id'
    ];

    /**
     * Retrieves the type of the rank
     *
     * @return BelongsTo RankType
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RankType::class, 'rank_type_id');
    }

    /**
     * Creates a scope to search all ranks by the provided keyword
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('short_name', 'LIKE', "%$keyword%")
            ->orWhere('name', 'LIKE', "%$keyword%")
            ->orWhereHas('type', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%");
            });
    }
}
