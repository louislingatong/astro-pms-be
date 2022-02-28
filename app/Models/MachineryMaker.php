<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MachineryMaker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Creates a scope to search all machinery maker by the provided keyword
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
