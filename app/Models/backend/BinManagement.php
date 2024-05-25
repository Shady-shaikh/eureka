<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\backend\StorageLocations;
use App\Models\backend\Bintype;

class BinManagement extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bin_mangement';
    protected $primaryKey = 'bin_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bin_name',
        'bin_type','warehouse_id'
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function get_warehouse_name(){
      return $this->hasOne(StorageLocations::class,'storage_location_id','warehouse_id');
    }

    public function get_bin(){
      
      return $this->hasOne(Bintype::class,'bin_type_id','bin_type');
    }

}
