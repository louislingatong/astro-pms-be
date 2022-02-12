<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VesselOwner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Retrieve all vessel under this owner
     *
     * @return HasMany Vessel[]
     */
    public function vessels(): HasMany
    {
        return $this->hasMany(Vessel::class);
    }
}
