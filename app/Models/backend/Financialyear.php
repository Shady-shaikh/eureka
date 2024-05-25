<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Financialyear extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'financial_year';
    protected $primaryKey = 'financial_year_id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['financial_year_id', 'order_fulfilment_counter', 'ar_invoice_counter', 'bin_transfer_counter', 'year', 'company_id', 'billoflading_counter', 'invoice_counter', 'delivery_order_counter', 'gatepass_counter', 'manifest_counter', 'active'];

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
