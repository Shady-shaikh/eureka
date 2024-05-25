<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class ExpenseSubCategories extends Model
{
  use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'expense_subcategories';
    protected $primaryKey = 'expense_subcategory_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['expense_category_id','expense_subcategory_name', 'visibility'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function sluggable():array
    {
        return [
            'sub_category_slug' => [
                'source' => 'subcategory_name',
                'onUpdate'=>true
            ]
        ];
    }

}
