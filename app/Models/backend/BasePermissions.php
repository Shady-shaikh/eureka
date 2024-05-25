<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BasePermissions extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'base_permissions';
    protected $primaryKey = 'base_permission_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['base_permission_name', 'guard_name',];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
