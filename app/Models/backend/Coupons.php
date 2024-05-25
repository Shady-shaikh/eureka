<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupons extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons';
    protected $primaryKey = 'coupon_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coupon_title', 'coupon_code','coupon_type','value',
        'start_date','end_date','status','coupon_purchase_limit',
        'coupon_usage_limit','copoun_desc'
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
