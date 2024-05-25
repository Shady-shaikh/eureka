<?php

namespace App\Http\Controllers\backend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\backend\AdminUsers;
use App\Models\backend\Focusmap;
use App\Models\backend\Area;
use App\Models\backend\Route as Routes;
use App\Models\backend\Beat;
use App\Models\backend\BeatCalender;
use App\Models\backend\Company;
use App\Models\backend\Outlet;
use App\Models\backend\OrderBooking;
use App\Models\backend\BussinessPartnerAddress;
use App\Models\backend\BussinessPartnerMaster;
use App\Models\backend\City;
use App\Models\backend\Country;
use App\Models\backend\Financialyear;
use App\Models\backend\Gst;
use App\Models\backend\OrderBookingItems;
use App\Models\backend\OrderBookingItemsTemp;
use App\Models\backend\OrderBookingTemp;
use App\Models\backend\PricingItem;
use App\Models\backend\Pricings;
use App\Models\backend\Products;
use App\Models\backend\Margin;
use App\Models\backend\Pricingladder;
use App\Models\backend\Scheme;
use Illuminate\Http\Request;

use App\Models\backend\SeriesMaster;
use App\Models\backend\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\backend\TrafficSource;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class ApiController extends Controller
{




  public function login()
  {


    // Attempt to log the user in
    if (Auth::guard('admin')->attempt(['email' => $_REQUEST['email'], 'password' => $_REQUEST['password'], 'role' => '37', 'account_status' => 1])) {
      // if successful, then redirect to their intended location
      // return redirect()->back();
      // dd(Auth()->guard('admin')->user());
      if (isset(Auth()->guard('admin')->user()->admin_user_id)) {
        // dd(Auth()->guard('admin')->user());
        if (Auth()->guard('admin')->user()->account_status == 0) {
          Auth::guard('admin')->logout();
          //   return back()->withErrors([
          //     'message' => 'Your account has been deactivated, Please contact 3P team to reactivate your account.'
          //   ]);
          return json_encode(['error' => 'Your account has been deactivated, Please contact 3P team to reactivate your account']);
        }
      }



      $user_ip_address = $_SERVER['REMOTE_ADDR'];
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
      $user_os = $this->actionGetOS();
      $browser = $this->getBrowser();
      if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $user_agent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($user_agent, 0, 4))) {
        $device = 'mobile';
      } else {
        $device = 'desktop';
      }
      // dd(Auth()->guard('admin')->user()->admin_user_id);
      $user_id = Auth()->guard('admin')->user()->admin_user_id;
      $email = Auth()->guard('admin')->user()->email;
      $company_id = Auth()->guard('admin')->user()->company_id;




      $current_user = new TrafficSource();
      $current_user->REMOTE_ADDR = $user_ip_address;
      $current_user->HTTP_USER_AGENT = $user_agent;
      $current_user->user_os = $user_os;
      $current_user->device = $device;
      $current_user->browser = $browser;
      $current_user->email = $email;
      $current_user->id = $user_id;
      $current_user->traffic_source = "email";


      $current_user->save();

      $company = Company::where('company_id', $company_id)->first();

      if (!empty($company)) {
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
      } else {
        $currentDate = Carbon::now();
        $startOfYear = Carbon::create($currentDate->year, 4, 1);
        $endOfYear = Carbon::create($currentDate->year + 1, 3, 31);

        if ($currentDate->between($startOfYear, $endOfYear)) {
          $Financialyear = $currentDate->year . '-' . ($currentDate->year + 1);
        } else {
          $Financialyear = ($currentDate->year - 1) . '-' . $currentDate->year;
        }
      }
      // dd('sdd');
      Session::put('company_id', $company_id ?? '');
      Session::put('fy_year', $Financialyear);

      $result_data['user_id'] = $user_id;
      $result_data['company_id'] = $company_id;
      $result_data['fy_year'] = $Financialyear;
      $result_data['success'] = "login success";

      //   $result_data = $result_data->toArray();

      return json_encode($result_data);
    } else {

      return json_encode(['error' => 'The email or password is incorrect, please try again']);
    }
  }

  public function updatePassword()
  {

    $data = AdminUsers::where(['email' => $_REQUEST['email'], 'role' => '37', 'account_status' => 1])->first();
    // dd($data->toArray());
    if (!empty($data)) {
      // if (FacadesHash::check($_REQUEST['current_password'], $data->password)) {
      // dd('Password matches');
      // dd($request->new_password);
      $data->password = $_REQUEST['new_password'];
      if ($data->save()) {
        // dd('success');


        // return redirect()->back()->with('success', 'Password Has Been Updated');
        return json_encode(['success' => 'Password Has Been Updated']);
      } else {
        // dd('failure');
        // return redirect()->back()->with('error', 'Unable to change the password');
        return json_encode(['error' => 'Unable to change the password']);
      }
    } else {
      return json_encode(['error' => "User not registered"]);
    }
  }


  public function get_dashboard_data()
  {

    $salesman = $_REQUEST['user_id'] ?? '';

    $outlets = count(BussinessPartnerMaster::where(['is_converted' => 0, 'business_partner_type' => 1])->get());
    $order_bookings = count(OrderBooking::where(['status' => 'open', 'split_of' => 0, 'created_by' => $salesman])->get());

    $focus_packs = Focusmap::where('month', date('F'))->limit(10)->pluck('product_id');
    $focus_pack = Products::whereIn('product_item_id', $focus_packs)->pluck('consumer_desc');

    $salesman_incentive = [];
    if (!empty($salesman)) {
      $salesman_incentive = DB::table('salesman_incentives')
        ->where(['month' => date('F'), 'salesman' => $salesman])
        ->pluck('amount')
        ->first();
    }
    // return response()->json($details);
    $items = OrderBookingItems::pluck('item_code');
    $formats = [];
    $maxItems = 5;
    $addedFormats = [];
    foreach ($items as $row) {
      $product = Products::where('item_code', $row)->first();
      if ($product && !in_array($product->sub_category->subcategory_name, $addedFormats)) {
        $formats[] = $product->sub_category->subcategory_name;
        $addedFormats[] = $product->sub_category->subcategory_name; // Add the format to the added formats array
        if (count($formats) >= $maxItems) {
          break;
        }
      }
    }

    $outlets_data = Outlet::get();
    $result_data['outlets'] = $outlets;
    $result_data['order_bookings'] = $order_bookings;
    $result_data['outlets_data'] = $outlets_data;
    $result_data['formats'] = $formats;
    $result_data['focus_pack'] = $focus_pack;
    $result_data['salesman_incentive'] = $salesman_incentive;

    return json_encode($result_data);
  }

  public function get_user_data()
  {

    $user = AdminUsers::where('admin_user_id', $_REQUEST['user_id'])->first();

    $result_data['user'] = $user;
    return json_encode($result_data);
  }

  public function get_companies()
  {
    $comapny_data = Company::get();

    // return response()->json($comapny_data);
    $result_data['companies'] = $comapny_data;
    return json_encode($result_data);
  }


  public function get_daily_beats()
  {
    $currentWeek = date('W');
    $firstDayOfMonthWeek = date('W', strtotime('first day of this month'));
    // Calculate the week number within the month
    $weekNumberWithinMonth = $currentWeek - $firstDayOfMonthWeek + 1;
    // If the result is less than 1, set it to 1
    $weekNumberWithinMonth = max($weekNumberWithinMonth, 1);

    $get_todays_beats = BeatCalender::where([
      'beat_week' => sprintf('Week %d', $weekNumberWithinMonth),
      'beat_day' => date('l'),
      'beat_month' => date('F'),
      'beat_year' => date('Y'),
    ])->pluck('beat_id')->toArray();


    $beats = BussinessPartnerMaster::with('get_beat')
      ->whereIn('beat_id', $get_todays_beats)
      ->where(['salesman' => $_REQUEST['user_id'], 'bp_category' => 2])
      ->get()
      ->groupBy('get_beat.beat_id') // Group by the beat_id from the relationship
      ->map(function ($group) {
        return $group->first();
      })
      ->values()
      ->toArray();

    $date = now()->format('Y-m-d');
    $days_plan = [];
    foreach ($beats as $row) {
      $days_plan = DB::table('days_plan')
        ->where('user_id', $row['salesman'])
        ->where('date', $date)
        ->get();
    }
    // dd($days_plan);


    $result_data['beats'] = $beats;
    $result_data['days_plan'] = $days_plan;
    return json_encode($result_data);
  }

  public function outlets()
  {
    $outlets = BussinessPartnerMaster::with('get_beat')->where(['salesman' => $_REQUEST['user_id'], 'is_converted' => 0])->get()->toArray();

    $result_data['outlets'] = $outlets;
    return json_encode($result_data);
  }

  public function update_outlet(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    try {
      if (isset($postedData['business_partner_id'])) {
        $bussiness = BussinessPartnerMaster::where('business_partner_id', $postedData['business_partner_id'])->first();
      } else {
        $bussiness = new BussinessPartnerMaster();
      }

      $bussiness->fill($postedData);
      $bussiness->business_partner_type = 1;
      $bussiness->bp_category = 2;
      $bussiness->pricing_profile = 1;
      $bussiness->bp_name = $postedData['outlet_name'];
      $bussiness->is_converted = 0;

      if ($bussiness->save()) {

        $bid = $bussiness->business_partner_id;
        $uid = ['bussiness_partner_id' => $bid];
        $full_data = array_merge($uid, $postedData);

        //usama_07-02-2024
        $billToAddress = BussinessPartnerAddress::firstOrNew(['bussiness_partner_id' => $uid, 'address_type' => 'Bill-To/ Bill-From']);
        $billToAddress->fill($full_data);
        $billToAddress->bp_address_name = $full_data['street_name']; // Adjust field name as needed
        $billToAddress->save();

        // Create or update Ship-To/ Ship-From address
        $shipToAddress = BussinessPartnerAddress::firstOrNew(['bussiness_partner_id' => $uid, 'address_type' => 'Ship-To/ Ship-From']);
        $shipToAddress->fill($full_data);
        $shipToAddress->bp_address_name = $full_data['street_name']; // Adjust field name as needed
        $shipToAddress->save();


        return json_encode(['success' => 'Outlet Updated']);
      }
    } catch (Exception $e) {
      return json_encode(['error' => $e->getMessage()]);
    }
  }

  public function view_outlet()
  {
    $outlet = BussinessPartnerMaster::where('business_partner_id', $_REQUEST['outlet_id'])->get()->toArray();
    $outlet_address = BussinessPartnerAddress::where('bussiness_partner_id', $_REQUEST['outlet_id'])->get()->toArray();

    $result_data['outlet'] = $outlet;
    $result_data['outlet_address'] = $outlet_address;
    return json_encode($result_data);
  }

  public function delete_outlet()
  {
    $id = $_REQUEST['outlet_id'];
    $outelt = BussinessPartnerMaster::where('business_partner_id', $id);

    if ($outelt->delete()) {
      BussinessPartnerAddress::where('bussiness_partner_id', $id);

      return json_encode(['success' => 'Outlet Deleted']);
    }
  }


  public function get_area_route_beat()
  {
    $area = Area::get()->toArray();

    $routes = Routes::where('area_id', $_REQUEST['area_id'] ?? '')->get()->toArray();
    $beats = Beat::where(['area_id' => $_REQUEST['area_id'] ?? '', 'route_id' => $_REQUEST['route_id'] ?? ''])->get()->toArray();
    $result_data['area'] = $area;
    $result_data['routes'] = $routes;
    $result_data['beats'] = $beats;
    return json_encode($result_data);
  }

  public function get_country_state_district()
  {
    $country = Country::get()->toArray();

    $states = State::where('country_id', $_REQUEST['country_id'] ?? '')->get()->toArray();
    $districts = City::where(['state_id' => $_REQUEST['state_id'] ?? ''])->get()->toArray();
    $result_data['countries'] = $country;
    $result_data['states'] = $states;
    $result_data['districts'] = $districts;
    return json_encode($result_data);
  }

  public function get_outlets()
  {

    $outlets = BussinessPartnerMaster::where(['business_partner_type' => '1', 'beat_id' => $_REQUEST['beat_id'], 'salesman' => $_REQUEST['user_id'], 'bp_category' => 2, 'is_converted' => 1])->get()->toArray();
    // dd($outlets);

    $date = now()->format('Y-m-d');
    $selected_outlet = [];
    foreach ($outlets as $row) {
      $selected_outlet = DB::table('outlet_selection')
        ->where('user_id', $row['salesman'])
        ->where('date', $date)
        ->get();
    }

    $result_data['outlets'] = $outlets;
    $result_data['selected_outlet'] = $selected_outlet;
    return json_encode($result_data);
  }



  public function update_days_plan(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    // Extract data from the $postedData array
    $user_id = $postedData['user_id'];
    $beat_id = $postedData['beat_id'];
    $date = now()->format('Y-m-d');
    $is_start = $postedData['is_start'] ?? 0;
    $is_skip = $postedData['is_skip'] ?? 0;
    $skip_reason = $postedData['skip_reason'] ?? '';

    $updatedOrInserted = DB::table('days_plan')->updateOrInsert(
      ['user_id' => $user_id, 'beat_id' => $beat_id, 'date' => $date],
      ['is_start' => $is_start, 'is_skip' => $is_skip, 'skip_reason' => $skip_reason]
    );


    if ($updatedOrInserted) {
      return response()->json(['success' => 'Updated']);
    } else {
      return response()->json(['error' => 'Error']);
    }
  }

  public function update_outlet_Selection(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    // Extract data from the $postedData array
    $user_id = $postedData['user_id'];
    $beat_id = $postedData['beat_id'];
    $outlet_id = $postedData['outlet_id'];
    $date = now()->format('Y-m-d');
    $is_start = $postedData['is_start'] ?? 0;
    $is_skip = $postedData['is_skip'] ?? 0;
    $skip_reason = $postedData['skip_reason'] ?? '';
    $is_submit = $postedData['is_submit'] ?? 0;

    $updatedOrInserted = DB::table('outlet_selection')->updateOrInsert(
      ['user_id' => $user_id, 'beat_id' => $beat_id, 'outlet_id' => $outlet_id, 'date' => $date],
      ['is_start' => $is_start, 'is_skip' => $is_skip, 'is_submit' => $is_submit, 'skip_reason' => $skip_reason]
    );


    if ($updatedOrInserted) {
      return response()->json(['success' => 'Updated']);
    } else {
      return response()->json(['error' => 'Error']);
    }
  }


  public function get_orders()
  {
    $orders = OrderBooking::where(['status' => 'open', 'split_of' => 0, 'party_id' => $_REQUEST['outlet_id'], 'created_by' => $_REQUEST['user_id']])->orderby('created_at', 'desc')->get()->toArray();

    $result_data['orders'] = $orders;
    return json_encode($result_data);
  }


  public function view_order_temp()
  {
    $order = OrderBookingTemp::with('purchaseorder_items')->where(['order_booking_id' => $_REQUEST['order_id'], 'party_id' => $_REQUEST['outlet_id']])->get()->toArray();

    $order_items = OrderBookingItemsTemp::where('order_booking_id', $_REQUEST['order_id'])->get()->toArray();

    $result_data['order'] = $order;
    $result_data['order_items'] = $order_items;

    // Calculate the total sum of 'total' and 'gst_amount'
    $totalSum = 0;
    $gstAmountSum = 0;

    foreach ($order_items as $item) {
      $totalSum += floatval($item['total']);
      $gstAmountSum += floatval($item['gst_amount']);
    }

    $result_data['total_sum'] = $totalSum;
    $result_data['gst_amount_sum'] = $gstAmountSum;

    return json_encode($result_data);
  }

  public function view_order()
  {
    $order = OrderBooking::with('purchaseorder_items')->where(['order_booking_id' => $_REQUEST['order_id']])->get()->toArray();

    $order_items = OrderBookingItems::where('order_booking_id', $_REQUEST['order_id'])->get()->toArray();

    $result_data['order'] = $order;
    $result_data['order_items'] = $order_items;

    // Calculate the total sum of 'total' and 'gst_amount'
    $totalSum = 0;
    $gstAmountSum = 0;

    foreach ($order_items as $item) {
      $totalSum += floatval($item['total']);
      $gstAmountSum += floatval($item['gst_amount']);
    }

    $result_data['total_sum'] = $totalSum;
    $result_data['gst_amount_sum'] = $gstAmountSum;
    return json_encode($result_data);
  }

  public function view_order_item()
  {
    $order_items = OrderBookingItems::where('order_booking_item_id', $_REQUEST['order_item_id'])->get()->toArray();

    $result_data['order_items'] = $order_items;
    return json_encode($result_data);
  }

  public function view_order_item_temp()
  {
    $order_items = OrderBookingItemsTemp::where('order_booking_item_id', $_REQUEST['order_item_id'])->get()->toArray();

    $result_data['order_items'] = $order_items;
    return json_encode($result_data);
  }



  public function update_order_temp()
  {

    $outlet_id = $_REQUEST['outlet_id'];
    $user_id = $_REQUEST['user_id'];
    $company_id = $_REQUEST['company_id'];
    $fy_year = $_REQUEST['fy_year'];

    $series = SeriesMaster::where('module', 9)->first();
    $series_no = $series->series_number;


    $order = OrderBookingTemp::where(['created_by' => $user_id])->first();
    if ($order === null) {
      $order = new OrderBookingTemp();

      // set counter
      $financial_year = Financialyear::where(['year' => $fy_year])->first();
      $order_booking_counter = 0;
      if ($financial_year) {
        $order_booking_counter = $financial_year->order_booking_counter + 1;
      }
      $financial_year->order_booking_counter = $order_booking_counter;
      $financial_year->save();
      $bill_no = $series_no . '-' . $financial_year->year . "-" . $order_booking_counter;

      $order->bill_no = $bill_no;
    }

    $order->party_id = $outlet_id;
    $order->created_by = $user_id;
    $order->company_id = $company_id;
    $order->fy_year = $fy_year;
    $order->bill_date = now()->format('Y-m-d');
    $order->from_app = 1;
    // $order->save();
    if ($order->save()) {

      $result_data['order_id'] = $order->order_booking_id;
      $result_data['order_no'] = $order->bill_no;
      $result_data['date'] = $order->created_at;
      return json_encode($result_data);
    } else {
      return json_encode(['error' => 'This user belongs to some other company']);
    }
  }

  public function update_order()
  {

    $outlet_id = $_REQUEST['outlet_id'];
    $user_id = $_REQUEST['user_id'];
    $company_id = $_REQUEST['company_id'];
    $fy_year = $_REQUEST['fy_year'];

    $series = SeriesMaster::where('module', 9)->first();
    $series_no = $series->series_number;

    // set counter
    $financial_year = Financialyear::where(['year' => $fy_year])->first();
    $order_booking_counter = 0;
    if ($financial_year) {
      $order_booking_counter = $financial_year->order_booking_counter + 1;
    }
    $bill_no = $series_no . '-' . $financial_year->year . "-" . $order_booking_counter;

    $order = new OrderBooking();
    $order->party_id = $outlet_id;
    $order->created_by = $user_id;
    $order->company_id = $company_id;
    $order->fy_year = $fy_year;
    $order->bill_no = $bill_no;
    $order->bill_date = now()->format('Y-m-d');
    $order->from_app = 1;
    // $order->save();
    if ($order->save()) {
      OrderBooking::where('order_booking_id', $order->order_booking_id)->update(['order_booking_counter' => $order_booking_counter, 'bill_no' => $bill_no]);
      $financial_year->order_booking_counter = $order_booking_counter;
      $financial_year->save();


      $result_data['order_no'] = $order->bill_no;
      $result_data['date'] = $order->created_at;
      return json_encode($result_data);
    } else {
      return json_encode(['error' => 'This user belongs to some other company']);
    }
  }


  public function get_gst()
  {
    $gsts = Gst::get();
    $result_data['gst'] = $gsts->toArray();
    return json_encode($result_data);
  }

  public function get_margin_scheme()
  {
    $margin = $_REQUEST['margin'];
    $scheme = $_REQUEST['scheme'];

    $margin = Margin::where('pricing_master_id', $margin)->get();
    $scheme = Scheme::where('pricing_master_id', $scheme)->get();

    $result_data['margin'] = $margin;
    $result_data['scheme'] = $scheme;
    return json_encode($result_data);
  }

  public function get_item_auto()
  {
    $query = $_REQUEST['query'];
    $customer_id = $_REQUEST['customer_id'];

    // fetch margin data
    $bp_master = BussinessPartnerMaster::where('business_partner_id', $customer_id)->first();
    $margin = Pricings::where([
      'status' => 1,
      'pricing_type' => 'margin', 'bp_category' => $bp_master->bp_category,
      'bp_channel' => $bp_master->bp_channel, 'bp_group' => $bp_master->bp_group
    ])->first();

    $scheme = Pricings::where([
      'status' => 1,
      'pricing_type' => 'scheme', 'bp_category' => $bp_master->bp_category,
      'bp_channel' => $bp_master->bp_channel, 'bp_group' => $bp_master->bp_group
    ])->first();

    if (is_numeric($query)) {
      $data = Products::select(DB::raw("item_code as name"), 'product_item_id', 'hsncode_id', 'variant', 'buom_pack_size', 'sku', 'mrp', 'gst_id', 'brand_id', 'sub_category_id', 'consumer_desc')
        ->where('visibility', 1)
        ->where("item_code", "LIKE", "%" . $query . "%")
        ->get();
    } else if (preg_match('/^\d+-\d+$/', $query)) {
      // Check if the query matches the pattern "digits-hyphen-digits"
      $data = Products::select(DB::raw("sku as name"), 'product_item_id', 'hsncode_id', 'variant', 'buom_pack_size', 'sku', 'mrp', 'gst_id', 'brand_id', 'sub_category_id', 'item_code')
        ->where('visibility', 1)
        ->where("sku", "LIKE", "%" . $query . "%")
        ->get();
    } else {
      $data = Products::select(DB::raw("consumer_desc as name"), 'product_item_id', 'hsncode_id', 'variant', 'buom_pack_size', 'sku', 'mrp', 'brand_id', 'sub_category_id', 'gst_id', 'item_code')
        ->where('visibility', 1)
        ->where("consumer_desc", "LIKE", "%" . $query . "%")->get();
    }
    $item_code = $data[0]->item_code;
    $pricing_price = 0;

    // as per new pricing
    $pricing_ladder = Pricings::where('bp_channel', $bp_master->bp_channel)
      ->where('pricing_type', 'ladder')
      ->where('margin', $margin->pricing_master_id)
      ->where('scheme', $scheme->pricing_master_id)
      ->value('pricing_master_id');

    if (!empty($pricing_ladder)) {
      $pricing_data = Pricingladder::where('pricing_master_id', $pricing_ladder)
        ->where('item_code', $item_code)
        ->latest()
        ->first();
    }

    if (!empty($pricing_data)) {
      $pricing_price =  $pricing_data->ptr_af_sch;
    }

    // if (!empty($item_code)) {
    //   $bp_data = BussinessPartnerMaster::where(['business_partner_id' => $customer_id])->first();
    //   if (!empty($bp_data)) {
    //     $pricing_data = PricingItem::where([
    //       'pricing_master_id' => $bp_data->pricing_profile,
    //       'item_code' => $item_code
    //     ])->first();
    //     if (!empty($pricing_data)) {
    //       $pricing_price =  $pricing_data->selling_price;
    //     }
    //   }
    // }

    // return response()->json(['data' => $data]);
    $result_data['item'] = $data;
    $result_data['pricing_price'] = $pricing_price;
    $result_data['margin'] = $margin;
    $result_data['scheme'] = $scheme;
    return json_encode($result_data);
  }

  public function update_so_items(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    try {
      //   dd($postedData);
      // Extract data from the $postedData array
      $outlet_id = $postedData['outlet_id'];
      $company_id = $postedData['company_id'];
      $gst_rate = $postedData['gst_rate'];
      $total = $postedData['total'];

      $outlet = BussinessPartnerMaster::where('business_partner_id', $outlet_id)->first();
      $outlet_addresss = BussinessPartnerAddress::where(['address_type' => 'Bill-To/ Bill-From', 'bussiness_partner_id' => $outlet_id])->first();
      $company = Company::where('company_id', $company_id)->first();

      $gst_percentage = 0;
      if ($gst_rate == 1) {
        $gst_percentage = 18;
        $tax_amount = ($total * $gst_percentage) / 100;
      } else if ($gst_rate == 2) {
        $gst_percentage = 28;
        $tax_amount = ($total * $gst_percentage) / 100;
      } else if ($gst_rate == 3) {
        $gst_percentage = 5;
        $tax_amount = ($total * $gst_percentage) / 100;
      }

      $cgst_rate = 0;
      $igst_rate = 0;
      $sgst_utgst_rate = 0;
      if ($outlet_addresss->state == $company->state) {
        $gst_percentage = $gst_percentage / 2;
        $cgst_rate = $gst_percentage;
        $sgst_utgst_rate = $gst_percentage;
        $calculated_cgst_amount = ($total * $gst_percentage) / 100;
        $calculated_sgst_utgst_amount = ($total * $gst_percentage) / 100;
        $calculated_igst_amount = 0;
      } else {
        $igst_rate = $gst_percentage;
        $calculated_cgst_amount = 0;
        $calculated_sgst_utgst_amount = 0;
        $calculated_igst_amount = ($total * $gst_percentage) / 100;
      }
      $gross_total = $total + $tax_amount;

      $sku = Products::where('item_code', $postedData['item_code'])->first();

      if (!empty($postedData['order_booking_item_id'])) {
        $so_items =  OrderBookingItems::where('order_booking_item_id', $postedData['order_booking_item_id'])->first();
      } else {
        $so_items = new OrderBookingItems();
      }

      $so_items->order_booking_id = $postedData['order_booking_id'];
      $so_items->item_name = $postedData['item_name'];
      $so_items->item_code = $postedData['item_code'];
      $so_items->gst_rate = $gst_rate;
      $so_items->sku = $sku->sku ?? '';
      $so_items->hsn_sac = $postedData['hsn_sac'];
      $so_items->qty = $postedData['qty'];
      $so_items->taxable_amount = $postedData['unit_price'];
      $so_items->mrp = $postedData['mrp'];
      $so_items->margin = $postedData['margin'];
      $so_items->scheme = $postedData['scheme'];
      $so_items->total = $total;
      $so_items->gst_amount = $tax_amount;
      $so_items->cgst_amount = $calculated_cgst_amount;
      $so_items->sgst_utgst_amount = $calculated_sgst_utgst_amount;
      $so_items->igst_amount = $calculated_igst_amount;
      $so_items->cgst_rate = $cgst_rate;
      $so_items->sgst_utgst_rate = $sgst_utgst_rate;
      $so_items->igst_rate = $igst_rate;
      $so_items->total = $total;
      $so_items->gross_total = $gross_total;
      // $so_items->fill($postedData);
      $so_items->save();


      return json_encode(['success' => 'Order Items Updated']);
    } catch (Exception $e) {
      return json_encode(['error' => $e]);
    }
  }

  public function update_so_items_temp(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    try {
      // Extract data from the $postedData array
      $outlet_id = $postedData['outlet_id'];
      $company_id = $postedData['company_id'];
      $gst_rate = $postedData['gst_rate'];
      $total = $postedData['total'];

      $outlet = BussinessPartnerMaster::where('business_partner_id', $outlet_id)->first();
      $outlet_addresss = BussinessPartnerAddress::where(['address_type' => 'Bill-To/ Bill-From', 'bussiness_partner_id' => $outlet_id])->first();
      $company = Company::where('company_id', $company_id)->first();

      $gst_percentage = 0;
      if ($gst_rate == 1) {
        $gst_percentage = 18;
        $tax_amount = ($total * $gst_percentage) / 100;
      } else if ($gst_rate == 2) {
        $gst_percentage = 28;
        $tax_amount = ($total * $gst_percentage) / 100;
      } else if ($gst_rate == 3) {
        $gst_percentage = 5;
        $tax_amount = ($total * $gst_percentage) / 100;
      }

      $cgst_rate = 0;
      $igst_rate = 0;
      $sgst_utgst_rate = 0;
      if ($outlet_addresss->state == $company->state) {
        $gst_percentage = $gst_percentage / 2;
        $cgst_rate = $gst_percentage;
        $sgst_utgst_rate = $gst_percentage;
        $calculated_cgst_amount = ($total * $gst_percentage) / 100;
        $calculated_sgst_utgst_amount = ($total * $gst_percentage) / 100;
        $calculated_igst_amount = 0;
      } else {
        $igst_rate = $gst_percentage;
        $calculated_cgst_amount = 0;
        $calculated_sgst_utgst_amount = 0;
        $calculated_igst_amount = ($total * $gst_percentage) / 100;
      }
      $gross_total = $total + $tax_amount;

      $sku = Products::where('item_code', $postedData['item_code'])->first();

      if (!empty($postedData['order_booking_item_id'])) {
        $so_items =  OrderBookingItemsTemp::where('order_booking_item_id', $postedData['order_booking_item_id'])->first();
      } else {
        $so_items = new OrderBookingItemsTemp();
      }

      $so_items->order_booking_id = $postedData['order_booking_id'];
      $so_items->item_name = $postedData['item_name'];
      $so_items->item_code = $postedData['item_code'];
      $so_items->gst_rate = $gst_rate;
      $so_items->sku = $sku->sku ?? '';
      $so_items->hsn_sac = $postedData['hsn_sac'];
      $so_items->qty = $postedData['qty'];
      $so_items->taxable_amount = $postedData['unit_price'];
      $so_items->mrp = $postedData['mrp'];
      $so_items->margin = $postedData['margin'];
      $so_items->scheme = $postedData['scheme'];
      $so_items->total = $total;
      $so_items->gst_amount = $tax_amount;
      $so_items->cgst_amount = $calculated_cgst_amount;
      $so_items->sgst_utgst_amount = $calculated_sgst_utgst_amount;
      $so_items->igst_amount = $calculated_igst_amount;
      $so_items->cgst_rate = $cgst_rate;
      $so_items->sgst_utgst_rate = $sgst_utgst_rate;
      $so_items->igst_rate = $igst_rate;
      $so_items->total = $total;
      $so_items->gross_total = $gross_total;
      // $so_items->fill($postedData);
      $so_items->save();


      return json_encode(['success' => 'Order Items Updated']);
    } catch (Exception $e) {
      return json_encode(['error' => $e]);
    }
  }


  public function delete_order()
  {
    $id = $_REQUEST['order_id'];
    $purchaseorder = OrderBooking::findOrFail($id);
    $purchaseorder_items = OrderBookingItems::where('order_booking_id', $id);

    if ($purchaseorder->delete()) {
      if (!empty($purchaseorder_items)) {
        $purchaseorder_items->delete();
      }
      return json_encode(['success' => 'Order Deleted']);
    }
  }

  public function delete_order_temp()
  {
    $id = $_REQUEST['order_id'];
    $purchaseorder = OrderBookingTemp::findOrFail($id);
    $purchaseorder_items = OrderBookingItemsTemp::where('order_booking_id', $id);

    if ($purchaseorder->delete()) {
      $purchaseorder_items->delete();
      return json_encode(['success' => 'Order Deleted']);
    }
  }

  public function delete_order_item()
  {
    $id = $_REQUEST['order_item_id'];
    $purchaseorder_items = OrderBookingItems::where('order_booking_item_id', $id);

    if ($purchaseorder_items->delete()) {
      return json_encode(['success' => 'Order Item Deleted']);
    }
  }

  public function delete_order_item_temp()
  {
    $id = $_REQUEST['order_item_id'];
    $purchaseorder_items = OrderBookingItemsTemp::where('order_booking_item_id', $id);

    if ($purchaseorder_items->delete()) {
      return json_encode(['success' => 'Order Item Deleted']);
    }
  }


  public function save_order()
  {
    $order_id = $_REQUEST['order_id'];
    $get_temp_orders = OrderBookingTemp::where('order_booking_id', $order_id)->first();
    $get_temp_items = OrderBookingItemsTemp::where('order_booking_id', $order_id)->get();

    if ($get_temp_items->isEmpty()) {
      return json_encode(['error' => 'Need Atleast One Item To Order']);
    }
    $order_booking = new OrderBooking;
    $order_booking->fill($get_temp_orders->toArray());
    $order_booking->customer_ref_no = 'from app';
    unset($order_booking['order_booking_id']);
    $order_booking->status = "open";
    if ($order_booking->save()) {

      // set counter
      $financial_year = Financialyear::where(['year' => $order_booking->fy_year])->first();
      $order_booking_counter = 0;
      if ($financial_year) {
        $order_booking_counter = $financial_year->order_booking_counter;
      }

      OrderBooking::where('order_booking_id', $order_id)->update(['order_booking_counter' => $order_booking_counter]);
      $financial_year->order_booking_counter = $order_booking_counter;
      $financial_year->save();


      $get_temp_orders->delete();
      foreach ($get_temp_items as $row) {
        $order_booking_items = new OrderBookingItems();
        $order_booking_items->fill($row->toArray());
        unset($order_booking_items['order_booking_item_id']);
        $order_booking_items->order_booking_id = $order_booking->order_booking_id;

        $order_booking_items->save();

        $row->delete();
      }

      return json_encode(['success' => 'Order Saved Successfully']);
    }
  }


  public function update_outstanding(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    // Extract data from the $postedData array
    $customer_id = (int) $postedData['outlet_id'];
    $amount = $postedData['amount'];
    $payment_option = $postedData['payment_option'];
    $cheque_images = [];

    if ($request->hasFile('cheque_images')) {
      $uploadDir = 'public/backend-assets/cheques/';

      foreach ($request->file('cheque_images') as $file) {
        // Generate a unique filename
        $filename = uniqid() . '_' . $file->getClientOriginalName();

        // Move the file to the upload directory
        $file->move($uploadDir, $filename);

        // Add the filename to the array
        $cheque_images[] = $filename;
      }
    } elseif ($request->hasFile('cheque_image')) {
      // Process uploaded file when only one image is present
      $uploadDir = 'public/backend-assets/cheques/';
      $filename = uniqid() . '_' . $request->file('cheque_image')->getClientOriginalName();

      // Move the file to the upload directory
      $request->file('cheque_image')->move($uploadDir, $filename);

      // Add the filename to the array
      $cheque_images[] = $filename;
    }


    $payment_date = now()->format('Y-m-d');


    $inserted = DB::table('outstanding_pay')->insert(
      [
        'customer_id' => $customer_id,
        'amount' => $amount,
        'payment_option' => $payment_option,
        'cheque_image' => implode(",", $cheque_images),
        'payment_date' => $payment_date
      ]
    );


    if ($inserted) {
      return response()->json(['success' => 'Payment Updated']);
    } else {
      return response()->json(['error' => 'Error']);
    }
  }


  public function update_visibility(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    // Extract data from the $postedData array
    $customer_id = (int) $postedData['outlet_id'];
    $payment_option = $postedData['rental_type'];
    $cheque_images = [];

    if ($request->hasFile('visibility_images')) {
      $uploadDir = 'public/backend-assets/visibility/';

      foreach ($request->file('visibility_images') as $file) {
        // Generate a unique filename
        $filename = uniqid() . '_' . $file->getClientOriginalName();

        // Move the file to the upload directory
        $file->move($uploadDir, $filename);

        // Add the filename to the array
        $cheque_images[] = $filename;
      }
    } elseif ($request->hasFile('visibility_image')) {
      // Process uploaded file when only one image is present
      $uploadDir = 'public/backend-assets/visibility/';
      $filename = uniqid() . '_' . $request->file('visibility_image')->getClientOriginalName();

      // Move the file to the upload directory
      $request->file('visibility_image')->move($uploadDir, $filename);

      // Add the filename to the array
      $cheque_images[] = $filename;
    }


    $payment_date = now()->format('Y-m-d');


    $inserted = DB::table('visibility_app')->insert(
      [
        'customer_id' => $customer_id,
        'rental_type' => $payment_option,
        'visibility_image' => implode(",", $cheque_images),
        'date' => $payment_date
      ]
    );


    if ($inserted) {
      return response()->json(['success' => 'Payment Updated']);
    } else {
      return response()->json(['error' => 'Error']);
    }
  }

  public function update_outlet_image(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    // Extract data from the $postedData array
    $customer_id = (int) $postedData['outlet_id'];
    $beat_id = (int) $postedData['beat_id'];
    $latitude = $postedData['latitude'];
    $longitude = $postedData['longitude'];
    $outlet_image = [];

    if ($request->hasFile('outlet_images')) {
      $uploadDir = 'public/backend-assets/visibility/';

      foreach ($request->file('outlet_images') as $file) {
        // Generate a unique filename
        $filename = uniqid() . '_' . $file->getClientOriginalName();
        $file->move($uploadDir, $filename);
        $outlet_image[] = $filename;
      }
    } elseif ($request->hasFile('outlet_image')) {
      // Process uploaded file when only one image is present
      $uploadDir = 'public/backend-assets/outlet/';
      $filename = uniqid() . '_' . $request->file('outlet_image')->getClientOriginalName();

      // Move the file to the upload directory
      $request->file('outlet_image')->move($uploadDir, $filename);

      // Add the filename to the array
      $outlet_image[] = $filename;
    }

    $inserted = DB::table('business_partner_master')
      ->where('business_partner_id', $customer_id)
      ->where('beat_id', $beat_id)
      ->update([
        'salesman_lat' => $latitude,
        'salesman_long' => $longitude,
        'outlet_image' => implode(",", $outlet_image),
      ]);


    if ($inserted) {
      return response()->json(['success' => 'Image Updated']);
    } else {
      return response()->json(['error' => 'Error']);
    }
  }

  public function save_comments(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    // Extract data from the $postedData array
    $customer_id = (int) $postedData['outlet_id'];
    $comments = $postedData['comments'];

    $date_time = now()->format('Y-m-d H:i:s');


    $inserted = DB::table('comments_orders')->insert(
      [
        'customer_id' => $customer_id,
        'comments' => $comments,
        'date' => $date_time
      ]
    );


    if ($inserted) {
      return response()->json(['success' => 'Comment Updated']);
    } else {
      return response()->json(['error' => 'Error']);
    }
  }
  public function get_previous_comments()
  {
    $customer_id = $_REQUEST['outlet_id'];

    $data = DB::table('comments_orders')->where('customer_id', $customer_id)->orderby('created_at', 'desc')->get()->toArray();

    $result_data['previous_comments'] = $data;
    return json_encode($result_data);
  }

  public function get_previous_soh()
  {
    $customer_id = $_REQUEST['outlet_id'];

    $data = DB::table('soh_app')->where('customer_id', $customer_id)->orderby('updated_at', 'desc')->get()->toArray();

    $result_data['previous_soh'] = $data;
    return json_encode($result_data);
  }


  public function save_soh(Request $request)
  {

    $postedData = json_decode($request->input('posted_data'), true);

    // Extract data from the $postedData array
    $customer_id = (int) $postedData['outlet_id'];
    $sku = $postedData['sku'];
    $quantity = $postedData['quantity'];
    $item_desc = $postedData['item_desc'];

    $date = now()->format('Y-m-d');

    $inserted = DB::table('soh_app')->updateOrInsert(
      [
        'customer_id' => $customer_id,
        'sku' => $sku,
      ],
      [
        'quantity' => $quantity,
        'item_desc' => $item_desc,
        'date' => $date,
      ]
    );



    if ($inserted) {
      return response()->json(['success' => 'Comment Updated']);
    } else {
      return response()->json(['error' => 'Error']);
    }
  }





  // by usama
  public function actionGetOS()
  {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform  = "Unknown OS Platform";
    $os_array     = array(
      '/windows nt 10/i'      =>  'Windows 10',
      '/windows nt 6.3/i'     =>  'Windows 8.1',
      '/windows nt 6.2/i'     =>  'Windows 8',
      '/windows nt 6.1/i'     =>  'Windows 7',
      '/windows nt 6.0/i'     =>  'Windows Vista',
      '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
      '/windows nt 5.1/i'     =>  'Windows XP',
      '/windows xp/i'         =>  'Windows XP',
      '/windows nt 5.0/i'     =>  'Windows 2000',
      '/windows me/i'         =>  'Windows ME',
      '/win98/i'              =>  'Windows 98',
      '/win95/i'              =>  'Windows 95',
      '/win16/i'              =>  'Windows 3.11',
      '/macintosh|mac os x/i' =>  'Mac OS X',
      '/mac_powerpc/i'        =>  'Mac OS 9',
      '/linux/i'              =>  'Linux',
      '/ubuntu/i'             =>  'Ubuntu',
      '/iphone/i'             =>  'iPhone',
      '/ipod/i'               =>  'iPod',
      '/ipad/i'               =>  'iPad',
      '/android/i'            =>  'Android',
      '/blackberry/i'         =>  'BlackBerry',
      '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value)
      if (preg_match($regex, $user_agent))
        $os_platform = $value;

    return $os_platform;
  }

  function getBrowser()
  {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // dd($user_agent);

    $browser        = "Unknow Browser";
    $browser_array = array(
      '/msie/i'       =>  'Internet Explorer',
      '/firefox/i'    =>  'Firefox',
      '/opera|OPR/i'  =>  'Opera',
      '/edg/i'        =>  'Microsoft Edge',
      '/safari/i'     =>  'Safari',
      '/chrome/i'     =>  'Chrome',
      '/edge/i'       =>  'Edge',
      '/netscape/i'   =>  'Netscape',
      '/maxthon/i'    =>  'Maxthon',
      '/konqueror/i'  =>  'Konqueror',
      '/mobile/i'     =>  'Mobile Browser'
    );

    foreach ($browser_array as $regex => $value) {
      if (preg_match($regex, $user_agent)) {
        $browser = $value;
        break;
      }
    }
    return $browser;
  }

  public function get_products(Request $request)
  {
    $customer_id = $request->input('customer_id');

    // Fetch margin and scheme data
    $bp_master = BussinessPartnerMaster::where('business_partner_id', $customer_id)->first();
    $margin = Pricings::where([
      'status' => 1,
      'pricing_type' => 'margin',
      'bp_category' => $bp_master->bp_category,
      'bp_channel' => $bp_master->bp_channel,
      'bp_group' => $bp_master->bp_group
    ])->first();

    $scheme = Pricings::where([
      'status' => 1,
      'pricing_type' => 'scheme',
      'bp_category' => $bp_master->bp_category,
      'bp_channel' => $bp_master->bp_channel,
      'bp_group' => $bp_master->bp_group
    ])->first();

    // Fetch product data
    $data = Products::select(DB::raw("consumer_desc as name"), 'product_item_id', 'hsncode_id', 'sku', 'mrp', 'brand_id', 'sub_category_id', 'gst_id', 'item_code')->get();

    // Fetch pricing ladder
    $pricing_ladder = Pricings::where([
      'bp_channel' => $bp_master->bp_channel,
      'pricing_type' => 'ladder',
      'margin' => $margin->pricing_master_id,
      'scheme' => $scheme->pricing_master_id
    ])->value('pricing_master_id');

    if (!empty($pricing_ladder)) {
      // Fetch margin and scheme data for matching products
      $margin_data = Margin::whereIn('brand_id', $data->pluck('brand_id'))
        ->whereIn('sub_category_id', $data->pluck('sub_category_id'))
        ->where('pricing_master_id', $margin->pricing_master_id)
        ->get();

      $scheme_data = Scheme::whereIn('brand_id', $data->pluck('brand_id'))
        ->whereIn('sub_category_id', $data->pluck('sub_category_id'))
        ->where('pricing_master_id', $scheme->pricing_master_id)
        ->get();

      // Merge pricing data with product data
      foreach ($data as $row) {
        $matched_margin = $margin_data->first(function ($item) use ($row) {
          return $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id;
        });

        if ($matched_margin) {
          $row->margin = $matched_margin->margin;
        }

        $matched_scheme = $scheme_data->first(function ($item) use ($row) {
          return $item->brand_id == $row->brand_id && $item->sub_category_id == $row->sub_category_id;
        });

        if ($matched_scheme) {
          $row->scheme = $matched_scheme->scheme;
        }

        // Fetch unit price from pricing ladder
        $pricing_data = Pricingladder::where('pricing_master_id', $pricing_ladder)
          ->where('item_code', $row->item_code)
          ->latest()
          ->first();

        if ($pricing_data) {
          $row->unit_price = $pricing_data->ptr_af_sch;
        }
      }
    }

    // Prepare response data
    $result_data['products'] = $data;
    $result_data['margin'] = $margin;
    $result_data['scheme'] = $scheme;

    // return response()->json($result_data);
    return json_encode($result_data);
  }
} //end of class
