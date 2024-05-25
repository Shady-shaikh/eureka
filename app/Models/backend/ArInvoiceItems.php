<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ArInvoiceItems extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ar_invoice_items';
    protected $primaryKey = 'order_fulfillment_item_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['party_id', 'mrp', 'gst_amount', 'uom', 'final_qty', 'batch_no', 'gst_rate', 'sku', 'item_code', 'order_fulfillment_id', 'discount_item', 'price_af_discount', 'gross_total', 'order_booking_id', 'item_name', 'hsn_sac', 'qty', 'taxable_amount', 'cgst_rate', 'cgst_amount', 'sgst_utgst_rate', 'sgst_utgst_amount', 'igst_rate', 'igst_amount', 'total', 'storage_location_id'];

    // ALTER TABLE `goods_service_receipts_items` ADD `storage_location_id` INT(11) NULL AFTER `total`; 

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    //public function
    public function ar_invoice_batches()
    {
        return $this->hasMany(ArInvoiceBatches::class, 'order_fulfillment_item_id');
    }

    public function get_ar()
    {
        return $this->hasOne(ArInvoice::class, 'order_fulfillment_id', 'order_fulfillment_id');
    }


    public function get_product()
    {
        return $this->hasOne(Products::class, 'item_code', 'item_code');
    }
}
