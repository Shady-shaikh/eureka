<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class BillBooking extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bill_booking';
    protected $primaryKey = 'bill_booking_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['vendor_id','type','status','company_id','bill_date','invoice_ref_date','fy_year','company_id','doc_no','posting_date'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function billbooking_items()
    {
        return $this->hasMany(BillBookingItems::class,'bill_booking_id');
    }


    public function get_partyname(){
        return $this->hasOne(BussinessPartnerMaster::class,'business_partner_id','vendor_id');
    }


    public function get_series_data(){
        return $this->hasOne(SeriesMaster::class,'id','series_no');
    }



    

}
