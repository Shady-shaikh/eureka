<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class FilterValues extends Model
{
  use Sluggable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filter_values';
    protected $primaryKey = 'filter_value_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filter_value','filter_id','visibility','product_page_visibility',
        'filter_min_value','filter_max_value',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function sluggable()
    {
        return [
            'filter_value_slug' => [
                'source' => 'filter_value',
                'onUpdate'=>true
            ]
        ];
    }

    public function color()
    {
      return $this->hasOne(Colors::class,'color_id','reference_id');
    }

}
