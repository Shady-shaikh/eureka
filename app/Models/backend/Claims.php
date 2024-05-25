<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Claims extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'claims';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  [
        'company_id', 'doc_date', 'party_id', 'bp_channel', 'bp_group', 'retail_dn_date',
        'retail_dn_no', 'retail_dn_desc', 'importer_dn_no', 'importer_dn_date', 'importer_dn_desc',
        'bar_code', 'activity_month', 'ret_dn_rec_date', 'location', 'brand', 'expense_type',
        'claim_type', 'importer_deb_note', 'debit_value', 'gst_value', 'total_debit_note', 'doc_no',
        'retailer_vendor_dn', 'distributor_dn', 'invoice_supp_docs', 'approval_supp_docs', 'is_submitted',
        'status', 'user', 'remarks', 'supporting_docs', 'created_by'

    ];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function get_group()
    {
        return $this->hasOne(Bpgroup::class, 'id', 'bp_group');
    }

    public function get_category()
    {
        return $this->hasOne(BusinessPartnerCategory::class, 'business_partner_category_id', 'bp_channel');
    }

    public function get_user()
    {
        return $this->hasOne(AdminUsers::class, 'admin_user_id', 'created_by');
    }

    public function get_status()
    {
        return $this->hasOne(ClaimStatus::class, 'id', 'status');
    }


    public function get_company()
    {
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }

    public function get_party()
    {
        return $this->hasOne(BussinessPartnerMaster::class, 'business_partner_id', 'party_id');
    }

    public function brands()
    {
        return $this->hasOne(Brands::class, 'brand_id', 'brand');
    }

    public function expense()
    {
        return $this->hasOne(ExpenseTypes::class, 'expense_type_id', 'expense_type');
    }
}
