<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ApInvoiceItems extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ap_invoice_items';
    protected $primaryKey = 'goods_service_receipts_item_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['party_id','gst_amount','gst_rate','item_code','sku','goods_service_receipt_id','discount_item','price_af_discount','gross_total','purchase_order_id','item_name','hsn_sac','qty','taxable_amount','cgst_rate','cgst_amount','sgst_utgst_rate','sgst_utgst_amount','igst_rate','igst_amount','total','storage_location_id'];

    // ALTER TABLE `goods_service_receipts_items` ADD `storage_location_id` INT(11) NULL AFTER `total`; 

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    //public function
    public function goods_service_receipts_batches(){
        return $this->hasMany(ApInvoiceBatches::class,'goods_service_receipts_item_id');
    }
}
