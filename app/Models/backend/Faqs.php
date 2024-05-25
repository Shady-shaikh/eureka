<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faqs extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'faqs';
    protected $primaryKey = 'faq_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'faq_name', 'faq_desc','visibility','sort_order',
      ];

    // use SoftDeletes;
    // protected $dates = ['deleted_at'];

    public function questions()
    {
      return $this->hasMany(FaQuestions::class,'faq_id','faq_id');
    }
}
