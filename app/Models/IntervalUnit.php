<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntervalUnit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Retrieve all intervals under this unit
     *
     * @return HasMany Interval[]
     */
    public function intervals(): HasMany
    {
        return $this->hasMany(Interval::class);
    }
}
