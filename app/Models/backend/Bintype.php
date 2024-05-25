<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\backend\StorageLocations;

class Bintype extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bin_type';
    protected $primaryKey = 'bin_type_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
      ];



}
