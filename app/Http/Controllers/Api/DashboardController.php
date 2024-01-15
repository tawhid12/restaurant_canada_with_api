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
  public function deliveryBoyDashboard($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    /* ==Processing Order== */
    $data['processing_order'] = Order::where(['order_status_id' => 2, 'driver_id' => $user->id])->count();
    /* ==Complete Order== */
    $data['ride_complete'] = Order::where(['order_status_id' => 5, 'driver_id' => $user->id])->count();
    /* ==Total Rating Count== */
    $data['total_rating'] = Order::where('driver_id', $user->id)->count();
    /* ==Total Payment== */
    $data['total_payment'] = Order::where(['order_status_id' => 5, 'driver_id' => $user->id])->count();
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
    $orders = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->whereNull('driver_id')->where('order_status_id', 1)->where(['owner_id' => $user->id])->get();
    if (count($orders) > 0) {
      foreach ($orders as $order) {
        $order_data = [
          'id' => $order->id,
          'customer' => $order->user?$order->user->name : null,
          'restaurant' => $order->restaurant?$order->restaurant->name : null,
          'order_detl' => [],
          'delivery_address' => $order->delivery_address,
          'payment' => $order->payment,
        ];

        foreach ($order->order_detl as $cart) {
          $order_data['order_detl'][] = [
            'food_id' => $cart->food?$cart->food->name : null,
            'user_id' => $cart->user_id,
            'quantity' => $cart->quantity,
            'price_per_qty' => $cart->price,
            'order_date' => $cart->created_at->format('d-m-Y'),
            'order_time' => $cart->created_at->format('H:i:s')
          ];
        }
        $data[] = $order_data;
      }
    }
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('data' => []), 200);
  }
  public function processingOrder($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->whereNotNull('driver_id')->where('order_status_id', 2)->where(['owner_id' => $user->id])->get();
    if (count($orders) > 0) {
      foreach ($orders as $order) {
        $order_data = [
          //'id' => $order->id,
          'customer' => $order->user?$order->user->name : null,
          'restaurant' => $order->restaurant?$order->restaurant->name : null,
          'order_detl' => [],
          'delivery_address' => $order->delivery_address,
          'payment' => $order->payment,
        ];

        foreach ($order->order_detl as $cart) {
          $order_data['order_detl'][] = [
            'food_id' => $cart->food?$cart->food->name : null,
            'user_id' => $cart->user_id,
            'quantity' => $cart->quantity,
            'price_per_qty' => $cart->price,
          ];
        }
        $data[$order->id] = $order_data;
      }
    }
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
    return response()->json(array('data' => []), 200);
  }
  public function completeOrder($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->whereNotNull('driver_id')->where('order_status_id', 5)->where(['owner_id' => $user->id])->get();
    if (count($orders) > 0) {
      foreach ($orders as $order) {
        $order_data = [
          //'id' => $order->id,
          'customer' => $order->user?$order->user->name : null,
          'restaurant' => $order->restaurant?$order->restaurant->name : null,
          'order_detl' => [],
          'delivery_address' => $order->delivery_address,
          'payment' => $order->payment,
        ];

        foreach ($order->order_detl as $cart) {
          $order_data['order_detl'][] = [
            'food_id' => $cart->food?$cart->food->name : null,
            'user_id' => $cart->user_id,
            'quantity' => $cart->quantity,
            'price_per_qty' => $cart->price,
          ];
        }
        $data[$order->id] = $order_data;
      }
    }
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
    return response()->json(array('data' => []), 200);
  }
  public function orderById($token, $id)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);

    $order = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
      /*'order_detl'*/
    ])->where(['id' => $id])->first();


    foreach ($order->order_detl as $cart) {
      $order_data[]   = array(
        'food_id' => $cart->food?$cart->food->name : null,
        'user_id' => $cart->user_id,
        'quantity' => $cart->quantity,
        'price_per_qty' => $cart->price,
      );
    }
    $data = array(
      'id' => $order->id,
      'customer' => $order->user?$order->user->name : null,
      'restaurant' => $order->restaurant?$order->restaurant->name : null,
      'order_detl' => $order_data,
      'delivery_address' => $order->delivery_address,
      'payment' => $order->payment,
    );
    /*}*/
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }

  public function orderProcessing($token, $id)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $order = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->where(['id' => $id, 'owner_id' => $user->id, 'order_status_id' => 1])->whereNull('driver_id')->first();
    if ($order) {
      $order->order_status_id = 2;
      $order->updated_at = Carbon::now();
      $order->save();
      $order_data = [
        //'id' => $order->id,
        'customer' => $order->user?$order->user->name : null,
        'restaurant' => $order->restaurant?$order->restaurant->name : null,
        'order_detl' => [],
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      ];

      foreach ($order->order_detl as $cart) {
        $order_data['order_detl'][] = [
          'food_id' => $cart->food?$cart->food->name : null,
          'user_id' => $cart->user_id,
          'quantity' => $cart->quantity,
          'price_per_qty' => $cart->price,
        ];
      }
      $data[$order->id] = $order_data;
    }



    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }

  public function orderReady($token, $id)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $order = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->where(['id' => $id, 'owner_id' => $user->id, 'order_status_id' => 2])->first();

    if ($order) {
      $order->order_status_id = 3;
      $order->updated_at = Carbon::now();
      $order->save();

      $order_data = [
        //'id' => $order->id,
        'customer' => $order->user?$order->user->name : null,
        'restaurant' => $order->restaurant?$order->restaurant->name : null,
        'order_detl' => [],
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      ];

      foreach ($order->order_detl as $cart) {
        $order_data['order_detl'][] = [
          'food_id' => $cart->food?$cart->food->name : null,
          'user_id' => $cart->user_id,
          'quantity' => $cart->quantity,
          'price_per_qty' => $cart->price,
        ];
      }
      $data[$order->id] = $order_data;
    }



    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }

  public function orderCancel($token, $id)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $order = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->where(['id' => $id, 'user_id' => $user->id])->whereIn('order_status_id', [1, 2, 3])->first();
    if ($order) {
      $order->order_status_id = 6;
      $order->updated_at = Carbon::now();
      $order->save();
      $order_data = [
        //'id' => $order->id,
        'customer' => $order->user?$order->user->name : null,
        'restaurant' => $order->restaurant?$order->restaurant->name : null,
        'order_detl' => [],
        'delivery_address' => $order->delivery_address,
        'payment' => $order->payment,
      ];

      foreach ($order->order_detl as $cart) {
        $order_data['order_detl'][] = [
          'food_id' => $cart->food?$cart->food->name : null,
          'user_id' => $cart->user_id,
          'quantity' => $cart->quantity,
          'price_per_qty' => $cart->price,
        ];
      }
      $data[$order->id] = $order_data;
    }

    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }

  public function list_of_order_for_delivery($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->where(['driver_id' => $user->id])->where('order_status_id', 1)->get();
    if (count($orders) > 0) {
      foreach ($orders as $order) {
        $order_data = [
          //'id' => $order->id,
          'customer' => $order->user?$order->user->name : null,
          'restaurant' => $order->restaurant?$order->restaurant->name : null,
          'order_detl' => [],
          'delivery_address' => $order->delivery_address,
          'payment' => $order->payment,
        ];

        foreach ($order->order_detl as $cart) {
          $order_data['order_detl'][] = [
            'food_id' => $cart->food?$cart->food->name : null,
            'user_id' => $cart->user_id,
            'quantity' => $cart->quantity,
            'price_per_qty' => $cart->price,
          ];
        }
        $data[$order->id] = $order_data;
      }
    }
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }
  public function pick_order_for_delivery($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->whereNull('driver_id')->where('order_status_id', 1)->get();
    if (count($orders) > 0) {
      foreach ($orders as $order) {
        $order_data = [
          //'id' => $order->id,
          'customer' => $order->user?$order->user->name : null,
          'restaurant' => $order->restaurant?$order->restaurant->name : null,
          'order_detl' => [],
          'delivery_address' => $order->delivery_address,
          'payment' => $order->payment,
        ];

        foreach ($order->order_detl as $cart) {
          $order_data['order_detl'][] = [
            'food_id' => $cart->food?$cart->food->name : null,
            'user_id' => $cart->user_id,
            'quantity' => $cart->quantity,
            'price_per_qty' => $cart->price,
          ];
        }
        $data[$order->id] = $order_data;
      }
    }
    /*echo '<pre>';
    print_r($data);die;*/
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }
  public function orderComplete($token)
  {
    $user = User::where('api_token', $token)->first();
    if (!$user)
      return response()->json(array('errors' => [0 => 'Token is not valid']), 400);
    $orders = Order::with([
      'delivery_address' => function ($query) use ($user) {
        $query->select('id', 'address', 'latitude', 'longitude');
      },
      'payment' => function ($query) {
        $query->select('id', 'total', 'discount', 'delivery_fee', 'payable');
      },
    ])->where(['driver_id' => $user->id])->where('order_status_id', 5)->get();
    if (count($orders) > 0) {
      foreach ($orders as $order) {
        $order_data = [
          //'id' => $order->id,
          'customer' => $order->user?$order->user->name : null,
          'restaurant' => $order->restaurant?$order->restaurant->name : null,
          'order_detl' => [],
          'delivery_address' => $order->delivery_address,
          'payment' => $order->payment,
        ];

        foreach ($order->order_detl as $cart) {
          $order_data['order_detl'][] = [
            'food_id' => $cart->food?$cart->food->name : null,
            'user_id' => $cart->user_id,
            'quantity' => $cart->quantity,
            'price_per_qty' => $cart->price,
          ];
        }
        $data[$order->id] = $order_data;
      }
    }
    /*echo '<pre>';
    print_r($data);die;*/
    if (!empty($data) && count($data) > 0)
      return response()->json(array('data' => $data), 200);
    else
      return response()->json(array('errors' => [0 => 'No Data found']), 400);
  }
}
