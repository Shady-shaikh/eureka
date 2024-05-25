<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotoffers extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='hotoffers';
    protected $primaryKey='hotoffers_id';

    protected $fillable=['col_type','short_order','first_image','first_image_url','second_image','second_image_url','third_image','third_image_url','fourth_image','fourth_image_url'];

}
