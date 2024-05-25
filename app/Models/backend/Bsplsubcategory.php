<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Bsplsubcategory extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bspl_sub_cateogry';
    protected $primaryKey = 'bsplsubcat_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['bsplsubcat_id','bsplcat_id', 'bsplheads_id','bspl_subcat_name', 'created_at', 'updated_at', 'deleted_at'];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];


    public function bspl_cat_name(){
        return $this->hasOne(Bsplcategory::class,'bsplcat_id','bsplcat_id');
    }





    public function bspl_type(){
        return $this->hasMany(Bspltype::class,'bsplsubcat_id','bsplsubcat_id');
    }



}
