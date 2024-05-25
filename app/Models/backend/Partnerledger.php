<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\backend\StorageLocations;
use App\Models\backend\Products;

class Partnerledger extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'partner_ledger';
  protected $primaryKey = 'id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'party_id','doc_no','total','cgst_amount','sgst_utgst_amount','igst_amount','gst_rate',
    'warehouse_id', 'bin_id', 'batch_no','gst_amount','gross_total',
    'transaction_type','sku', 'user_id', 'fy_year','company_id', 'item_code', 'qty', 'unit_price', 'manufacturing_date', 'expiry_date', 'added_qty', 'final_qty'
  ];


  public function get_unit_price()
  {
    return $this->hasOne(Products::class, 'item_code', 'item_code');
  }

  public function get_warehouse()
  {
    return $this->hasOne(StorageLocations::class, 'storage_location_id', 'warehouse_id');
  }

  public function get_partner()
  {
    return $this->hasOne(BussinessPartnerMaster::class, 'business_partner_id', 'party_id');
  }

  public function get_bin()
  {
    return $this->hasOne(BinManagement::class, 'bin_id', 'bin_id');
  }
}
