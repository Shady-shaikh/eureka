<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFilters extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_filters';
    protected $primaryKey = 'product_filter_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filter_value_id', 'product_id', 'filter_id',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function filtervalues()
    {
      return $this->hasMany(FilterValues::class,'filter_id','filter_id');
    }
}
