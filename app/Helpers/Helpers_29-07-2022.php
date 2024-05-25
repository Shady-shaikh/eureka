<?php
use App\Models\frontend\Cart;
use App\Models\frontend\Orders;
// use App\Models\frontend\Orders;
use App\Models\frontend\CartCoupons;
use App\Models\backend\OrderReturnManagement;
use App\Models\backend\OrderCancelManagement;
use App\Models\backend\StateCodes;

if (!function_exists('get_shipping_charges'))
{
  // $md(string) : modeBilling mode of shipment(E: Express/ S: Surface)(mandatory)
  // $cgm(int32) : Weight of shipment in grams(Mandatory)
  // $o_pin(int32) : Origin center's Pin Code (optional)
  // $d_pin(int32) : Pin code of destination city (optional)
  // $ss(string) : Status of shipment Delivered,RTO,DTO(Mandatory)

  function get_shipping_charges($md,$cgm,$o_pin,$ss,$d_pin)
  {
    // dd($cgm);
    $accesstoken = 'c7bd1bd7f2ed46a36775d63e8c9ee43617ffee49';
    $url = "https://track.delhivery.com/api/kinko/v1/invoice/charges/.json?md=".$md."&cgm=".$cgm."&=".$o_pin."&ss=".$ss."&d_pin=".$d_pin;
    $curl = curl_init();

    $headr = array();
    $headr[] = 'Access: 0';
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: Token '.$accesstoken;

    curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;

  }
}

//cart total
//discounted price total
if (!function_exists('get_cart_total'))
{
    function get_cart_total()
    {
      $cart_total = new stdClass();
      $total = 0;
      $mrp_total = 0;
      if(isset(auth()->user()->id))
      {
        $user_id = auth()->user()->id;
        if($user_id)
        {
          // $cart_total = Cart::where('user_id',$user_id)->with(['products'])->select(DB::raw('sum(products.product_discounted_price*cart.qty) as cart_total'))->first();
          // $cart_total = Cart::where('user_id',$user_id)->join('products', 'products.product_id', '=', 'cart.product_id')->select(DB::raw('sum(products.product_discounted_price*cart.qty) as cart_total'))->first();
          $cart = Cart::where('user_id',$user_id)->with(['products','product_variant'])->get();
          foreach ($cart as $item)
          {
            $join_table = 'products';
            if($item->product_type=="configurable")
            {
              $join_table = 'product_variant';
            }
            $total += $item->{$join_table}->product_discounted_price*$item->qty;
          }
        }
      }
      $cart_total->cart_total = $total;
      return $cart_total;
    }
}

if (!function_exists('get_cart_mrp_total'))
{
    function get_cart_mrp_total()
    {
      $cart_total = new stdClass();
      // $cart_total;
      $total = 0;
      if(isset(auth()->user()->id))
      {
        $user_id = auth()->user()->id;
        if($user_id)
        {
          // $cart_total = Cart::where('user_id',$user_id)->with(['products'])->select(DB::raw('sum(products.product_discounted_price*cart.qty) as cart_total'))->first();
          // $cart_total = Cart::where('user_id',$user_id)->join('products', 'products.product_id', '=', 'cart.product_id')->select(DB::raw('sum(products.product_price*cart.qty) as cart_total'))->first();
          $cart = Cart::where('user_id',$user_id)->with(['products','product_variant'])->get();
          foreach ($cart as $item)
          {
            $join_table = 'products';
            if($item->product_type=="configurable")
            {
              $join_table = 'product_variant';
            }
            if($item->{$join_table}->product_discounted_price!=null && $item->{$join_table}->product_discount!=0)
            {
              $total += $item->{$join_table}->product_price*$item->qty;
            }
            elseif($item->{$join_table}->product_discounted_price!=null && $item->{$join_table}->product_discount==0)
            {
              $total += $item->{$join_table}->product_discounted_price*$item->qty;
            }
            else
            {
              $total += $item->{$join_table}->product_price*$item->qty;
            }
          }
        }
      }
      $cart_total->cart_total = $total;
      return $cart_total;
    }
}

