<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scheme extends Model
{
  use SoftDeletes;
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'scheme';
  protected $primaryKey = 'id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'bp_category', 'bp_channel', 'pricing_master_id', 'bp_group', 'brand_id', 'sub_category_id', 'variant', 'buom_pack_size', 'scheme',
  ];

  public function brand()
  {
    return $this->hasOne(Brands::class, 'brand_id', 'brand_id');
  }

  public function format()
  {
    return $this->hasOne(SubCategories::class, 'subcategory_id', 'sub_category_id');
  }

  public function variants()
  {
    return $this->hasOne(Variant::class, 'id', 'variant');
  }
}
