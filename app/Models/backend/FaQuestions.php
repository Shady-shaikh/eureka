<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaQuestions extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'fa_questions';
    protected $primaryKey = 'fa_question_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fa_question','faq_id','visibility','fa_question_ans',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

}
