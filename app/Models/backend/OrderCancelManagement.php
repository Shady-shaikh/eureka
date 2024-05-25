<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCancelManagement extends Model
{
    public $table = 'order_cancel_days';
    protected $primaryKey = 'order_cancel_days_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_cancel_max_days','order_cancel_max_hours','order_cancel_note',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}
