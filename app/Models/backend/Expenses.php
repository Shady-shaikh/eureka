<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\DB;

class Expenses extends Model
{
  use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'expense_master';
    protected $primaryKey = 'expense_master_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'expense_category_id','expense_sub_category_id','expense_type_id','expense_sub_type_id',
      'service_expense','goods_expense','rental_deposits_banking','hr_admin',
      'Infra_stationary','miscellaneous'
    ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function sluggable()
    {
        return [
            'product_slug' => [
                'source' => 'product_title'
            ]
        ];
    }

    public function product_images()
    {
      return $this->hasMany(ProductImages::class,'product_id','product_id');
    }

    public function category()
    {
      return $this->hasOne(Categories::class,'category_id','category_id');
    }

    public function sub_category()
    {
      return $this->hasOne(SubCategories::class,'subcategory_id','sub_category_id');
    }

    public function item_type()
    {
      return $this->hasOne(ItemTypes::class,'item_type_id','item_type_id');
    }

    public function brand()
    {
      return $this->hasOne(Brands::class,'brand_id','brand_id');
    }

    public function manufacturer()
    {
      return $this->hasOne(Manufacturers::class,'manufacturer_id','manufacturer_id');
    }

    public function size()
    {
      return $this->hasOne(Sizes::class,'size_id','size_id');
    }

    public function product_variants()
    {
      return $this->hasMany(ProductVariants::class,'product_id','product_id')->with(['size','color']);
    }

    public function color()
    {
      return $this->hasOne(Colors::class,'color_id','color_id');
    }

    public function product_filters()
    {
      return $this->hasMany(ProductFilters::class,'product_id','product_id');
    }

    public function relatedproducts()
    {
      return $this->hasMany(RelatedProducts::class);
    }
    public function brands()
    {
      return $this->hasOne(Brands::class,'brand_id','brand_id');
    }

    public function hsncode()
    {
      return $this->hasOne(HSNCodes::class,'hsncode_id','hsncode_id');
    }
}
