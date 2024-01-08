<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Jobs\AssignDeliveryBoy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderPlacedListener
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
        AssignDeliveryBoy::dispatch($event->order);
    }
}
