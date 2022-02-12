<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategory extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'machinery_id',
        'name'
    ];

    /**
     * Retrieves the machinery of the sub category
     *
     * @return BelongsTo Machinery
     */
    public function machinery(): BelongsTo
    {
        return $this->belongsTo(Machinery::class, 'machinery_id');
    }

    /**
     * Retrieve all descriptions under this sub category
     *
     * @return HasMany SubCategoryDescription[]
     */
    public function descriptions(): HasMany
    {
        return $this->hasMany(SubCategoryDescription::class);
    }

    /**
     * Creates a scope to search all sub category by the provided keyword
     *
     * @param Builder $query
     * @param string $keyword
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where('name', 'LIKE', "%$keyword%")
            ->orWhereHas('machinery', function ($q) use ($keyword) {
                $q->where('code_name', 'LIKE', "%$keyword%")
                    ->orWhere('name', 'LIKE', "%$keyword%");
            });
    }
}
