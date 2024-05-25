<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDeliveryManagement extends Model
{
    public $table = 'order_delivery_management';
    protected $primaryKey = 'order_delivery_management_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_delivery_max_days','order_delivery_max_hours',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}
