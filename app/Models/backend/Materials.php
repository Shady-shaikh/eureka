<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materials extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'materials';
    protected $primaryKey = 'material_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'material_name', 'material_desc',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
