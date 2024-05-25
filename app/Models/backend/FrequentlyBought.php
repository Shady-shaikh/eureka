<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrequentlyBought extends Model
{
    use HasFactory;

    protected $table='frequently_bought';
    protected $primaryKey='id';

    protected $fillable=['product_id','frequently_bought_together_id'];

    public function product(){
        return $this->belongsTo(Products::class);
    }
}
