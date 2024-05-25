<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignCategoryFilters extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'assign_category_filters';
    protected $primaryKey = 'assign_category_filter_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filter_ids', 'filter_value_ids','filter_level','category_id','sub_category_id',
        'sub_sub_category_id',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    // public function filtervalues()
    // {
    //   return $this->hasMany(FilterValues::class,'filter_id','filter_id');
    // }
}
