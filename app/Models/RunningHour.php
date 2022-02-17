<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RunningHour extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vessel_machinery_id',
        'running_hours',
        'updating_date',
        'creator_id'
    ];

    /**
     * Retrieves the vessel machinery of the running hour
     *
     * @return BelongsTo VesselMachinery
     */
    public function vesselMachinery(): BelongsTo
    {
        return $this->belongsTo(VesselMachinery::class, 'vessel_machinery_id');
    }

    /**
     * Retrieves the editor of the running hour
     *
     * @return BelongsTo User
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
