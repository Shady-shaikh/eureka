<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\backend\BussinessPartnerAddress;

class StorageLocations extends Model
{

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'storage_locations';
  protected $primaryKey = 'storage_location_id';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = [
    'storage_location_name',
    'location', 'address', 'zip_code', 'country', 'state', 'city', 'warehouse_address'
  ];

  // use SoftDeletes;
  // protected $dates = ['deleted_at'];

  public function get_warehouse_add_name()
  {
    return $this->hasOne(BussinessPartnerAddress::class, 'bp_address_id', 'warehouse_address');
  }

  public function company()
  {
    return $this->hasOne(Company::class, 'company_id', 'warehouse_address');
  }
}
