<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pricingladder extends Model
{
  // use SoftDeletes;
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'pricing_ladder';
  protected $primaryKey = 'id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'pricing_master_id', 'date', 'item_code', 'product_desc', 'visibility', 'bp_channel', 'brand_id',
    'sub_category_id', 'variant', 'buom_pack_size', 'sourcing', 'case_pallet', 'layer_pallet', 'unit_case',
    'final_sellp_dist', 'dist_margin_perc', 'dist_margin', 'gst_total_val_add', 'net_bill_price_dist', 'tts',
    'tts_wo_gst', 'tts_aft_sch', 'tts_wo_gst_af_scheme', 'actual_dt_margin', 'diff_in_margin', 'sub_d_margin_abs_exc',
    'sub_d_landing', 'sub_d_margin', 'ptr_af_sch', 'scheme_md', 'scheme', 'ptr', 'margin', 'retailer_margin',
    'derived_mrp', 'intended_mrp_exc', 'mrp'
  ];
}
