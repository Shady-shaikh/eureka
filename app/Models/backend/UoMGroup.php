<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\DB;

class UoMGroup extends Model
{
  //use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'uom_group';
    protected $primaryKey = 'uom_group_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_item_id', 'at_qty', 'at_uom', 'base_qty', 'base_uom', 'is_active'
    ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function sluggable()
    {
        return [
            'product_slug' => [
                'source' => 'product_title'
            ]
        ];
    }

}
