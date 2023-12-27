<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bill;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Payment;
use Session;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
  public function ownerDashboard($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    /* ==Pending Order== */
    $data['pending_order'] = Order::where(['order_status_id' => 1, 'owner_id' => $user->id])->count();
    /* ==Complete Order== */
    $data['complete_order'] = Order::where(['order_status_id' => 5, 'owner_id' => $user->id])->count();
    /* ==Total Order Count== */
    $data['total_order'] = Order::where('owner_id', $user->id)->count();
    /* ==Total Payment== */
    $data['total_payment'] = Payment::where(['status' => 1, 'owner_id' => $user->id])->count();
    /* ==Graph Data== */
    /* no instructions */
    if ($data)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }
  public function pendingOrder($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with(['delivery_address','payment'])->where('order_status_id',1)->get();
    /*echo '<pre>';
    print_r($orders->toArray());die;*/
    foreach ($orders as $order) {
      $format_base_64 = base64_decode($order->cart);
      $json_to_array = json_decode($format_base_64, true);
      /*echo '<pre>';
      print_r($json_to_array['cart']);
      die;*/

      $data[$order->id]= array(
        'order_detl' => $json_to_array,
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      );
     
    }
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }
  public function processingOrder($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with(['delivery_address','payment'])->where('order_status_id',2)->get();
    /*echo '<pre>';
    print_r($orders->toArray());die;*/
    foreach ($orders as $order) {
      $format_base_64 = base64_decode($order->cart);
      $json_to_array = json_decode($format_base_64, true);
      /*echo '<pre>';
      print_r($json_to_array['cart']);
      die;*/

      $data[$order->id]= array(
        'order_detl' => $json_to_array,
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      );
     
    }
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }
  public function completeOrder($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with(['delivery_address','payment'])->where('order_status_id',5)->get();
    /*echo '<pre>';
    print_r($orders->toArray());die;*/
    foreach ($orders as $order) {
      $format_base_64 = base64_decode($order->cart);
      $json_to_array = json_decode($format_base_64, true);
      /*echo '<pre>';
      print_r($json_to_array['cart']);
      die;*/

      $data[$order->id]= array(
        'order_detl' => $json_to_array,
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      );
     
    }
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }
  public function orderById($token,$id)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with(['delivery_address','payment'])->where(['id'=>$id, 'user_id' => $user->id])->get();
    /*echo '<pre>';
    print_r($orders->toArray());die;*/
    foreach ($orders as $order) {
      $format_base_64 = base64_decode($order->cart);
      $json_to_array = json_decode($format_base_64, true);
      /*echo '<pre>';
      print_r($json_to_array['cart']);
      die;*/

      $data[$order->id]= array(
        'order_detl' => $json_to_array,
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      );
     
    }
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }

  public function orderProcessing($token,$id)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $order = Order::with(['delivery_address','payment'])->where(['id'=>$id, 'user_id' => $user->id,'order_status_id' => 1])->first();
    if(!empty($order) && count($order) > 0){
      $order->order_status_id = 3;
      $order->save();
      /*echo '<pre>';
      print_r($orders->toArray());die;*/
    
        $format_base_64 = base64_decode($order->cart);
        $json_to_array = json_decode($format_base_64, true);
        /*echo '<pre>';
        print_r($json_to_array['cart']);
        die;*/
  
        $data[$order->id]= array(
          'order_status_id' => $order->order_status_id,
          'order_detl' => $json_to_array,
          'delivery_address' => $order->delivery_address,
          'payment' => $order->payment,
        );
    }else{
      return response()->json(array('errors' => [0 => 'Invalid Operation']), 400);
    }

     
    
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }

  public function orderReady($token,$id)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $order = Order::with(['delivery_address','payment'])->where(['id'=>$id, 'user_id' => $user->id,'order_status_id' => 2])->first();
    $order->order_status_id = 3;
    $order->save();
    /*echo '<pre>';
    print_r($orders->toArray());die;*/
  
      $format_base_64 = base64_decode($order->cart);
      $json_to_array = json_decode($format_base_64, true);
      /*echo '<pre>';
      print_r($json_to_array['cart']);
      die;*/

      $data[$order->id]= array(
        'order_status_id' => $order->order_status_id,
        'order_detl' => $json_to_array,
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      );
     
    
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }

  public function orderCancel($token,$id)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $order = Order::with(['delivery_address','payment'])->where(['id'=>$id, 'user_id' => $user->id]) ->whereIn('order_status_id', [1,2,3])->first();
    $order->order_status_id = 6;
    $order->save();
    /*echo '<pre>';
    print_r($orders->toArray());die;*/
  
      $format_base_64 = base64_decode($order->cart);
      $json_to_array = json_decode($format_base_64, true);
      /*echo '<pre>';
      print_r($json_to_array['cart']);
      die;*/

      $data[$order->id]= array(
        'order_status_id' => $order->order_status_id,
        'order_detl' => $json_to_array,
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      );
     
    
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }

}
