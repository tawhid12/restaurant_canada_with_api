<?php

namespace App\Listners;

use App\Events\DriverAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DriverAccepted
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
     * @param  \App\Events\DriverAssigned  $event
     * @return void
     */
    public function handle(DriverAssigned $event)
    {
        //
    }
}
