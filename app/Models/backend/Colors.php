<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colors extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'colors';
    protected $primaryKey = 'color_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'color_name', 'color_code',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
