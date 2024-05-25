<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomePageSectionFields extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'home_page_section_fields';
    protected $primaryKey = 'home_page_section_field_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'home_page_section_field_name', 'home_page_section_field_code',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
