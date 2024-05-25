<?php

namespace App\Models\frontend;

use App\Models\frontend\Ideas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdeaRevisionImages extends Model
{
  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'idea_revision_images';
  protected $primaryKey = 'image_id ';

  /**
   * Attributes that should be mass-assignable.
   *
   * @var array
   */
  protected $fillable = ['image_id', 'file_name', 'idea_uni_id', 'image_path', 'image_link', 'created_at', 'updated_at', 'deleted_at'];
  public function idea()
  {
    return $this->belongsTo(IdeaRevision::class, 'idea_uni_id');
  }
}
