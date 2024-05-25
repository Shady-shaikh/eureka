<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingChargesManagement extends Model
{
    public $table = 'shipping_charges_management';
    protected $primaryKey = 'shipping_charges_management_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status','purchase_min_limit'
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}
