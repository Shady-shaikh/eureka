<?php

namespace App\Models\backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
  use HasFactory;

  protected $table = 'company';
  protected $primaryKey = 'company_id';


  protected $fillable = [
    'name', 'email', 'mobile_no', 'pincode', 'state','distributor_margin', 'country', 'batch_system', 'zone_id',
    'address', 'gstno', 'bankdetail', 'address_line1', 'db_type','is_subd', 'ay_type',
    'address_line2', 'landmark', 'city', 'district', 'ac_start_date', 'ac_end_date', 'is_backdated_date'
  ];


  public static function upload_logo($request)
  {
    if ($request->hasFile('logo')) {
      $destinationPath = public_path('backend-assets/images/company_logo');
      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777);
      }
      $image = $request->file('logo');
      $name = time() . '.' . $image->getClientOriginalExtension();
      $image->move($destinationPath, $name);
      return $name;
    }
  }

  public static function upload_signature($request)
  {
    if ($request->hasFile('signature')) {
      $destinationPath = public_path('backend-assets/images/company_signature');
      if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0777);
      }
      $image = $request->file('signature');
      $name = time() . '.' . $image->getClientOriginalExtension();
      $image->move($destinationPath, $name);
      return $name;
    }
  }

  function get_zone()
  {
    return $this->hasOne(Zones::class, 'zone_id', 'zone_id');
  }
}
