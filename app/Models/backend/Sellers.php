<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sellers extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sellers';
    protected $primaryKey = 'seller_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'seller_name', 'seller_email','seller_mobile_no','seller_address',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
