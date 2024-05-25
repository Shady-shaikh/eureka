<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemTypes extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'item_types';
    protected $primaryKey = 'item_type_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'item_type_name', 
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