if (!function_exists('get_coupon_usage_count'))
{
    function get_coupon_usage_count($coupon_code)
    {
      $coupon_count = 0;
      if(isset(auth()->user()->id))
      {
        $user_id = auth()->user()->id;
        if($user_id)
        {
          $coupon_count = Orders::where('user_id',$user_id)->where('coupon_code',$coupon_code)->count();
        }
      }

      return $coupon_count;
    }
}

if (!function_exists('verify_pincode'))
{
    function verify_pincode($pincode)
    {
      $accesstoken = 'ec1ee821dc6ca3355a018d48828d3e2ccb892de7';//c7bd1bd7f2ed46a36775d63e8c9ee43617ffee49
      $url = "https://staging-express.delhivery.com/c/api/pin-codes/json/?token=".$accesstoken."&filter_codes=".$pincode;
      $curl = curl_init();

      $headr = array();
      $headr[] = 'Access: 0';
      $headr[] = 'Content-type: application/json';
      $headr[] = 'Authorization: Token '.$accesstoken;

      curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($curl);
      curl_close($curl);
      // dd($data);
      return $data;
    }
}

if (!function_exists('order_creation'))
{
  // $md(string) : modeBilling mode of shipment(E: Express/ S: Surface)(mandatory)
  // $cgm(int32) : Weight of shipment in grams(Mandatory)
  // $o_pin(int32) : Origin center's Pin Code (optional)
  // $d_pin(int32) : Pin code of destination city (optional)
  // $ss(string) : Status of shipment Delivered,RTO,DTO(Mandatory)

  function order_creation($current_order)
  {
    $accesstoken = 'ec1ee821dc6ca3355a018d48828d3e2ccb892de7';
    $url = "https://staging-express.delhivery.com/api/cmu/create.json";

    $add = $current_order->shipping_address_line1.', '.$current_order->shipping_address_line2.', '.$current_order->shipping_landmark.', '.$current_order->shipping_city.', '.$current_order->shipping_district.', '.$current_order->shipping_state;
    $phone = $current_order->shipping_mobile_no;
    $total_amount = $current_order->total;
    $cod_amount = '';

    if($current_order->payment_mode=='payumoney')
    {
      $payment_mode = "Prepaid";
    }
    else if($current_order->payment_mode=='cod')
    {
      $payment_mode = "COD";
    }
    else
    {
      $payment_mode = "COD";
    }
    $name = $current_order->shipping_full_name;
    $pin = $current_order->shipping_pincode;
    $order = $current_order->orders_counter_id;
    $order_id = $current_order->order_id;
    $city = $current_order->shipping_city;
    $order_total = $current_order->total;
    $product_weight = 0;
    $shipments = '';
    $order_qty = 0;
    $items = '';
    if (isset($current_order->orderproducts) && $current_order->orderproducts->count()>0)
    {
      $items .= '"item": [';
      foreach ($current_order->orderproducts as $item)
      {
        $item_qty = $item->qty;
        $item_total_amount = $item->qty*$item->product_discounted_price;
        $products_desc = $item->product_sub_title.' ('.$item->product_title.')';
        $item_id = $item->orders_product_details_id;
        if($current_order->payment_mode=='cod')
        {
          $cod_amount = ',"cod_amount": "'.$item_total_amount.'"';
        }
        if ($shipments!='')
        {
          $shipments .=',';
        }
        $product_weight += $item->product_weight*$item->qty;
        
        $items .= '
        {
          "images": "img1-static image url",
          "color": "Red",
          "reason": "Damaged Product/Return reason of the product",
          "descr": "'.$products_desc.'",
          "ean": "EAN no. that needs to be checked for a product (apparels)",
          "imei": "IMEI no. that needs to be checked for a product (mobile phones)",
          "brand": "Brand of the product",
          "pcat": "apparels",
          "si": "special instruction for FE",
          "item_quantity" : '.$item_qty.'
        },';
        // $shipments .= '{
        //   "add": "'.$add.'",
        //   "phone": "'.$phone.'",
        //   "payment_mode": "'.$payment_mode.'",
        //   "name": "'.$name.'",
        //   "pin": '.$pin.',
        //   "order": "'.$item_id.'",
        //   "city": "'.$city.'",
        //   "weight": "'.$product_weight.'",
        //   "products_desc": "'.$products_desc.'",
        //   "total_amount": '.$item_total_amount.',
        //   "quantity": "'.$item_qty.'"
        //   '.$cod_amount.'
        // }';
        $order_qty = $order_qty+$item_qty;
      }
      $items = rtrim($items,',');
      $items .= ']';
      $shipments .= '{
        "add": "'.$add.'",
        "phone": "'.$phone.'",
        "payment_mode": "'.$payment_mode.'",
        "name": "'.$name.'",
        "pin": '.$pin.',
        "order": "'.$order_id.'",
        "city": "'.$city.'",
        "weight": "'.$product_weight.'",
        '.$items.',
        "total_amount": '.$order_total.',
        "quantity": "'.$order_qty.'"
        '.$cod_amount.'
      }';
    }

    $postRequest=  'format=json&data={
                  "shipments": ['.$shipments.'],
                  "pickup_location": {
                  "name": "GR 0068088"
                  }
                  }';
    // $postRequest = json_encode($postRequest);
    // dd($postRequest);
    // "waybill": "'.$bulk_waybills.'",
    $curl = curl_init();

    $headr = array();
    $headr[] = 'Access: 0';
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: Token '.$accesstoken;

    curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, ''.$postRequest);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;

  }
}
//cart total with all prices
if (!function_exists('get_cart_amounts'))
{
    function get_cart_amounts()
    {
      $cart_total = new stdClass();
      $cart_mrp_total = 0;
      $cart_discounted_total = 0;
      $coupon_discount = 0;
      $total_discount = 0;
      // dd('test');
      if(isset(auth()->user()->id))
      {
        $user_id = auth()->user()->id;
        if($user_id)
        {
          // $cart_total = Cart::where('user_id',$user_id)->with(['products'])->select(DB::raw('sum(products.product_discounted_price*cart.qty) as cart_total'))->first();
          // $cart_total = Cart::where('user_id',$user_id)->join('products', 'products.product_id', '=', 'cart.product_id')->select(DB::raw('sum(products.product_price*cart.qty) as cart_mrp_total, sum(products.product_discounted_price*cart.qty) as cart_discounted_total'))->first();
          $cart = Cart::where('user_id',$user_id)->with(['products','product_variant'])->get();
          foreach ($cart as $item)
          {
            $join_table = 'products';
            if($item->product_type=="configurable")
            {
              $join_table = 'product_variant';
            }
            if($item->{$join_table}->product_discounted_price!=null && $item->{$join_table}->product_discount!=0)
            {
              $cart_mrp_total += $item->{$join_table}->product_price*$item->qty;
              $cart_discounted_total += $item->{$join_table}->product_discounted_price*$item->qty;
            }
            elseif($item->{$join_table}->product_discounted_price!=null && $item->{$join_table}->product_discount==0)
            {
              $cart_mrp_total += $item->{$join_table}->product_discounted_price*$item->qty;
              $cart_discounted_total += $item->{$join_table}->product_discounted_price*$item->qty;
            }
            else
            {
              $cart_mrp_total += $item->{$join_table}->product_price*$item->qty;
              $cart_discounted_total += $item->{$join_table}->product_discounted_price*$item->qty;
            }
          }
          $cart_total->cart_mrp_total = $cart_mrp_total;
          $cart_total->cart_discounted_total = $cart_discounted_total;
        }

        if ($cart_total)
        {
          //total_discount
          $total_discount = $cart_total->cart_mrp_total-$cart_total->cart_discounted_total;
          //coupon discount
          $cart_coupon = CartCoupons::where('user_id',$user_id)->with('coupon')->first();

          if(isset($cart_coupon->coupon))
          {
            $paymentDate = date('Y-m-d');
            $paymentDate = date('Y-m-d', strtotime($paymentDate));
            $contractDateBegin = date('Y-m-d', strtotime($cart_coupon->coupon->start_date));
            $contractDateEnd = date('Y-m-d', strtotime($cart_coupon->coupon->end_date));

            if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd))
            {
              if($cart_coupon->coupon->coupon_type == 'flat')
              {
                $coupon_value = $cart_coupon->coupon->value;
                $coupon_discount = $coupon_value;
              }
              else
              {
                $coupon_value = $cart_coupon->coupon->value;
                if (isset($cart_total->cart_mrp_total))
                {
                  $coupon_discount = ($cart_total->cart_mrp_total*$coupon_value)/100;
                }
              }
            }
            else
            {
            }
          }
        }
      }

      return (object)['cart'=>$cart_total,'total_discount'=>$total_discount,'coupon_discount'=>$coupon_discount];
    }
}

