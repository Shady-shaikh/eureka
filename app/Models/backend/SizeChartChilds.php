<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SizeChartChilds extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'size_chart_childs';
    protected $primaryKey = 'size_chart_child_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'size_chart_id','size_id','size_type','length_in',
        'chest_in','shoulder_in','sleeve_length_in','waist_in','neck_in','hip_in',
        'inseam_length_in','brand_size','rise','bottom','chest'
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function size()
    {
      return $this->hasOne(Sizes::class,'size_id','size_id');
    }
}
