<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Spatie\Permission\Models\Role;

class TermPayment extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payment_terms';
    protected $primaryKey = 'payment_terms_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['payment_terms_id', 'term_type'];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function bussiness_partner(){
        //return $this->HasOne(TermPayment::class, 'payment_terms_id','business_partner_id');
        }



}
