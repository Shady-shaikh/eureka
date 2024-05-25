<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecentlyViewed extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recentlyviewed';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'product_id',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
