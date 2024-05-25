<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficSource extends Model
{
    use HasFactory;

    protected $table = 'traffic source';
    protected $primaryKey = 'user_id';


    protected $fillable=['user_id','email','REMOTE_ADDR','HTTP_USER_AGENT','user_os','device','id','traffic_source'];
}
