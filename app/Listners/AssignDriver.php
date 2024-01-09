<?php

namespace App\Listners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Jobs\AssignDeliveryBoy;
class AssignDriver
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderPlaced  $event
     * @return void
     */
    public function handle(OrderPlaced $event)
    {
        Log::info('OrderPlaced event handled', ['order_id' => $event->order->id]);
        $drivers = User::where('roleId', 4)->get();
        // Dispatch the job
        foreach ($drivers as $driver) {
            if($driver->driver->available == 0){
                AssignDeliveryBoy::dispatch($event->order,$driver);
            }
            
        }
        
    }
}
