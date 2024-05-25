<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilterTypes extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filter_types';
    protected $primaryKey = 'filter_type_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filter_type_name', 'filter_type_code','visibility',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];


}
