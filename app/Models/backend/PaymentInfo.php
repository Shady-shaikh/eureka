<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $table = 'payment_info';
     protected $primaryKey = 'payment_id';
     protected $fillable = ['user_id', 'transaction_id','amount','payment_data','status','customer_name','email','data_dump','type','payment_tracking_code','payment_mode','pickup_flag'];

}
