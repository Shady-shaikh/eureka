<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoginManagement extends Model
{
    public $table = 'login_managements';
    protected $primaryKey = 'login_management_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login_management_facebook','login_management_google','login_management_login',
        'login_management_signup',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
}
