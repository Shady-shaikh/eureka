<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class PurchaseOrder extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order';
    protected $primaryKey = 'purchase_order_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['created_at','party_name','receipt_type','series_no','fy_year','company_id','discount','status','remarks','posting_date','document_date','delivery_date','contact_person','ship_from','vendor_ref_no','purchase_order_id','purchase_order_counter','purchase_order_no','bill_date','party_id','party_details','financial_year','bill_no','amount_in_words','sub_total','cgst_total','sgst_utgst_total','igst_total','gst_grand_total','grand_total','rounded_off','bill_to_gst_no','company_gstin','vessel','port','date_of_arrival','date_of_departure','place_of_supply','tax_in_words','split_purchaseorder'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function purchaseorder_items()
    {
        return $this->hasMany(PurchaseOrderItems::class,'purchase_order_id');
    }

    public static function getPurchaseOrderGstReportData($start_date,$end_date){
        $data=PurchaseOrder::select('bill_no','bill_date','bill_to_name','bill_to_gst_no','sub_total','gst_rate','cgst_total','sgst_utgst_total','igst_total','grand_total')->whereBetween('bill_date',[$start_date,$end_date])
            ->get()->toArray();
        return $data;
    }

    public static function getPurchaseOrderReportData($start_date,$end_date){
        $data=PurchaseOrder::select('bill_no','bill_date','bill_to_name','bill_to_gst_no','bill_type','grand_total')->whereBetween('bill_date',[$start_date,$end_date])
            ->get()->toArray();
        return $data;
    }

    public function get_partyname(){
        return $this->hasOne(BussinessPartnerMaster::class,'business_partner_id','party_id');
    }

    public function get_ship_toaddress(){
        return $this->hasOne(BussinessPartnerAddress::class,'bp_address_id','ship_from');
    }

}
