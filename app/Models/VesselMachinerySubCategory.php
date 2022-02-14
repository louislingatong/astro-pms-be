<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'vessel_machinery_id',
        'code',
        'sub_category_id',
        'sub_category_description_id',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vessel_mchnry_sub_categories';

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
     * @return BelongsTo SubCategory
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    /**
     * Retrieves the description of the vessel sub category
     *
     * @return BelongsTo SubCategoryDescription
     */
    public function description(): BelongsTo
    {
        return $this->belongsTo(SubCategoryDescription::class, 'sub_category_description_id');
    }

    /**
     * Retrieve all works under this vessel machinery sub category
     *
     * @return HasMany Work[]
     */
    public function works(): HasMany
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
