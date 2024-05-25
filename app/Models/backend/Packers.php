<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packers extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packers';
    protected $primaryKey = 'packer_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'packer_name', 'packer_email','packer_mobile_no','packer_address',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
