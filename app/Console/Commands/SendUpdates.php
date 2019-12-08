<?php

namespace App\Console\Commands;

use App\Http\Controllers\EventController;
use App\Mail\SendMail;
use App\Order;
use App\OrderAgeGroup;
use App\OrderType;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send mail about new events';

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
        $users = User::all();
        foreach($users as $user){
            $informNow = array();
            $orders = Order::with('event')->where('fk_order_user', $user->id)->get();

            foreach ($orders as $order) {
                $orderAgeGroups = OrderAgeGroup::where('fk_order_age_group', $order->id)->get();
                $orderTypes = OrderType::where('fk_order_type', $order->id)->get();

                $ageGroups = array();
                foreach ($orderAgeGroups as $ageGroup){
                    array_push($ageGroups, $ageGroup->fk_ageGroup);
                }

                $types = array();
                foreach ($orderTypes as $type){
                    array_push($types, $type->fk_type_id);
                }

                $eventController = new EventController();
                $events = $eventController->formOrder($ageGroups, $types, $order->only_free, $order->region);
                foreach ($events as $event) {
                    $existingOrder = Order::where('fk_order_user', $user->id)
                        ->where('fk_order_event', $event->id)->get();
                    echo $event->date;
                    if(count($existingOrder) == 0 && Carbon::now()->addDays($order->inform_before) >= $event->date){
                        $newOrder = Order::create([
                            'only_free' => $order->only_free,
                            'participation' => '0',
                            'period' => $order->period,
                            'region' => $order->region,
                            'inform_before' => $order->inform_before,
                            'fk_order_event' => $event->id,
                            'fk_order_user' => $order->fk_order_user
                        ]);
                        array_push($informNow, $newOrder);
                    }
                }
            }
            if(count($informNow) != 0){
                Mail::to($user->email)->send(new SendMail($informNow));
            }
        }
    }
}
