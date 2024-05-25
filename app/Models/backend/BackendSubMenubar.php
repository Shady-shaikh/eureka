<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackendSubMenubar extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'backend_submenubar';
    protected $primaryKey = 'submenu_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['submenu_id', 'submenu_name','submenu_controller_name','has_sub_submenu','submenu_action_name','visibility','menu_id','submenu_permissions','sort_order'];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
