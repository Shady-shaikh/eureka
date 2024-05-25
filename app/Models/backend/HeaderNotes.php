<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HeaderNotes extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'header_notes';
    protected $primaryKey = 'header_note_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'header_note_name', 'header_note_text','visibility',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
