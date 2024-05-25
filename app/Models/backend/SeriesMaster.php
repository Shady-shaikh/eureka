<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class SeriesMaster extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'series_master';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['series_number','module','transaction_type','company_id', 'created_at', 'updated_at', 'deleted_at'];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];




}
