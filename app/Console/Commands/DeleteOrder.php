<?php

namespace App\Console\Commands;

use App\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deleted order information after it has expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = Carbon::create(2019, 12, 9);
        $orders = Order::where('fk_order_event', null)->get();
        foreach($orders as $order){
            if($date->subDays($order->period) >= $order->created_at){
                $allUserOrders = Order::where('fk_order_user', $order->fk_order_user)->get();
                foreach ($allUserOrders as $userOrder){
                    $userOrder->delete();
                }
            }
        }
    }
}
