<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackendMenubar extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'backend_menubar';
    protected $primaryKey = 'menu_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['menu_name', 'menu_controller_name','menu_action_name','has_submenu','visibility','menu_icon','permissions','sort_order'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
