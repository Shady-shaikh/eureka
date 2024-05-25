<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Filters extends Model
{
  use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filters';
    protected $primaryKey = 'filter_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filter_name', 'filter_desc','visibility','product_page_visibility','sort_order',
        'filter_type','category_id','subcategory_id','sub_subcategory_id',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function sluggable()
    {
        return [
            'filter_slug' => [
                'source' => 'filter_name',
                'onUpdate'=>true
            ]
        ];
    }

    public function filtervalues()
    {
      return $this->hasMany(FilterValues::class,'filter_id','filter_id');
    }

    public function category()
    {
      return $this->hasOne(Categories::class,'category_id','category_id');
    }

    public function subcategory()
    {
      return $this->hasOne(SubCategories::class,'subcategory_id','subcategory_id');
    }

    public function childcategory()
    {
      return $this->hasOne(SubSubCategories::class,'sub_subcategory_id','sub_subcategory_id');
    }
}
