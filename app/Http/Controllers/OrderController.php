<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
class OrderController extends Controller
{
    public function index(){
        $orders = Order::where('owner_id',  currentUserId())->orderBy("id","desc")->get();
        
        echo '<pre>';
        print_r($orders);
        die;
    }
}
