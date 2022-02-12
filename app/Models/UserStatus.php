<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Retrieve all users under this status
     *
     * @return HasMany User[]
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
