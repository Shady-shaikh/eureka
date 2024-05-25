<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class OrderBookingItems extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_booking_items';
    protected $primaryKey = 'order_booking_item_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['gst_rate', 'mrp', 'margin', 'scheme', 'gst_amount', 'uom', 'final_qty', 'item_code', 'sku', 'order_booking_item_id', 'storage_location_id', 'discount_item', 'price_af_discount', 'gross_total', 'order_booking_id', 'item_name', 'hsn_sac', 'qty', 'taxable_amount', 'cgst_rate', 'cgst_amount', 'sgst_utgst_rate', 'sgst_utgst_amount', 'igst_rate', 'igst_amount', 'total'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function order_booking_batches()
    {
        return $this->hasMany(OrderBookingBatches::class, 'order_booking_item_id');
    }

    public function get_product()
    {
        return $this->hasOne(Products::class, 'item_code', 'item_code');
    }
}
