<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rank extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'short_name',
        'name',
        'rank_type_id'
    ];

    /**
     * Retrieves the type of the rank
     *
     * @return BelongsTo RankType
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(RankType::class, 'rank_type_id');
    }
}
