<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ApInvoiceBatches extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ap_invoice_batches';
    protected $primaryKey = 'goods_service_receipts_batches_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['goods_service_receipt_id', 'storage_location_id','goods_service_receipts_item_id', 'batch_no', 'manufacturing_date', 'expiry_date'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

}
