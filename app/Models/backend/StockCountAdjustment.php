<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockCountAdjustment extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_qty_storage';
    protected $primaryKey = 'product_qty_storage _id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_qty_storage _id',
        'product_item_id','storage_location_id','qty'
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function product()
    {
      return $this->hasOne(Products::class,'product_item_id','product_item_id');
    }

    public function storagelocation()
    {
      return $this->hasOne(StorageLocations::class,'storage_location_id','storage_location_id');
    }

}
