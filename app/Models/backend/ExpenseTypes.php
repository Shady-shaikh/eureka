<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class ExpenseTypes extends Model
{
  // use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'expense_types';
    protected $primaryKey = 'expense_type_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'expense_type_name', 'visibility'
    ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    

}
