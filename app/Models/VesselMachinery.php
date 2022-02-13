<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VesselMachinery extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'installed_date',
        'due_date',
        'vessel_id',
        'machinery_id',
        'incharge_rank_id',
        'interval_id',
    ];

    /**
     * Retrieves the vessel of the vessel machinery
     *
     * @return BelongsTo Vessel
     */
    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class, 'vessel_id');
    }

    /**
     * Retrieves the machinery of the vessel machinery
     *
     * @return BelongsTo Machinery
     */
    public function machinery(): BelongsTo
    {
        return $this->belongsTo(Machinery::class, 'machinery_id');
    }

    /**
     * Retrieves the rank in-charge of the vessel machinery
     *
     * @return BelongsTo Rank
     */
    public function inchargeRank(): BelongsTo
    {
        return $this->belongsTo(Rank::class, 'incharge_rank_id');
    }

    /**
     * Retrieves the interval of the vessel machinery
     *
     * @return BelongsTo Interval
     */
    public function interval(): BelongsTo
    {
        return $this->belongsTo(Interval::class, 'interval_id');
    }

    /**
     * Creates a scope to search all vessel machinery by the provided keyword
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('installed_date', 'LIKE', "%$keyword%")
            ->where('due_date', 'LIKE', "%$keyword%")
            ->orWhereHas('vessel', function ($q) use ($keyword) {
                $q->where('code_name', 'LIKE', "%$keyword%");
            })
            ->orWhereHas('machinery', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                    ->orWhereHas('department', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', "%$keyword%");
                    });
            })
            ->orWhereHas('inchargeRank', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%");
            })
            ->orWhereHas('interval', function ($q) use ($keyword) {
                $q->where('value', 'LIKE', "%$keyword%");
            });
    }
}
