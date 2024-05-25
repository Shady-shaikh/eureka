<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HSNCodes extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hsncodes';
    protected $primaryKey = 'hsncode_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'hsncode_name', 'hsncode_desc'
    ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function material()
    {
      return $this->hasOne(Materials::class,'material_id','material_id');
    }
    public function category()
    {
      return $this->hasOne(Categories::class,'category_id','category_id');
    }
    public function subcategory()
    {
      return $this->hasOne(SubCategories::class,'subcategory_id','subcategory_id');
    }
    public function childcategory()
    {
      return $this->hasOne(SubSubCategories::class,'sub_subcategory_id','sub_subcategory_id');
    }
}
