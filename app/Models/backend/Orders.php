<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\frontend\User;

class Orders extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id','user_id','mark','mark_value','one_mark_value','commission_percent','gst_percent','discount_percent','gst_value','discounted_value','total'];

    //use SoftDeletes;
  //  protected $dates = ['deleted_at'];
    public function users()
    {
      return $this->hasOne(User::class,'id','user_id');
    }
}
