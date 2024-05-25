<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disclaimers extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'disclaimers';
    protected $primaryKey = 'disclaimer_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disclaimer_description', 
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
