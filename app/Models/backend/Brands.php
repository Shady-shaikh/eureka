<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brands extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'brands';
    protected $primaryKey = 'brand_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brand_name', 'brand_desc',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
