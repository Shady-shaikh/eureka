<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomePageSections extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'home_page_sections';
    protected $primaryKey = 'home_page_section_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'home_page_section_name','size_ids','visibility',
        'home_page_section_footer_desc','visibility','home_page_section_type_id',
        'home_page_section_code','home_page_section_title', 'home_page_section_sub_title',
        'home_page_section_priority','home_page_section_no_prod','home_page_section_footer_title',
        'home_page_section_footer_sub_title','home_page_section_url', 'home_page_section_start_date',
        'home_page_section_end_date','padding_top','padding_bottom',


      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function section_childs()
    {
      return $this->hasMany(HomePageSectionChilds::class,'home_page_section_id','home_page_section_id');
    }

    public function chart_images()
    {
      return $this->hasMany(SizeChartImages::class,'home_page_section_id','home_page_section_id');
    }

    public function home_page_section_type()
    {
      return $this->hasOne(HomePageSectionTypes::class,'home_page_section_type_id','home_page_section_type_id');
    }
}
