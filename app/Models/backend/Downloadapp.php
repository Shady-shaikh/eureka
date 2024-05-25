<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Downloadapp extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table='downloadapp';
    protected $primaryKey='downloadapp_id';

    protected $fillable=['url','image_url'];

}
