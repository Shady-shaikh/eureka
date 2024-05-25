<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Bankingpayment extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banking_payment';
    protected $primaryKey = 'banking_payment_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['vendor_id','transaction_type','overdue_range','payment_type','fy_year','company_id','bill_date','doc_no','posting_date','bill_booking_item_ids','net_total','tax_total','gorss_total','bank'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];




    public function get_partyname(){
        return $this->hasOne(BussinessPartnerMaster::class,'business_partner_id','vendor_id');
    }


    public function get_series_data(){
        return $this->hasOne(SeriesMaster::class,'id','series_no');
    }



    

}
