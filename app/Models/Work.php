<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Work extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vessel_machinery_sub_category_id',
        'last_done',
        'instructions',
        'remarks',
        'creator_id',
    ];

    /**
     * Retrieves the vessel machinery sub category of the work
     *
     * @return BelongsTo VesselMachinerySubCategory
     */
    public function vesselMachinerySubCategory(): BelongsTo
    {
        return $this->belongsTo(VesselMachinerySubCategory::class, 'vessel_machinery_sub_category_id');
    }

    /**
     * Retrieves the creator of the work
     *
     * @return BelongsTo User
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
