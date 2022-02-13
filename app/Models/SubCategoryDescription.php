<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubCategoryDescription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_category_id',
        'name'
    ];

    /**
     * Retrieves the sub category of the sub category description
     *
     * @return BelongsTo SubCategory
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    /**
     * Retrieves the vessel machinery sub category under this sub category description
     *
     * @return HasOne VesselMachinerySubCategory
     */
    public function vesselMachinerySubCategory(): HasOne
    {
        return $this->hasOne(VesselMachinerySubCategory::class, 'sub_category_id');
    }
}
