<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class BeatCalender extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'beat_calender';
    protected $primaryKey = 'beat_cal_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  [
        'beat_cal_id', 'salesman', 'area_id', 'route_id', 'beat_id', 'outlet', 'sales_execu', 'ase', 'asm',
        'beat_week', 'beat_day', 'beat_month', 'beat_year',
        'created_at', 'updated_at', 'deleted_at'
    ];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function get_beat()
    {
        return $this->hasOne(Beat::class, 'beat_id', 'beat_id');
    }
}
