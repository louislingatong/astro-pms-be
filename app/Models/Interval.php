<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interval extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'interval_unit_id',
        'value',
        'name',
    ];

    /**
     * Retrieves the unit of the interval
     *
     * @return BelongsTo IntervalUnit
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(IntervalUnit::class, 'interval_unit_id');
    }

    /**
     * Creates a scope to search all interval by the provided keyword
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('value', 'LIKE', "%$keyword%")
            ->orWhere('name', 'LIKE', "%$keyword%")
            ->orWhereHas('unit', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%");
            });
    }
}
