<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Expensemaster extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'expense';
    protected $primaryKey = 'expense_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  ['expense_id', 'expense_name', 'gl_code', 'bsplheads_id', 'bsplcat_id', 'bsplsubcat_id', 'bsplstype_id', 'created_at', 'updated_at', 'deleted_at'];
    use SoftDeletes;
    protected $dates = ['deleted_at'];


    public function get_type()
    {
        return $this->belongsTo(Bspltype::class, 'bsplstype_id');
    }
    public function get_sub_cat()
    {
        return $this->belongsTo(Bsplsubcategory::class, 'bsplsubcat_id');
    }
    public function get_cat()
    {
        return $this->belongsTo(Bsplcategory::class, 'bsplcat_id');
    }
    public function get_heads()
    {
        return $this->belongsTo(Bsplheads::class, 'bsplheads_id');
    }
    public function get_gl()
    {
        return $this->belongsTo(GLCodes::class, 'gl_code','gl_code');
    }
}
