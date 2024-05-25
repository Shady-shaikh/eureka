<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Models\backend\ProductVariantImages;

class ProductVariants extends Model
{
  use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_variants';
    protected $primaryKey = 'product_variant_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'product_title', 'product_sub_title', 'product_price', 'product_discounted_price', 'product_discount',
      'product_qty', 'product_desc', 'product_material', 'product_fit_type', 'product_pattern',
      'product_neck_type', 'product_sleeve_type', 'product_sleeve_length', 'product_type', 'product_length',
      'product_occasion', 'product_fabric_transparency', 'product_stretch', 'product_closure', 'product_distress',
      'product_waist_rise', 'product_waist_band', 'product_collar', 'product_style', 'product_fade',
      'product_shade', 'product_basic_trend', 'product_suitable_season', 'product_no_of_pkt', 'product_ideal_for',
      'product_set_of', 'product_weight', 'package_length', 'package_width', 'package_height',
      'product_eligible_for_return', 'product_wash_instructions','visibility','product_generic_name','country_id',
      'seller_id','manufacturer_id','packer_id','importer_id','category_id',
      'sub_category_id','sub_sub_category_id','category_slug','sub_category_slug','sub_sub_category_slug',
      'product_specification','product_disclaimer','product_sku',
      'product_id','color_id','size_id','brand_id','product_discount_type',

    ];

    // use SoftDeletes; ,'color_id','size_id',
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
      return $this->hasMany(ProductImages::class,'product_id','product_id')->limit(6);
    }

    public function country()
    {
      return $this->hasOne(Countries::class,'id','country_id');
    }

    public function seller()
    {
      return $this->hasOne(Sellers::class,'seller_id','seller_id');
    }

    public function packer()
    {
      return $this->hasOne(Packers::class,'packer_id','packer_id');
    }

    public function importer()
    {
      return $this->hasOne(Importers::class,'importer_id','importer_id');
    }

    public function manufacturer()
    {
      return $this->hasOne(Manufacturers::class,'manufacturer_id','manufacturer_id');
    }

    public function color()
    {
      return $this->hasOne(Colors::class,'color_id','color_id');
    }

    public function size()
    {
      return $this->hasOne(Sizes::class,'size_id','size_id');
    }

    public function product_variant_images()
    {
      return $this->hasMany(ProductVariantImages::class,'product_variant_id','product_variant_id')->limit(6);
    }

    public function product()
    {
      return $this->hasOne(Products::class,'product_id','product_id');
    }

    public function color_product_variant_images()
    {
      return $this->hasMany(ProductVariantImages::class,'product_id','product_id')->limit(6);
    }


}
