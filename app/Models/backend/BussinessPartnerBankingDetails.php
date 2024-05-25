<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Spatie\Permission\Models\Role;

class BussinessPartnerBankingDetails extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banking_details';
    protected $primaryKey = 'banking_details_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['banking_details_id', 'acc_holdername','bussiness_partner_id', 'bank_name', 'bank_branch', 'ifsc', 'ac_number', 'bank_address'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];




}
