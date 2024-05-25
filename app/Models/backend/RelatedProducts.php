<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatedProducts extends Model
{
    use HasFactory;

    protected $table='related_products';
    protected $primaryKey='id';

    protected $fillable=['product_id','related_product_list_id'];

    public function product(){
        return $this->belongsTo(Products::class);
    }
}
