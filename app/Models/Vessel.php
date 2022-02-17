<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vessel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vessel_owner_id',
        'code_name',
        'name',
    ];

    /**
     * Retrieves the owner of the vessel
     *
     * @return BelongsTo VesselOwner
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(VesselOwner::class, 'vessel_owner_id');
    }

    /**
     * Creates a scope to search all vessel by the provided keyword
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('name', 'LIKE', "%$keyword%");
    }
}
