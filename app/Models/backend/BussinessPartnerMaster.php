<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\backend\BussinessMasterType;

use Spatie\Permission\Models\Role;

use App\Models\backend\TermPayment;

class BussinessPartnerMaster extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'business_partner_master';
    protected $primaryKey = 'business_partner_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  [
        'business_partner_id', 'bp_channel', 'ase', 'latitude', 'longitude', 'business_partner_type',
        'residential_status', 'gst_reg_type', 'rcm_app', 'msme_reg', 'bp_code', 'bp_name', 'bp_organisation_type',
        'bp_category', 'bp_group', 'sales_manager', 'sales_officer', 'salesman', 'payment_terms_id',
        'credit_limit', 'gst_details', 'pricing_profile', 'shelf_life', 'area_id', 'route_id', 'beat_id',
        'created_at', 'updated_at', 'deleted_at', 'company_id', 'salesman_lat', 'salesman_long'
    ];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function paymentterms()
    {
        return $this->hasOne(TermPayment::class, 'payment_terms_id', 'payment_terms_id');
    }
    public function get_address()
    {
        return $this->hasOne(BussinessPartnerAddress::class, 'bussiness_partner_id', 'business_partner_id');
    }

    public function get_salesman()
    {
        return $this->hasOne(AdminUsers::class, 'admin_user_id', 'salesman');
    }

    public function get_pricing()
    {
        return $this->hasOne(Pricings::class, 'pricing_master_id', 'pricing_profile');
    }


    public function salesManager()
    {
        return $this->hasOne(AdminUsers::class, 'admin_user_id', 'sales_manager');
    }

    public function get_ase()
    {
        return $this->hasOne(AdminUsers::class, 'admin_user_id', 'ase');
    }

    public function salesOfficer()
    {
        return $this->hasOne(AdminUsers::class, 'admin_user_id', 'sales_officer');
    }


    public function get_beat()
    {
        return $this->hasOne(Beat::class, 'beat_id', 'beat_id');
    }

    public function get_group()
    {
        return $this->hasOne(Bpgroup::class, 'id', 'bp_group');
    }


    public function getpartnertype()
    {
        return $this->hasOne(BussinessMasterType::class, 'bussiness_master_type_id', 'business_partner_type')
            ->where('bussiness_master_type', '=', 'vendor');
    }
    public function get_partnerTypeName()
    {
        return $this->hasOne(BussinessMasterType::class, 'bussiness_master_type_id', 'business_partner_type');
    }


    // relation created by prathamesh - start
    public function get_partner_type_name()
    {
        return $this->hasOne(BussinessMasterType::class, 'bussiness_master_type_id', 'business_partner_type');
    }
    //  end

    public function get_org_type()
    {
        return $this->hasOne(BussinessPartnerOrganizationType::class, 'bp_organisation_type_id', 'bp_organisation_type');
    }
    public function get_category()
    {
        return $this->hasOne(BusinessPartnerCategory::class, 'business_partner_category_id', 'bp_channel');
    }
    public function getpartnertypecustomer()
    {
        return $this->hasOne(BussinessMasterType::class, 'bussiness_master_type_id', 'business_partner_type')
            ->where('bussiness_master_type', '=', 'customer');
    }

    public function get_company()
    {
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }
}
