<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class VesselMachinerySubCategory extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'due_date',
        'interval_id',
        'vessel_machinery_id',
        'machinery_sub_category_id',
        'machinery_sub_category_description_id',
    ];

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
     * Retrieves the vessel machinery of the vessel sub category
     *
     * @return BelongsTo VesselMachinery
     */
    public function vesselMachinery(): BelongsTo
    {
        return $this->belongsTo(VesselMachinery::class, 'vessel_machinery_id');
    }

    /**
     * Retrieves the sub category of the vessel sub category
     *
     * @return BelongsTo MachinerySubCategory
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(MachinerySubCategory::class, 'machinery_sub_category_id');
    }

    /**
     * Retrieves the description of the vessel sub category
     *
     * @return BelongsTo MachinerySubCategoryDescription
     */
    public function description(): BelongsTo
    {
        return $this->belongsTo(MachinerySubCategoryDescription::class, 'machinery_sub_category_description_id');
    }

    /**
     * Retrieve current work under this vessel machinery sub category
     *
     * @return HasOne Work
     */
    public function currentWork(): HasOne
    {
        return $this->HasOne(Work::class, 'vessel_machinery_sub_category_id')
            ->orderBy('id', 'DESC');
    }

    /**
     * Retrieve all works under this vessel machinery sub category
     *
     * @return HasMany Work[]
     */
    public function worksHistory(): HasMany
    {
        return $this->HasMany(Work::class, 'vessel_machinery_sub_category_id')
            ->orderBy('last_done', 'DESC');
    }

    /**
     * Creates a scope to search all vessel sub category by the provided keyword
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('code', 'LIKE', "%$keyword%")
            ->orWhereHas('subCategory', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%");
            })
            ->orWhereHas('description', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%");
            });
    }


}
