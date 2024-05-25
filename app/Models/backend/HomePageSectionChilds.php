<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomePageSectionChilds extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'home_page_section_childs';
    protected $primaryKey = 'home_page_section_child_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'home_page_section_id','home_page_section_child_title','home_page_section_child_sub_title',
        'home_page_section_child_footer','home_page_section_child_url',
        'home_page_section_child_images','visibility','home_page_section_child_footer_title',
        'home_page_section_child_footer_sub_title',


      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function size()
    {
      return $this->hasOne(Sizes::class,'size_id','size_id');
    }
}
