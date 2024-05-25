<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class SubCategories extends Model
{
  use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'subcategories';
    protected $primaryKey = 'subcategory_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id','subcategory_name', 'visibility','subcategory_description','has_sub_subcategories'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function sluggable():array
    {
        return [
            'sub_category_slug' => [
                'source' => 'subcategory_name',
                'onUpdate'=>true
            ]
        ];
    }

}
