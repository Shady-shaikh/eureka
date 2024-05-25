<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersCounter extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders_counter';
    protected $primaryKey = 'orders_counter_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['orders_counter_id','orders_counter'];

    //use SoftDeletes;
  //  protected $dates = ['deleted_at'];

}
