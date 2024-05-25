<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomePageSectionTypes extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'home_page_section_types';
    protected $primaryKey = 'home_page_section_type_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'home_page_section_type_name', 'home_page_section_field_id','home_page_section_type_code',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function home_page_section_fields()
    {
      return $this->hasMany(HomePageSectionFields::class,'home_page_section_field_id','home_page_section_field_id'); //field , type
    }
}
