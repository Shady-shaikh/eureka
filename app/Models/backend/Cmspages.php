<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Cmspages extends Model
{
  use Sluggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $table = 'cms_pages';
     protected $primaryKey = 'cms_pages_id';
     protected $fillable = [
       'cms_pages_title','cms_pages_content', 'cms_pages_top','cms_pages_footer',
       'show_hide','cms_slug','column_type','cms_pages_link','contactus_form_flag',
     ];

     public function sluggable()
     {
         return [
             'cms_slug' => [
                 'source' => 'cms_pages_title'
             ]
         ];
     }

}
