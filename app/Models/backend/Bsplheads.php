<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Bsplheads extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bspl_heads';
    protected $primaryKey = 'bsplheads_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['bsplheads_id', 'has_subcat','bspl_heads_name', 'created_at', 'updated_at', 'deleted_at'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function get_all_cat_data(){
        return $this->hasMany(Bsplcategory::class,'bsplheads_id','bsplheads_id');
    }

  



}
