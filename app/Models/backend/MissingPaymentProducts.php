<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MissingPaymentProducts extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'missing_payment_product';
    protected $primaryKey = 'missing_payment_product_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'payment-id', 'product_id','product_price','qty',
      'discount','currency','total_cost','shipping_method_code',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

}
