<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturers extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'manufacturers';
    protected $primaryKey = 'manufacturer_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'manufacturer_name', 'manufacturer_email','manufacturer_mobile_no','manufacturer_address',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
