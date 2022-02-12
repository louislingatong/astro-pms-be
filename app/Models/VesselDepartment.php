<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VesselDepartment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Retrieve all machineries under this vessel department
     *
     * @return HasMany Machinery[]
     */
    public function machineries(): HasMany
    {
        return $this->hasMany(Machinery::class);
    }
}
