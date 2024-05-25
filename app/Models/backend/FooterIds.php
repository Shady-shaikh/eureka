<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterIds extends Model
{
    use HasFactory;

    protected $table='footerids';
    protected $primaryKey='id';

    protected $fillable=['footer_id','category_id','sub_subcategory_id'];

    public function footer(){
        return $this->belongsTo(Footer::class);
    }
}
