<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\backend\BussinessPartnerAddress;

class YearManagement extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'academic_year';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year_string','year_id',
      ];



}
