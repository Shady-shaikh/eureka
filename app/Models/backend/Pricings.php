<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\DB;

class Pricings extends Model
{
  use SoftDeletes;
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'pricing_master';
  protected $primaryKey = 'pricing_master_id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'pricing_name', 'pricing_type', 'status', 'company_id', 'scheme', 'subd_margin','distributor_margin', 'margin', 'bp_category', 'bp_channel', 'bp_group'
  ];

  public function get_channel()
  {
    return $this->hasOne(BusinessPartnerCategory::class, 'business_partner_category_id', 'bp_channel');
  }

  public function get_pricing_items()
  {
    return $this->hasMany(PricingItem::class, 'pricing_master_id', 'pricing_master_id');
  }

  public function get_sub_d_margin()
  {
    return $this->hasOne(Subdmargin::class, 'id', 'subd_margin');
  }
  public function get_scheme()
  {
    return $this->hasMany(Scheme::class, 'pricing_master_id', 'scheme');
  }

  public function get_margin()
  {
    return $this->hasMany(Margin::class, 'pricing_master_id', 'margin');
  }
}
