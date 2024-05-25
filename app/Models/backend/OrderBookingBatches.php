<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class OrderBookingBatches extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_booking_batches';
    protected $primaryKey = 'order_booking_batches_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['order_booking_id', 'order_booking_item_id', 'storage_location_id','batch_no', 'manufacturing_date', 'expiry_date'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

}
