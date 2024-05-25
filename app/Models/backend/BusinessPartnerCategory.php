<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class BusinessPartnerCategory extends Model
{
  use Sluggable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'business_partner_category';
    protected $primaryKey = 'business_partner_category_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
      'business_partner_category','business_partner_category_name'
    ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];
    public function sluggable(): array
    {
        return [
            'category_slug' => [
                'source' => 'category_name',
                'onUpdate'=>true
            ]
        ];
    }

}
