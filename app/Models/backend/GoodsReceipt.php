<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class GoodsReceipt extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'goods_receipt';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['gst_amount', 'item_code', 'uom', 'final_qty', 'sku', 'gst_rate', 'remarks', 'discount', 'batch_no', 'fy_year', 'company_id', 'manufacturing_date', 'expiry_date', 'gross_total', 'item_name', 'hsn_sac', 'qty', 'taxable_amount', 'cgst_rate', 'cgst_amount', 'sgst_utgst_rate', 'sgst_utgst_amount', 'igst_rate', 'igst_amount', 'total', 'storage_location_id'];


    public function get_warehouse_name()
    {
        return $this->hasOne(StorageLocations::class, 'storage_location_id', 'storage_location_id');
    }

    public function get_product()
    {
        return $this->hasOne(Products::class, 'item_code', 'item_code');
    }
}
