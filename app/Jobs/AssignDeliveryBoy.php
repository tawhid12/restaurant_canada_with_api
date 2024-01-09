<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class AssignDeliveryBoy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order;
    public $driver;
    protected $tries = 3; // Set the maximum number of attempts
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, User $driver)
    {
        $this->order = $order;
        $this->driver = $driver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Job Response', ['order_id' => $this->order->id,'driver_id' => $this->driver->id]);
        } catch (\Exception $e) {
            // Log or handle the exception
            Log::error('Error processing AssignDeliveryBoy job: ' . $e->getMessage());
        }
    }
}
