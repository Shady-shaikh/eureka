<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FrontendImages extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='frontend_images';
    protected $primaryKey='frontend_image_id';

    protected $fillable=['image_code','url','image_name','image_url'];

}
