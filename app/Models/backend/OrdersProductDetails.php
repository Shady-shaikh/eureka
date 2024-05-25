<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersProductDetails extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders_product_details';
    protected $primaryKey = 'orders_product_details_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'orders_product_details_id','product_id', 'qty','product_price',
      'order_id','referral_id','distributor_id','package_cancel_return_status',
      'package_cancel_return_dump',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

}
