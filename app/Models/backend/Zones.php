<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Zones extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'zones';
    protected $primaryKey = 'zone_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['zone_id', 'zone_name', 'created_at', 'updated_at', 'deleted_at'];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];


    public function get_all_data(){
        return $this->hasMany(Route::class,'zone_id','zone_id');
    }



}
