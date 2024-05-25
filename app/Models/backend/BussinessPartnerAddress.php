<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Spatie\Permission\Models\Role;

class BussinessPartnerAddress extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bp_address';
    protected $primaryKey = 'bp_address_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['bp_address_id', 'bussiness_partner_id', 'gst_no', 'bp_address_name', 'address_type', 'building_no_name', 'street_name', 'landmark', 'city', 'pin_code', 'district', 'state', 'country'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function getCountry()
    {
        return $this->hasOne(Country::class, 'country_id', 'country');
    }

    public function getState()
    {
        return $this->hasOne(State::class, 'id', 'state');
    }

    public function getDistrict()
    {
        return $this->hasOne(City::class, 'city_id', 'district');
    }
}
