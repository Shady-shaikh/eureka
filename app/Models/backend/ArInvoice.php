<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ArInvoice extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ar_invoice';
    protected $primaryKey = 'order_fulfillment_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['place_of_supply', 'of_id', 'is_bill_booking_done', 'discount', 'fy_year', 'company_id', 'customer_inv_no', 'ar_inv_no', 'contact_person', 'status', 'trans_type', 'remarks', 't_down_pmnt', 'applied_amt', 'posting_date', 'due_date', 'delivery_date', 'ship_from', 'customer_ref_no', 'order_booking_id', 'order_booking_counter', 'purchase_order_no', 'bill_date', 'receipt_type', 'po_document', 'party_id', 'party_details', 'financial_year', 'bill_no', 'amount_in_words', 'sub_total', 'cgst_total', 'sgst_utgst_total', 'igst_total', 'gst_grand_total', 'grand_total', 'rounded_off', 'party_name', 'bill_to_gst_no', 'company_gstin', 'vessel', 'port', 'date_of_arrival', 'date_of_departure', 'place_of_supply', 'tax_in_words', 'split_purchaseorder', 'bill_to'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function goodsservicereceipts_items()
    {
        return $this->hasMany(ArInvoiceItems::class, 'order_fulfillment_id')->with('ar_invoice_batches');
    }

    public static function getPurchaseOrderGstReportData($start_date, $end_date)
    {
        $data = ArInvoice::select('bill_no', 'bill_date', 'bill_to_name', 'bill_to_gst_no', 'sub_total', 'gst_rate', 'cgst_total', 'sgst_utgst_total', 'igst_total', 'grand_total')->whereBetween('bill_date', [$start_date, $end_date])
            ->get()->toArray();
        return $data;
    }

    public static function getPurchaseOrderReportData($start_date, $end_date)
    {
        $data = ArInvoice::select('bill_no', 'bill_date', 'bill_to_name', 'bill_to_gst_no', 'bill_type', 'grand_total')->whereBetween('bill_date', [$start_date, $end_date])
            ->get()->toArray();
        return $data;
    }

    public function get_partyname()
    {
        return $this->hasOne(BussinessPartnerMaster::class, 'business_partner_id', 'party_id');
    }


    public function get_bill_toaddress()
    {
        return $this->hasOne(BussinessPartnerAddress::class, 'bp_address_id', 'bill_to');
    }
    public function get_ship_toaddress()
    {
        return $this->hasOne(BussinessPartnerAddress::class, 'bp_address_id', 'ship_from');
    }

    public function get_company()
    {
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }
}
