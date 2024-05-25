<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SizeChartImages extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'size_chart_images';
    protected $primaryKey = 'size_chart_image_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'size_chart_id', 'image_name',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];


}
