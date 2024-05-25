<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SizeChartTypes extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'size_chart_types';
    protected $primaryKey = 'size_chart_type_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'size_chart_type_name', 'size_chart_field_id',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function chartfields()
    {
      return $this->hasMany(SizeChartFields::class,'size_chart_field_id','size_chart_field_id'); //field , type
    }
}
