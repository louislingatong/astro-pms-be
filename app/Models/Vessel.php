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
        'former_name',
        'flag',
        'call_sign',
        'official_no',
        'imo_no',
        'loa',
        'lbp',
        'light_condition',
        'classification',
        'character',
        'descriptive_note',
        'built_year',
        'build_yard',
        'tpc',
        'breadth',
        'depth',
        'summer_draft',
        'summer_freeboard',
        'summer_deadweight',
        'winter_draft',
        'winter_freeboard',
        'winter_deadweight',
        'tropical_draft',
        'tropical_freeboard',
        'tropical_deadweight',
        'tropical_fw_draft',
        'tropical_fw_freeboard',
        'tropical_fw_deadweight',
        'fw_draft',
        'fw_freeboard',
        'fw_deadweight',
        'fw_allowance',
        'light_shift_drafts_f',
        'light_shift_drafts_a',
        'heavy_ballast_drafts_f',
        'heavy_ballast_drafts_a',
        'normal_ballast_drafts_f',
        'normal_ballast_drafts_a',
        'international_gt',
        'international_nt',
        'panama_gt',
        'panama_nt',
        'suez_gt',
        'suez_nt',
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