if (!function_exists('order_status'))
{
    function order_status($orders)
    {
      $progress = "PROCESSING";
      if($orders->order_return_flag == 1 && $orders->order_return_status == 1)
      {
        $progress = "RETURNED & REFUNDED";
      }
      else if($orders->order_return_flag == 1 && $orders->order_return_status == 0 && $orders->order_return_status != NULL)
      {
        // dd($orders);
        $progress = "RETURNED";
      }
      else if ($orders->order_return_flag == 1)
      {
        $progress = "RETURNING";
      }
      else
      {
        if ($orders->cancel_order_flag == 1)
        {
          $progress = "CANCELLED";
        }
        else
        {
          if ($orders->confirmed_stage == 1)
          {
            $progress = "CONFIRMED";
          }
          if ($orders->preparing_order_stage == 1)
          {
            $progress = "PREPARING YOUR ORDER";
          }
          if ($orders->shipped_stage == 1)
          {
            $progress = "SHIPPED";
          }
          if ($orders->out_for_delivery_stage == 1)
          {
            $progress = "OUT FOR DELIVERY";
          }
          if ($orders->delivered_stage == 1)
          {
            $progress = "DELIVERED";
          }
        }
      }

      return $progress;
    }
}

if (!function_exists('order_return_days_status'))
{
    function order_return_days_status($orders)
    {
      $return_status = false;

      if ($orders->delivered_stage == 1 && $orders->delivered_date != '')
      {
        $order_return = OrderReturnManagement::first();

        $todayDate = date('Y-m-d H:i:s');
        $todayDate = date('Y-m-d H:i:s', strtotime($todayDate));

        $deliveryDate = $orders->delivered_date;
        $deliveryDate = date('Y-m-d H:i:s', strtotime($deliveryDate));

        $contractDateEnd = date('Y-m-d H:i:s', strtotime($deliveryDate.' +'.$order_return->order_return_max_days.' days'));
        // dd($todayDate);
        if ($todayDate <= $contractDateEnd)
        {
          $return_status = true;
        }
      }


      return $return_status;
    }
}

