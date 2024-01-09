<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\OrderPlaced;

class Order extends Model
{
    use HasFactory;
    public function restaurants(){
        return $this->belongsTo(Restaurant::class);
    }
    public function delivery_address(){
        return $this->belongsTo(DeliveryAddress::class,'delivery_address_id','id');
    }
    public function payment(){
        return $this->belongsTo(Payment::class,'payment_id','id');
    }
    public function order_detl(){
        return $this->hasMany(Cart::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
    /*public function assignDeliveryBoy()
    {
        OrderPlaced::dispatch($this);
    }*/
}    
