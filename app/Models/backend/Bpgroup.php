<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Bpgroup extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bp_group';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['id', 'name', 'created_at', 'updated_at', 'deleted_at'];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