if (!function_exists('order_cancel_days_status'))
{
    function order_cancel_days_status($orders)
    {
      $cancel_status = false;

      // if ($orders->delivered_stage == 1 && $orders->delivered_date != '')
      // {
        $order_cancel = OrderCancelManagement::first();

        // $Date1 = '2010-09-17';
        $todayDate = date('Y-m-d H:i:s');
        $tdate = new DateTime($todayDate);
        // $tdate->add(new DateInterval('P1D')); // P1D means a period of 1 day
        $todayDate = $tdate->format('Y-m-d H:i:s');

        // $todayDate = date('Y-m-d h:i:s', strtotime($todayDate));

        $deliveryDate = $orders->created_at;
        $ddate = new DateTime($deliveryDate);
        // $ddate->add(new DateInterval('P1D')); // P1D means a period of 1 day
        $deliveryDate = $ddate->format('Y-m-d H:i:s');
        // $deliveryDate = date('Y-m-d h:i:s', strtotime($deliveryDate));

        $cdate = new DateTime($deliveryDate);
        $cdateincd = 'P'.$order_cancel->order_cancel_max_days.'D';
        $cdateinch = 'PT'.$order_cancel->order_cancel_max_hours.'H';
        // $cdate->add(new DateInterval($cdateincd)); // P1D means a period of 1 day
        $cdate->add(new DateInterval($cdateinch)); // P1D means a period of 1 day
        $contractDateEnd = $cdate->format('Y-m-d H:i:s');
        // $contractDateEnd = date('Y-m-d h:i:s', strtotime('+'.$order_cancel->order_cancel_max_days.' days +'.$order_cancel->order_cancel_max_hours.' hours',strtotime($deliveryDate)));
        // dd($cdateinch.' '.$todayDate.' '.$contractDateEnd.' '.$deliveryDate);
        if ($todayDate <= $contractDateEnd)
        {
          $cancel_status = true;
        }
      // }


      return $cancel_status;
    }
}

