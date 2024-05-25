<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MissingPayments extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'missing_payments';
    protected $primaryKey = 'payment_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id', 'transaction_id','amount','payment_date',
      'status','customer_name','email','data_dump','type',
      'payu_response','shipping_method_code','shipping_method_cost',
    ];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

}
