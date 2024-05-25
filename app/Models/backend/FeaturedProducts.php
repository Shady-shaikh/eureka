<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class FeaturedProducts extends Model
{
  use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'home_page_featured_products';
    protected $primaryKey = 'home_page_featured_product_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'home_page_featured_product_name', 'home_page_featured_product_type','visibility',
        'sort_order','home_page_featured_product_code','product_id',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function sluggable()
    {
        return [
            'home_page_featured_product_code' => [
                'source' => 'home_page_featured_product_name'
            ]
        ];
    }

    public function questions()
    {
      return $this->hasMany(FaQuestions::class,'home_page_featured_product_id','home_page_featured_product_id');
    }
}