if (!function_exists('warehouse_creation'))
{
  // $md(string) : modeBilling mode of shipment(E: Express/ S: Surface)(mandatory)
  // $cgm(int32) : Weight of shipment in grams(Mandatory)
  // $o_pin(int32) : Origin center's Pin Code (optional)
  // $d_pin(int32) : Pin code of destination city (optional)
  // $ss(string) : Status of shipment Delivered,RTO,DTO(Mandatory)

  function warehouse_creation()
  {
    $accesstoken = 'ec1ee821dc6ca3355a018d48828d3e2ccb892de7';
    $url = "https://staging-express.delhivery.com/api/backend/clientwarehouse/create/";

    $postRequest=  '{
                    "phone": "7498042995",
                    "city": "Greater Thane",
                    "name": "DILIPKUMAR PARWANI",
                    "pin": "421004",
                    "address": "HARNAM APARTMENT 403, 4TH FLOOR, SECTION 30, ULHASNAGAR, 421004 , Ulhasnagar, Maharashtra ,India 421004",
                    "country": "India",
                    "email": "grparwanitradingco@gmail.com",
                    "registered_name": "GR0068088",
                     "return_address": "HARNAM APARTMENT 403, 4TH FLOOR, SECTION 30, ULHASNAGAR, 421004 , Ulhasnagar, Maharashtra ,India 421004",
                     "return_pin":"421004",
                     "return_city":"Greater Thane",
                     "return_state":"Maharashtra",
                     "return_country": "India"
                  }';
    // $postRequest = json_encode($postRequest);
    $curl = curl_init();

    $headr = array();
    $headr[] = 'Access: 0';
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: Token '.$accesstoken;

    curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, ''.$postRequest);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;

  }
}

if (!function_exists('get_state'))
{
    function get_state($state_code)
    {
      $state = "";
      if(isset($state_code) && $state_code!='')
      {
        $state = StateCodes::where('state_code',$state_code)->first();
      }
      return $state;
    }
}

if (!function_exists('create_bulk_waybill'))
{
    function create_bulk_waybill($count)
    {
      $accesstoken = 'ec1ee821dc6ca3355a018d48828d3e2ccb892de7';//c7bd1bd7f2ed46a36775d63e8c9ee43617ffee49
      $cl = 'GR0068088SURFACE-B2C';
      $url = "https://staging-express.delhivery.com/waybill/api/bulk/json/?token=".$accesstoken."&cl=".$cl."&count=".$count;
      $curl = curl_init();

      $headr = array();
      $headr[] = 'Access: 0';
      $headr[] = 'Content-type: application/json';
      $headr[] = 'Authorization: Token '.$accesstoken;

      curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($curl);
      curl_close($curl);
      // dd($data);
      return $data;
    }
}

if (!function_exists('track_order'))
{
    function track_order($waybill)
    {
      $accesstoken = 'ec1ee821dc6ca3355a018d48828d3e2ccb892de7';//c7bd1bd7f2ed46a36775d63e8c9ee43617ffee49
      $cl = 'GR0068088SURFACE-B2C';
      $url = "https://staging-express.delhivery.com/api/v1/packages/json/?token=".$accesstoken."&waybill=".$waybill;
      $curl = curl_init();

      $headr = array();
      $headr[] = 'Access: 0';
      $headr[] = 'Content-type: application/json';
      $headr[] = 'Authorization: Token '.$accesstoken;

      curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($curl);
      curl_close($curl);
      // dd($data);
      return $data;
    }
}

