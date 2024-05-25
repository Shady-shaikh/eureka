<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combitype extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'combi_types';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'variant_desc',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
