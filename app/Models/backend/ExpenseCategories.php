<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class ExpenseCategories extends Model
{
  // use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'expense_categories';
    protected $primaryKey = 'expense_category_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'expense_category_id','expense_category_name', 'visibility'
    ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    

}
