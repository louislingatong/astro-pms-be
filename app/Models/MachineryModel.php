<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MachineryModel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Retrieves the machinery under this model
     *
     * @return HasOne Machinery
     */
    public function machinery(): HasOne
    {
        return $this->hasOne(Machinery::class, 'machinery_model_id');
    }
}
