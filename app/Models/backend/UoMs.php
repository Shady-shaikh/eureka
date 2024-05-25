<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UoMs extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'uoms';
    protected $primaryKey = 'uom_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uom_name', 
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
