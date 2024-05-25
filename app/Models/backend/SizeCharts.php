<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SizeCharts extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'size_charts';
    protected $primaryKey = 'size_chart_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'size_chart_name','size_ids','visibility','product_page_visibility',
        'size_chart_desc','size_chart_image','size_type','length_in',
        'chest_in','shoulder_in','sleeve_lenght_in','waist_in','neck_in','hip_in',
        'inseam_length_in','size_chart_footer_desc',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function chart_childs()
    {
      return $this->hasMany(SizeChartChilds::class,'size_chart_id','size_chart_id');
    }

    public function chart_images()
    {
      return $this->hasMany(SizeChartImages::class,'size_chart_id','size_chart_id');
    }

    public function size_chart_type()
    {
      return $this->hasOne(SizeChartTypes::class,'size_chart_type_id','size_type');
    }
}
