<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductRevision extends Model
{
    use SoftDeletes;
    protected $table = 'products_revision';
    protected $primaryKey = 'product_revision_id ';



    protected $fillable = [
        'product_item_id', 'item_type_id', 'item_code', 'item_description', 'consumer_description', 'brand_id',
        'dimensions_length_uom_id','dimensions_net_uom_id','sku',
        'category_id', 'sub_category_id', 'variant', 'buom_pack_size', 'uom_id', 'unit_case',
        'hsncode_id', 'batch', 'expiry_date', 'shelf_life_number', 'shelf_life', 'sourcing', 'case_pallet',
        'layer_pallet', 'dimensions', 'dimensions_unit_pack', 'dimensions_length',
        'dimensions_width', 'dimensions_height', 'dimensions_net_weight',
        'dimensions_gross_weight', 'ean_barcode', 'mrp', 'gst_id', 'visibility', 'product_thumb',
        'consumer_desc', 'product_desc', 'storage_location_id',
    ];


    public function product_images()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'product_id');
    }

    public function category()
    {
        return $this->hasOne(Categories::class, 'category_id', 'category_id');
    }

    public function sub_category()
    {
        return $this->hasOne(SubCategories::class, 'subcategory_id', 'sub_category_id');
    }

    public function item_type()
    {
        return $this->hasOne(ItemTypes::class, 'item_type_id', 'item_type_id');
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
