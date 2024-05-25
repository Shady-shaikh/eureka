<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Incentives extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'incentives';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  [
        'brand_id', 'format_id', 'product_id', 'month', 'amount',
        'created_at', 'updated_at', 'deleted_at'
    ];
    // use SoftDeletes;
    // protected $dates = ['deleted_at'];


    public function brand()
    {
      return $this->hasOne(Brands::class, 'brand_id', 'brand_id');
    }

    public function sub_category()
    {
      return $this->hasOne(SubCategories::class, 'subcategory_id', 'format_id');
    }

    public function product()
    {
      return $this->hasOne(Products::class, 'product_item_id', 'product_id');
    }
}
