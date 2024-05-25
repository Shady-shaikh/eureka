<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schemes extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schemes';
    protected $primaryKey = 'schemes_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['scheme_id','scheme_title', 'min_product_qty','free_product_qty'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

}
