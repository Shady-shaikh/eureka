<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\backend\StorageLocations;
use App\Models\backend\BinManagement;

class BinTransfer extends Model
{

  use SoftDeletes;
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'bin_transfer';
  protected $primaryKey = 'id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'from_warehouse', 'from_bin', 'to_warehouse', 'to_bin',
    'sku', 'item_code', 'from_qty', 'qty', 'item_name', 'remarks', 'fy_year','company_id', 'user_id'
  ];

  public function get_from_warehouse_name()
  {
    return $this->hasOne(StorageLocations::class, 'storage_location_id', 'from_warehouse');
  }

  public function get_to_warehouse_name()
  {
    return $this->hasOne(StorageLocations::class, 'storage_location_id', 'to_warehouse');
  }

  public function get_from_bin_name()
  {
    return $this->hasOne(BinManagement::class, 'bin_id', 'from_bin');
  }

  public function get_to_bin_name()
  {
    return $this->hasOne(BinManagement::class, 'bin_id', 'to_bin');
  }
}
