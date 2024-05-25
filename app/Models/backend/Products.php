<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\DB;

class Products extends Model
{
  use Sluggable, SoftDeletes;
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'product_item_sku_master';
  protected $primaryKey = 'product_item_id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'item_type_id', 'item_code', 'item_description', 'consumer_description', 'brand_id',
    'dimensions_length_uom_id', 'dimensions_net_uom_id', 'combi_type', 'combi_type_int',
    'category_id', 'sub_category_id', 'variant', 'buom_pack_size', 'uom_id', 'unit_case', 'sku',
    'hsncode_id', 'batch', 'expiry_date', 'shelf_life_number', 'shelf_life', 'sourcing', 'case_pallet',
    'layer_pallet', 'dimensions', 'dimensions_unit_pack', 'dimensions_length',
    'dimensions_width', 'dimensions_height', 'dimensions_net_weight',
    'dimensions_gross_weight', 'ean_barcode', 'mrp', 'gst_id', 'visibility', 'product_thumb',
    'consumer_desc', 'product_desc', 'storage_location_id',
  ];

  // use SoftDeletes;
  // protected $dates = ['deleted_at'];
  public function sluggable(): array
  {
    return [
      'product_slug' => [
        'source' => 'product_title'
      ]
    ];
  }

  public function product_images()
  {
    return $this->hasMany(ProductImages::class, 'product_id', 'product_id');
  }

  public function get_uom()
  {
    return $this->hasOne(UoMs::class, 'uom_id', 'uom_id');
  }

  public function get_side_uom()
  {
    return $this->hasOne(UoMs::class, 'uom_id', 'dimensions_length_uom_id');
  }
  public function get_wt_uom()
  {
    return $this->hasOne(UoMs::class, 'uom_id', 'dimensions_net_uom_id');
  }

  public function get_gst()
  {
    return $this->hasOne(Gst::class, 'gst_id', 'gst_id');
  }

  public function category()
  {
    return $this->hasOne(Categories::class, 'category_id', 'category_id');
  }

  public function sub_category()
  {
    return $this->hasOne(SubCategories::class, 'subcategory_id', 'sub_category_id');
  }
  public function variants()
  {
    return $this->hasOne(Variant::class, 'id', 'variant');
  }

  public function item_type()
  {
    return $this->hasOne(ItemTypes::class, 'item_type_id', 'item_type_id');
  }

  public function get_combi_type()
  {
    return $this->hasOne(Combitype::class, 'id', 'combi_type');
  }

  public function brand()
  {
    return $this->hasOne(Brands::class, 'brand_id', 'brand_id');
  }

  public function manufacturer()
  {
    return $this->hasOne(Manufacturers::class, 'manufacturer_id', 'manufacturer_id');
  }

  public function size()
  {
    return $this->hasOne(Sizes::class, 'size_id', 'size_id');
  }

  public function product_variants()
  {
    return $this->hasMany(ProductVariants::class, 'product_id', 'product_id')->with(['size', 'color']);
  }


  public function color()
  {
    return $this->hasOne(Colors::class, 'color_id', 'color_id');
  }

  public function product_filters()
  {
    return $this->hasMany(ProductFilters::class, 'product_id', 'product_id');
  }

  public function relatedproducts()
  {
    return $this->hasMany(RelatedProducts::class);
  }
  public function brands()
  {
    return $this->hasOne(Brands::class, 'brand_id', 'brand_id');
  }

  public function hsncode()
  {
    return $this->hasOne(HSNCodes::class, 'hsncode_id', 'hsncode_id');
  }
}
