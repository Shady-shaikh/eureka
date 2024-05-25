<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Proforma extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'proforma';
    protected $primaryKey = 'proforma_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['proforma_id','proforma_counter','proforma_no','bill_date','party_id','party_details','financial_year','bill_no','amount_in_words','sub_total','cgst_total','sgst_utgst_total','igst_total','gst_grand_total','grand_total','rounded_off','party_name','bill_to_gst_no','company_gstin','vessel','port','date_of_arrival','date_of_departure','place_of_supply','tax_in_words','split_proforma'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function proforma_items()
    {
        return $this->hasMany(ProformaItems::class,'invoice_id','proforma_id');
    }

    public static function getproformaGstReportData($start_date,$end_date){
        $data=Proforma::select('bill_no','bill_date','bill_to_name','bill_to_gst_no','sub_total','gst_rate','cgst_total','sgst_utgst_total','igst_total','grand_total')->whereBetween('bill_date',[$start_date,$end_date])
            ->get()->toArray();
        return $data;
    }

    public static function getproformaReportData($start_date,$end_date){
        $data=Proforma::select('bill_no','bill_date','bill_to_name','bill_to_gst_no','bill_type','grand_total')->whereBetween('bill_date',[$start_date,$end_date])
            ->get()->toArray();
        return $data;
    }

}
