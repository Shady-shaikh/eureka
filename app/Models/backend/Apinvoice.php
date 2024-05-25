<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Apinvoice extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ap_invoice';
    protected $primaryKey = 'goods_service_receipt_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['place_of_supply','gr_id','is_bill_booking_done','fy_year','company_id','discount','vendor_inv_no','ap_inv_no','contact_person','status','trans_type','remarks','t_down_pmnt','applied_amt','posting_date','due_date','delivery_date','ship_from','vendor_ref_no','purchase_order_id','purchase_order_counter','purchase_order_no','bill_date','receipt_type','po_document','party_id','party_details','financial_year','bill_no','amount_in_words','sub_total','cgst_total','sgst_utgst_total','igst_total','gst_grand_total','grand_total','rounded_off','party_name','bill_to_gst_no','company_gstin','vessel','port','date_of_arrival','date_of_departure','place_of_supply','tax_in_words','split_purchaseorder','bill_to'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function goodsservicereceipts_items()
    {
        return $this->hasMany(ApInvoiceItems::class,'goods_service_receipt_id')->with('goods_service_receipts_batches');
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

    public function get_bill_toaddress(){
        return $this->hasOne(BussinessPartnerAddress::class,'bp_address_id','bill_to');
    }
    public function get_ship_toaddress(){
        return $this->hasOne(BussinessPartnerAddress::class,'bp_address_id','ship_from');
    }

}
