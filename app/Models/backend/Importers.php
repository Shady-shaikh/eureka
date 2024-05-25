<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Importers extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'importers';
    protected $primaryKey = 'importer_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importer_name', 'importer_email','importer_mobile_no','importer_address',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
