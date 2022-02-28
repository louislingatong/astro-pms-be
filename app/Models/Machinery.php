<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machinery extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vessel_department_id',
        'code_name',
        'name',
        'machinery_model_id',
        'machinery_maker_id',
    ];

    use SoftDeletes;

    /**
     * Retrieves the department of the machinery
     *
     * @return BelongsTo VesselDepartment
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(VesselDepartment::class, 'vessel_department_id');
    }

    /**
     * Retrieves the model of the machinery
     *
     * @return BelongsTo MachineryModel
     */
    public function model(): BelongsTo
    {
        return $this->belongsTo(MachineryModel::class, 'machinery_model_id');
    }

    /**
     * Retrieves the maker of the machinery
     *
     * @return BelongsTo MachineryMaker
     */
    public function maker(): BelongsTo
    {
        return $this->belongsTo(MachineryMaker::class, 'machinery_maker_id');
    }

    /**
     * Retrieve all sub category under this machinery
     *
     * @return HasMany MachinerySubCategory[]
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(MachinerySubCategory::class);
    }

    /**
     * Creates a scope to search all machinery by the provided keyword
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('code_name', 'LIKE', "%$keyword%")
            ->orWhere('name', 'LIKE', "%$keyword%");
    }
}
