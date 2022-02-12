<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RankType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Retrieves the rank under this type
     *
     * @return HasOne Rank
     */
    public function rank(): HasOne
    {
        return $this->hasOne(Rank::class, 'rank_type_id');
    }
}
