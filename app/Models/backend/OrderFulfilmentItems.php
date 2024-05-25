<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class OrderFulfilmentItems extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_fulfilment_items';
    protected $primaryKey = 'order_fulfillment_item_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['party_id', 'gst_amount', 'uom', 'final_qty', 'gst_rate', 'batch_no', 'item_code', 'sku', 'discount_item', 'price_af_discount', 'gross_total', 'order_fulfillment_id', 'order_booking_id', 'item_name', 'hsn_sac', 'og_qty', 'fulfil_qty', 'qty', 'taxable_amount', 'cgst_rate', 'cgst_amount', 'sgst_utgst_rate', 'sgst_utgst_amount', 'igst_rate', 'igst_amount', 'total', 'storage_location_id'];

    // ALTER TABLE `goods_service_receipts_items` ADD `storage_location_id` INT(11) NULL AFTER `total`; 

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    //public function
    public function order_fulfilment_batches()
    {
        return $this->hasMany(OrderFulfilmentBatches::class, 'order_fulfillment_item_id');
    }

    public function get_product()
    {
        return $this->hasOne(Products::class, 'item_code', 'item_code');
    }
}
