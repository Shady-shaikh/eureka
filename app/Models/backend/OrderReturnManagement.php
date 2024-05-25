<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderReturnManagement extends Model
{
    public $table = 'order_returns';
    protected $primaryKey = 'order_return_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_return_max_days','order_return_max_hours',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}
