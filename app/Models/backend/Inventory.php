<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\backend\StorageLocations;
use App\Models\backend\Products;

class Inventory extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'inventory';
  protected $primaryKey = 'id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'warehouse_id', 'bin_id', 'batch_no', 'blocked_qty', 'doc_no', 'sku', 'remarks', 'fy_year', 'company_id', 'user_id', 'item_code', 'qty', 'unit_price', 'manufacturing_date', 'expiry_date'
  ];

  public function get_unit_price()
  {
    return $this->hasOne(Products::class, 'item_code', 'item_code');
  }

  public function get_warehouse()
  {
    return $this->hasOne(StorageLocations::class, 'storage_location_id', 'warehouse_id');
  }

  public function get_bin()
  {
    return $this->hasOne(BinManagement::class, 'bin_id', 'bin_id');
  }

  public function get_product()
  {
    return $this->hasOne(Products::class, 'item_code', 'item_code');
  }

  // public function get_bin_data()
  // {
  //   return $this->hasOne(BinManagement::class, 'bin_id', 'bin_id');
  // }
}
