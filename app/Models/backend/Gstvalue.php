<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gstvalue extends Model
{
    use HasFactory;

    protected $table = 'gst_value';
    protected $primaryKey = 'id';

    protected $fillable = ['value'];

}
