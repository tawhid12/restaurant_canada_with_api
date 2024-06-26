<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Food;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;

use DB;
use Session;
use App\Events\OrderPlaced;
class CheckoutController extends Controller
{
    use ResponseTrait;
    public function index(Request $request)
    {
        $cities = City::all();
        $cart = session()->get('cart', []);
        $pids = array_keys((array) session('cart'));
        $products = Food::whereIn('id', $pids)->get();
        $delivery_addresses = DB::table('delivery_addresses')->where('user_id', '=', currentUserId())->get();
        $charge = Restaurant::select('delivery_fee')->find($products[0]->restaurant_id);
        /* get similar product */
        $similarpro = $products->pluck('category_id', 'category_id');
        $similarpro = Food::whereNotIn('id', $pids)->whereIn('category_id', $similarpro)->limit(12)->get();
        return view('checkout', compact('cities', 'cart', 'products', 'delivery_addresses', 'charge'));
    }
    public function finalCheckout(Request $request)
    {
        /*echo '<pre>';
        print_r($request->toArray());die;*/
        $request->validate(
            [
                'delivery_address_id' => 'required',
            ]
        );
        if (!session()->get('user'))
            return redirect(route('signInForm'));


        $cart = session()->get('cart', []);
        $restaurant = Restaurant::find($request->restaurant_id);
        $cal_cart = $this->cal_cart($restaurant->delivery_fee);

        if (count((array) session('cart')) <= 0)
            return redirect(route('cart'))->with($this->responseMessage(false, null, "You have no product in cart."));
        else {
            DB::beginTransaction();
            try {
                /*==Insert Data into Cart Table (New Order Received) ====*/

                $cart = session()->get('cart', []);
                $pids = array_keys((array) session('cart'));
                $products = Food::whereIn('id', $pids)->get();

                /*==Insert Data into Order Table (New Order Received) ====*/
                $order = new Order();
                $order->user_id = encryptor('decrypt', request()->session()->get('user'));
                $order->restaurant_id = $request->restaurant_id;
                $order->owner_id = $request->owner_id;
                $order->cart = base64_encode(json_encode(array("cart" => $cart, "cal_cart" => $cal_cart)));
                $order->order_status_id = 1;
                $order->delivery_fee = $restaurant->delivery_fee;
                $order->delivery_address_id = $request->delivery_address_id;
                $order->total = str_replace(',', '', $cal_cart["total"]);
                $order->discount = str_replace(',', '', $cal_cart["discount"]);
                $order->delivery_fee = $restaurant->delivery_fee;
                $order->payable =  str_replace(',', '', $cal_cart["sub_total"]);
                //$order->payment_id = DB::getPdo()->lastInsertId();



                if ($order->save()) {

                    //$order->assignDeliveryBoy();
                    //event(new OrderPlaced($order));
                    /*==Insert Data into payment Table (New Order Received) ====*/
                    $insertedId = DB::table('payments')->insertGetId(
                        [
                            'order_id' => $order->id,
                            'total' => str_replace(',', '', $cal_cart["total"]),
                            'discount' => str_replace(',', '', $cal_cart["discount"]),
                            'delivery_fee' => $restaurant->delivery_fee,
                            'payable' => str_replace(',', '', $cal_cart["sub_total"]),
                            'user_id' => encryptor('decrypt', request()->session()->get('user')),
                            'restaurant_id' => $request->restaurant_id,
                            'owner_id' => $request->owner_id,
                            'method' => 1,
                            'created_at' => Carbon::now()
                        ]
                    );
                    if (count((array) $products) > 0) {
                        foreach ($products as $item) {
                            $foods['order_id'] = $order->id;
                            $foods['food_id'] = $item->id;
                            $foods['user_id'] = encryptor('decrypt', request()->session()->get('user'));
                            $foods['quantity'] = $cart[$item->id]['quantity'];
                            $foods['price'] = $cart[$item->id]['dis_price']>0?$cart[$item->id]['dis_price']:$cart[$item->id]['price'];
                            $foods['discount'] = $cart[$item->id]['dis_price']>0?$cart[$item->id]['discount']:0;
                            $foods['restaurant_id'] = $cart[$item->id]['restaurant_id'];
                            $foods['owner_id'] = $request->owner_id;
                            $foods['created_at'] = Carbon::now();
                            DB::table('carts')->insert($foods);
                        }
                    }
                    $order = $order->find($order->id);
                    $order->payment_id = $insertedId;
                    $order->save();
                    DB::commit();
                    Session::forget('cart');
                    Session::forget('cal_cart');
                    Session::forget('promo_code');
                    return redirect(route('thank_you', $order->id))->with($this->responseMessage(true, null, "data saved!"));
                }
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
                return redirect()->back()->with($this->responseMessage(false, "error", "Please try again!"));
            }
        }
    }

    public function cal_cart($delivery_fee)
    {
        //var_dump($delivery_fee);die;
        $total = 0;
        $t_discount = 0;

        $cart = session()->get('cart', []);
        foreach ($cart as $c) {
            $total += $c['price'] * $c['quantity'];
            $t_discount += $c['quantity'] * $c['discount'];
        }
        $cal_cart = array(
            "total" => number_format($total+$delivery_fee,2),
            "discount" => number_format($t_discount, 2),
            "sub_total" => number_format(($total+$delivery_fee) - $t_discount,2)
        );

        session()->put('cal_cart', $cal_cart);
        return $cal_cart;
    }

    public function thank_you($order)
    {
        $cities = City::all();
        return view('thank-you', compact('cities', 'order'));
    }

    public function view_order($order)
    {
        $order = Order::find($order);
        $cities = City::all();
        $cart = json_decode(base64_decode($order->cart), true);
        //dd($cart);
        return view('backend.dashboard.customer_dashboard', compact('cities', 'cart', 'order'));
    }

    public function promoCode(Request $request)
    {
        $type = "";
        $msg = "";
        $promoCode = "";
        $promo_dis = 0;
        if (empty($request->code)) {
            $type = 2;
            $msg = "Promo code Empty";
        } else {
            $promoCode = Coupon::where('code', 'like', '%' . $request->code . '%')->first();
            if ($promoCode) {
                $type = 1;
                $msg = "Promo code Applied";
                $cart = session()->get('cart', []);
                $total = 0;
                foreach ($cart as $c) {
                    $total += $c['dis_price'] * $c['quantity'];
                }
                if ($promoCode->discount_type = 1) {
                    $rate = ($promoCode->discount) / 100;
                    $dis_amt = floor($total * $rate);
                    if ($dis_amt >= $promoCode->max_discount)
                        $dis_amt = $promoCode->max_discount;
                } else {
                    $dis_amt = $promoCode->discount;
                }
                session()->put('promo_code', $dis_amt);
            } else {
                $type = 2;
                $msg = "Invalid Promo Code";
            }
        }
        return response()->json(array("msg" => $msg, 'type' => $type), 200);
    }
}