if (!function_exists('packing_slip'))
{
    function packing_slip($waybill)
    {
      $accesstoken = 'ec1ee821dc6ca3355a018d48828d3e2ccb892de7';//c7bd1bd7f2ed46a36775d63e8c9ee43617ffee49
      $cl = 'GR0068088SURFACE-B2C';
      $url = "https://staging-express.delhivery.com/api/p/packing_slip?token=".$accesstoken."&wbns=".$waybill;
      $curl = curl_init();

      $headr = array();
      $headr[] = 'Access: 0';
      $headr[] = 'Content-type: application/json';
      $headr[] = 'Authorization: Token '.$accesstoken;

      curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($curl);
      curl_close($curl);
      // dd($data);
      return $data;
    }
}

if (!function_exists('get_cart_product_weight'))
{
    function get_cart_product_weight()
    {
      $cart_total = new stdClass();
      if(isset(auth()->user()->id))
      {
        $user_id = auth()->user()->id;
        if($user_id)
        {
          // $cart_total = Cart::where('user_id',$user_id)->with(['products'])->select(DB::raw('sum(products.product_discounted_price*cart.qty) as cart_total'))->first();
          // $cart_total = Cart::where('user_id',$user_id)->join('products', 'products.product_id', '=', 'cart.product_id')->select(DB::raw('sum(products.product_weight*cart.qty) as cart_total_product_weight'))->first();
          $cart = Cart::where('user_id',$user_id)->with(['products','product_variant'])->get();
          $product_wt = 0;
          foreach ($cart as $item)
          {
            $join_table = 'products';
            if($item->product_type=="configurable")
            {
              $join_table = 'product_variant';
            }
            $product_wt = $product_wt+($item->{$join_table}->product_weight*$item->qty);
          }
          $cart_total->cart_total_product_weight = $product_wt;
          $cart_total = ($cart_total->cart_total_product_weight)?$cart_total->cart_total_product_weight:0;
        }
      }

      return $cart_total;
    }
}


if (!function_exists('order_cancellation'))
{

  function order_cancellation($waybill)
  {
    $accesstoken = 'ec1ee821dc6ca3355a018d48828d3e2ccb892de7';
    $url = "https://staging-express.delhivery.com/api/p/edit";

    $postRequest=  '{"cancellation": "true",
                    "waybill": "'.$waybill.'"
                  }';
    // $postRequest = json_encode($postRequest);

    // "waybill": "'.$bulk_waybills.'",
    $curl = curl_init();

    $headr = array();
    $headr[] = 'Access: 0';
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: Token '.$accesstoken;

    curl_setopt($curl, CURLOPT_HTTPHEADER,$headr);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, ''.$postRequest);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    curl_close($curl);

    return $data;

  }
}

//mahesh 09-02-2022 
//for setting status wise color for orders
if (!function_exists('order_status_color'))
{
    function order_status_color($orders)
    {
      $status_color = "order-status-color-default";
      if($orders->order_return_flag == 1 && $orders->order_return_status == 1)
      {
        $status_color = "order-status-color-return";
      }
      else if($orders->order_return_flag == 1 && $orders->order_return_status == 0 && $orders->order_return_status != NULL)
      {
        $status_color = "order-status-color-return";
      }
      if ($orders->order_return_flag == 1)
      {
        $status_color = "order-status-color-return";
      }
      else
      {
        if ($orders->cancel_order_flag == 1)
        {
          $status_color = "order-status-color-cancellation";
        }
        else
        {
          if ($orders->confirmed_stage == 1)
          {
            $status_color = "order-status-color-confirmed";
          }
          if ($orders->preparing_order_stage == 1)
          {
            $status_color = "order-status-color-preparing";
          }
          if ($orders->shipped_stage == 1)
          {
            $status_color = "order-status-color-shipped";
          }
          if ($orders->out_for_delivery_stage == 1)
          {
            $status_color = "order-status-color-out-of-delivery";
          }
          if ($orders->delivered_stage == 1)
          {
            $status_color = "order-status-color-delivery";
          }
        }
      }

      return $status_color;
    }
}

?>
