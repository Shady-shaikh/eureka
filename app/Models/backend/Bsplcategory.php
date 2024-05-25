<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Bsplcategory extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bspl_cateogry';
    protected $primaryKey = 'bsplcat_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['bsplcat_id', 'bsplheads_id','has_subcat','bspl_cat_name', 'created_at', 'updated_at', 'deleted_at'];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function bspl_head_name(){
        return $this->hasOne(Bsplheads::class,'bsplheads_id','bsplheads_id');
    }


    public function get_all_subcat_data(){
        return $this->hasMany(Bsplsubcategory::class,'bsplcat_id','bsplcat_id');
    }


}
