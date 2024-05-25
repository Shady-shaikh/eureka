<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


use Carbon\Carbon;
use App\Models\backend\ActivityLog;
use App\Models\backend\AdminUsers;
use App\Models\backend\Company;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\backend\SeriesMaster;
use App\Models\backend\Financialyear;
use Carbon\CarbonPeriod;
use Spatie\Permission\Models\Role;


if (!function_exists('formatted_date')) {
    function formatted_date($date)
    {
        $date = Carbon::parse($date)->format('d-m-Y');
        return $date;
    }
}

if (!function_exists('amount_in_words')) {
    function amount_in_words($number)
    {
        if (isset($_GET['amount_in_words'])) {
            $number = $_GET['amount_in_words'];
        }

        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }
        $Gn = floor($number / 10000000);  /* Crore */
        $number -= $Gn * 10000000;
        $kn = floor($number / 100000);     /* lakhs */
        $number -= $kn * 100000;
        $Hn = floor($number / 1000);      /* thousand */
        $number -= $Hn * 1000;
        $Dn = floor($number / 100);       /* Tens (deca) */
        $number = $number % 100;               /* Ones */
        $tn = floor($number / 10);
        $one = floor($number % 10);
        $res = "";
        if ($Gn) {
            $res .= amount_in_words($Gn) . " Crore";
        }
        if ($kn) {
            $res .= (empty($res) ? "" : " ") .
                amount_in_words($kn) . " Lakhs";
        }
        if ($Hn) {
            $res .= (empty($res) ? "" : " ") .
                amount_in_words($Hn) . " Thousand";
        }

        if ($Dn) {
            $res .= (empty($res) ? "" : " ") .
                amount_in_words($Dn) . " Hundred";
        }

        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($tn || $one) {
            if (!empty($res)) {
                $res .= " and ";
            }
            if ($tn < 2) {
                $res .= $ones[$tn * 10 + $one];
            } else {
                $res .= $tens[$tn];
                if ($one) {
                    $res .= "-" . $ones[$one];
                }
            }
        }
        // if (empty($res))
        // {
        //     $res = "zero";
        // }
        return $res;
    }

    //avtivity logs function
    if (!function_exists('captureActivity')) {
        function captureActivity($data)
        {
            // dd($data);
            $user_array = [];
            $id = Auth()->guard('admin')->user()->admin_user_id;
            $username = Auth()->guard('admin')->user()->first_name . ' ' . Auth()->guard('admin')->user()->last_name;
            $ses_id = Session::getId();

            $user_array = [
                'user_id' => $id,
                'user_name' => $username,
                'session_id' => $ses_id
            ];

            $row = array_merge($user_array, $data);
            $log = new ActivityLog();
            $log->fill($row);
            $log->save();
        }
    }


    if (!function_exists('captureActivityupdate')) {
        function captureActivityupdate($new_changes, $log)
        {
            // dd($new_changes,$log);
            $user_array = [];
            $id = Auth()->guard('admin')->user()->admin_user_id;
            $username = Auth()->guard('admin')->user()->first_name . ' ' . Auth()->guard('admin')->user()->last_name;
            $ses_id = Session::getId();
            $user_array = ['user_id' => $id, 'user_name' => $username, 'session_id' => $ses_id];

            $new_changes = array_filter($new_changes, function ($key) {
                return $key !== 'updated_at';
            }, ARRAY_FILTER_USE_KEY);

            $dataAttributes = array_map(function ($value, $key) {
                return $key . '="' . $value . '"';
            }, array_values($new_changes), array_keys($new_changes));

            $dataAttributes = implode(' ', $dataAttributes);


            $log['description'] = $log['description'] .  ' Change=> '  . $dataAttributes;
            // $log['description'] = $log['description'] . htmlspecialchars(' <span style="color: red;">To</span> ') . $dataAttributes;
            // dd($log['description']);

            $row = array_merge($user_array, $log);
            $log = new ActivityLog();
            $log->fill($row);
            $log->save();
        }
    }



    if (!function_exists('get_series_number')) {
        function get_series_number($moduleName, $company_id = null)
        {
            $company_id = $company_id ?? session('company_id');
            $moduleName = str_replace(' ', '', strtolower(trim($moduleName)));
            $existingModules = DB::table('modules')->pluck('name', 'id')->map(function ($name) {
                return  str_replace(' ', '', strtolower(trim($name)));
            })->toArray();
            $module_id = array_search($moduleName, $existingModules);
            //now get series number
            $series_data = SeriesMaster::where('module', $module_id)
                ->when(!empty($company_id), function ($q) use ($company_id) {
                    $q->where([
                        'company_id' => $company_id
                    ]);
                })
                ->first();

            return $series_data->series_number ?? '';
        }
    }


    if (!function_exists('get_transaction_type')) {
        function get_transaction_type($moduleName, $company_id = null)
        {
            $moduleName = str_replace(' ', '', strtolower(trim($moduleName)));
            $existingModules = DB::table('modules')->pluck('name', 'id')->map(function ($name) {
                return  str_replace(' ', '', strtolower(trim($name)));
            })->toArray();
            $module_id = array_search($moduleName, $existingModules);
            //now get series number
            $series_data = SeriesMaster::where('module', $module_id)
                ->when(!empty($company_id), function ($q) use ($company_id) {
                    $q->where([
                        'company_id' => $company_id
                    ]);
                })
                ->first();

            return $series_data->transaction_type ?? '';
        }
    }


    if (!function_exists('getCommonArrays')) {
        function getCommonArrays()
        {
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'];

            $month_data = collect(CarbonPeriod::create(now()->startOfMonth(), '1 month', now()->addMonths(11)->startOfMonth()))
                ->map(function ($item) {
                    return date('F', strtotime($item));
                })->all();

            $year_data = collect(CarbonPeriod::create(now()->startOfYear(), '1 year', now()->addYears(9)->startOfYear()))
                ->map(function ($item) {
                    return date('Y', strtotime($item));
                })->all();

            return [
                'days' => array_combine($days, $days),
                'week' => array_combine($weeks, $weeks),
                'month' => array_combine($month_data, $month_data),
                'year' => array_combine($year_data, $year_data),
            ];
        }
    }

    // usama 29-01-2024 - check if current user is super admin
    if (!function_exists('is_superAdmin')) {
        function is_superAdmin()
        {
            $role = Role::where([
                'id' => Auth()
                    ->guard('admin')
                    ->user()->role,
                'department_id' => 1,
            ])->first();

            return !empty($role) ? 1 : 0;
        }
    }

    // usama_23-02-2024- fetch id with name or create new for excel
    //moreData is to fetch first name last name and onlyUpdate for to return only primary key if data exists
    //parentData for entriest parent user id while creating user
    if (!function_exists('getOrCreateIdUnified')) {
        function getOrCreateIdUnified($modelOrTable, $fieldName, $excelValue, $primaryKeyName, $extra_id = null, $moreData = null, $parentData = null, $onlyUpdate = null)
        {
            $value = trim(addslashes($excelValue));
            $isModel = is_string($modelOrTable) && class_exists($modelOrTable);

            if ($isModel) {
                // Using Eloquent model
                // $record = $not_exact
                //     ? $modelOrTable::whereRaw('LOWER(' . $fieldName . ') LIKE ?', [strtolower($value)])->first()
                //     : $modelOrTable::where($fieldName, $value)->first();
                $record = $modelOrTable::whereRaw('LOWER(' . $fieldName . ') LIKE ?', [strtolower($value)])->first();
                if (!empty($value)) {
                    if (!$record && empty($onlyUpdate)) {
                        // Record doesn't exist, create a new one
                        $newRecord = new $modelOrTable([$fieldName => $value]);

                        if ($modelOrTable == 'App\Models\backend\Gst' && !empty($extra_id)) {
                            $newRecord->gst_percent = $extra_id;
                        }

                        if ($modelOrTable == 'App\Models\backend\State' && !empty($extra_id)) {
                            $newRecord->country_id = $extra_id;
                        }

                        if ($modelOrTable == 'App\Models\backend\City' && !empty($extra_id)) {
                            $newRecord->state_id = $extra_id;
                        }

                        if ($modelOrTable == 'App\Models\backend\Pricings' && !empty($extra_id)) {
                            $newRecord->pricing_type = $extra_id;
                        }

                        if ($modelOrTable == 'App\Models\backend\Bpgroup' && !empty($extra_id)) {
                            $newRecord->bp_channel_id = $extra_id;
                        }

                        if ($modelOrTable == 'App\Models\backend\AdminUsers' && !empty($extra_id)) {

                            $check_user = AdminUsers::where(['email' => $value, 'account_status' => 1])->first();

                            if (!empty($check_user)) {
                                return;
                            }

                            $role = Role::where('department_id', $extra_id)->first();
                            $newRecord->role = $role->id;

                            if (!empty($parentData)) {
                                $newRecord->parent_users = $parentData;
                            }
                            if (!empty($moreData)) {
                                $fullName = explode(" ", trim(addslashes($moreData)));
                                $fName = $fullName[0];
                                $lName = $fullName[1] ?? '';
                                $newRecord->first_name = $fName;
                                $newRecord->last_name = $lName;
                            }

                            $newRecord->account_status = 1;
                            $newRecord->company_id = Company::first()->company_id;
                            $newRecord->password = "Pass@123";
                        }

                        $newRecord->save();

                        // Return the new ID
                        return $newRecord->$primaryKeyName;
                    }

                    // Record exists, return its ID
                    return $record->$primaryKeyName;
                }
            } else {
                // Using database table
                $record = DB::table($modelOrTable)->where($fieldName, $value)->first();

                if (!empty($value)) {
                    if (!$record) {
                        // Record doesn't exist, create a new one
                        $newRecordData = [
                            $fieldName => $value,
                        ];

                        $newRecordId = DB::table($modelOrTable)->insertGetId($newRecordData);

                        // Return the new ID
                        return $newRecordId;
                    }

                    // Record exists, return its ID
                    return $record->$primaryKeyName;
                }
            }
        }
    }

    // usama_12-03-2024-to fetch fy_year from company
    if (!function_exists('get_fy_year')) {
        function get_fy_year($company_id)
        {
            $company = Company::where('company_id', $company_id)->first();

            if ($company->ay_type == 'fi_year') {
                $currentDate = Carbon::now();
                $startOfYear = Carbon::create($currentDate->year, 4, 1);
                $endOfYear = Carbon::create($currentDate->year + 1, 3, 31);

                if ($currentDate->between($startOfYear, $endOfYear)) {
                    $Financialyear = $currentDate->year . '-' . substr(($currentDate->year + 1), -2);
                } else {
                    $Financialyear = ($currentDate->year - 1) . '-' . substr($currentDate->year, -2);
                }
            } elseif ($company->ay_type == 'cal_year') {
                $Financialyear = Carbon::now()->year;
            }

            return $Financialyear;
        }
    }


    if (!function_exists('upload_docs')) {
        function upload_docs($docs, $prefix = '')
        {
            $imageName = $prefix . '_' . time() . '.' . $docs->getClientOriginalExtension();
            if (!file_exists(public_path('backend-assets/docs'))) {
                mkdir(public_path('backend-assets/docs'), 0777, true);
            }
            $docs->move(public_path('backend-assets/docs'), $imageName);

            return $imageName;
        }
    }

    if (!function_exists('is_image')) {
        function is_image($doc)
        {
            $filePath = asset('public/backend-assets/docs') . '/' . $doc;
            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];

            if (in_array(strtolower($fileExtension), $imageExtensions)) {
                return ['status' => 1, 'path' => $filePath];
            } else {
                return ['status' => 0, 'path' => $filePath];
            }
        }
    }

    // usama_send email
    if (!function_exists('send_email')) {
        function send_email($to, $subject, $body)
        {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Specify your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'abuosamas@parasightsolutions.com'; // SMTP username
            $mail->Password = 'para@123'; // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('abuosamas@parasightsolutions.com', 'Eureka');
            $mail->addAddress($to); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
        }
    }
}
