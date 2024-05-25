<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    use HasFactory;

    protected $table='footers';
    protected $primaryKey='id';


    protected $fillable=['footer_description','footer_category_description','footer_image1','category_id','sub_subcategory_id','footer_image2','footer_image3','footer_image4'];

    public function categories()
    {
        return $this->hasMany(FooterIds::class,'footer_id','id');
    }
    public function footerid()
    {
        return $this->hasMany(FooterIds::class);
    }
}
