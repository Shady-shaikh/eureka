<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class ProformaItems extends Authenticatable
{
    use HasFactory,Notifiable,HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_items';
    protected $primaryKey = 'invoice_item_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['invoice_item_id','invoice_id','item_name','hsn_sac','qty','taxable_amount','cgst_rate','cgst_amount','sgst_utgst_rate','sgst_utgst_amount','igst_rate','igst_amount','total'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];

}
