<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\DB;

class ProductQtyStorage extends Model
{
  //use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_qty_storage';
    protected $primaryKey = 'product_qty_storage_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_item_id', 'item_code', 'qty', 'storage_location_id'

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
