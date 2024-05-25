<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SizeChartFields extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'size_chart_fields';
    protected $primaryKey = 'size_chart_field_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'size_chart_field_name', 'size_chart_field_code',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
