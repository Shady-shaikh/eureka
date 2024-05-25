<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecialDeals extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'specialdeals';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_date', 'end_date',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
