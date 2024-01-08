<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
class AssignDeliveryBoy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $deliveryBoy = $this->findAvailableDeliveryBoy();
        if ($deliveryBoy) {
            $this->order->delivery_boy_id = $deliveryBoy->id;
            $this->order->status = 'assigned';
            $this->order->save();
        } else {
            // No available delivery boy, dispatch the job again after a delay
            Log::info('No available delivery boy. Job will be retried.');
            
        }
    }
    protected function findAvailableDeliveryBoy()
    {
        // Logic to find the first available delivery boy
        $deliveryBoy = User::where(['roleId' => 4,'status' => 5])->get();
    }
}
