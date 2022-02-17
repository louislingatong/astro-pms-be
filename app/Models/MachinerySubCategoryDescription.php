<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachinerySubCategoryDescription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'machinery_sub_category_id',
        'name'
    ];
}
