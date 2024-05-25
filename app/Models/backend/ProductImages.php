<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImages extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_images';
    protected $primaryKey = 'product_image_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id', 'image_name',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];


}
