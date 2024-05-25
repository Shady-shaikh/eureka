<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimsJourney extends Model
{
    use HasFactory;

    protected $table = 'claims_journey';
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected  $fillable =  [
        'user', 'status', 'claim_id', 'remarks', 'supporting_docs'
    ];

    public function get_user()
    {
        return $this->hasOne(AdminUsers::class, 'admin_user_id', 'user');
    }

    public function get_status()
    {
        return $this->hasOne(ClaimStatus::class, 'id', 'status');
    }
}
