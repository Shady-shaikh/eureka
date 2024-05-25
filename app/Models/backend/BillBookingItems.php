<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class BillBookingItems extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bill_booking_items';
    protected $primaryKey = 'bill_booking_item_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bill_booking_id','description','invoice_ref_no','expense_id','bsplstype_id','bsplsubcat_id','bsplcat_id','bsplheads_id','gl_code','amount','cgst_amount','sgst_utgst_amount','igst_amount','total_value','gst_rate','gst_amount','cgst_rate','sgst_utgst_rate','igst_rate'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // public function purchase_order_batches(){
    //     return $this->hasMany(PurchaseOrderBatches::class,'purchase_order_item_id');
    // }

    public function get_expense_name()
    {
        return $this->belongsTo(Expensemaster::class, 'expense_id');
    }

    
    public function get_type()
    {
        return $this->belongsTo(Bspltype::class, 'bsplstype_id');
    }
    public function get_sub_cat()
    {
        return $this->belongsTo(Bsplsubcategory::class, 'bsplsubcat_id');
    }
    public function get_cat()
    {
        return $this->belongsTo(Bsplcategory::class, 'bsplcat_id');
    }
    public function get_heads()
    {
        return $this->belongsTo(Bsplheads::class, 'bsplheads_id');
    }
    public function get_gl()
    {
        return $this->belongsTo(GLCodes::class, 'gl_code','gl_code');
    }

}
