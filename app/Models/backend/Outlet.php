<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Outlet extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'outlet';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  [
        'outlet_name', 'building_no_name', 'street_name', 'landmark', 'country', 'state', 'district', 'city', 'pin_code',
        'phone', 'salesman', 'area_id', 'route_id', 'beat_id', 'beat_day', 'sales_execu', 'ase', 'asm',
        'created_at', 'updated_at', 'deleted_at'
    ];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function get_beat()
    {
        return $this->hasOne(Beat::class, 'beat_id', 'beat_id');
    }


}
