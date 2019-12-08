<?php


namespace App\Http\Controllers;


use App\Event;
use App\AgeGroup;
use App\EventAgeGroup;
use App\KeyWord;
use App\Type;
use App\EventType;
use App\EventKeyWord;
use App\Order;
use App\OrderAgeGroup;
use App\OrderType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class EventController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:event-create', ['only' => ['create','store', 'addType', 'createType']]);
         $this->middleware('permission:event-order', ['only' => ['order', 'subscribe', 'noOrder', 'editOrder', 'orderEvents', 'saveOrders']]);
    }

    public function index()
    {

        $events = Event::all();

        $eventsWithKeys = Event::join('event_keywords', 'id', 'fk_event_keyword')
            ->join('keywords', 'keywords.id', 'fk_keyword')->get();

        $regions =array();
        foreach ($events as $event){
            if(!in_array($event->region, $regions)){
                array_push($regions, $event->region);
            }
        }

        $keywords = Order::select(DB::raw('count(keywords.word) as counter, keywords.word'))
            ->join('events', 'events.id', 'fk_order_event')
            ->join('event_keywords', 'event_keywords.fk_event_keyword', 'events.id')
            ->join('keywords', 'keywords.id', 'event_keywords.fk_keyword')
            ->groupBy('keywords.word')->orderBy('counter', 'desc')->limit(5)->get();

        return view('events.index', compact('events', 'regions', 'eventsWithKeys', 'keywords'));
    }

    public function keywords($keyword){
        $events = Event::select('events.id', 'events.name', 'events.image', 'events.time', 'events.date', 'events.region')
            ->join('event_keywords', 'id', 'fk_event_keyword')
            ->join('keywords', 'keywords.id', 'fk_keyword')
            ->where('keywords.word', $keyword)->get();

        $eventsWithKeys = Event::join('event_keywords', 'id', 'fk_event_keyword')
            ->join('keywords', 'keywords.id', 'fk_keyword')->get();

        $allEvents=Event::all();
        $regions =array();
        foreach ($allEvents as $event){
            if(!in_array($event->region, $regions)){
                array_push($regions, $event->region);
            }
        }
        $keywords = Order::select(DB::raw('count(keywords.word) as counter, keywords.word'))
            ->join('events', 'events.id', 'fk_order_event')
            ->join('event_keywords', 'event_keywords.fk_event_keyword', 'events.id')
            ->join('keywords', 'keywords.id', 'event_keywords.fk_keyword')
            ->groupBy('keywords.word')->orderBy('counter', 'desc')->limit(5)->get();

        return view('events.index', compact('events', 'regions', 'keywords', 'eventsWithKeys'));
    }

    public function create()
    {
        $ageGroups = AgeGroup::all();
        $parentTypes = Type::where('fk_parent_type',NULL)->get();
        $events = Event::all();
        return view('events.create', compact('ageGroups', 'parentTypes', 'events'));
    }

    public function subtypes($typeId, &$types){

        $type = Type::with('subtype')->where('id', $typeId)->first();
        if(count($type['subtype']) == 0){
            return;
        }

        foreach ($type['subtype'] as $subtype){
            array_push($types, $subtype);
        }
        foreach ($type['subtype'] as $subtype) {
            $this->subtypes($subtype->id, $types);
        }
    }

    function multiexplode ($delimiters,$string) {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'pavadinimas' => 'required',
            'data' => 'required|after:today',
            'laikas' => 'required',
            'paveikslėlis' => 'required',
            'regionas' => 'required',
            'adresas' => 'required',
            'amžiaus_grupė' => 'required',
            'tipas' => 'required',
            'raktinis_zodis' => 'required|regex:/^(#[A-Ž0-9a-ž]+ ?)+$/m'
        ]);

        //dd($request->input('renginio_nuoroda'));
        $free = '0';
        if($request->input('nemokama')=="on"){
            $free = '1';
        }
        if ($free == '0') {
            $this->validate($request, [
                'nuoroda_pirkti' => 'required'
            ]);
        }

        $keywords = $request->input('raktinis_zodis');
        $keywordsArray = $this->multiexplode(array("#"," #"), $keywords);
        for($i = 1; $i < count($keywordsArray); $i++){
            $existingWord = KeyWord::where('word', $keywordsArray[$i])->first();
            if($existingWord == null){
                KeyWord::create([
                    'word' => $keywordsArray[$i]
                ]);
            }
        }
        if ($free == '1') {
            $event = Event::create([
                'name' => $request->input('pavadinimas'),
                'date' => $request->input('data'),
                'time' => $request->input('laikas'),
                'image' => $request->input('paveikslėlis'),
                'region' => $request->input('regionas'),
                'address' => $request->input('adresas'),
                'free' => $free,
                'fk_link' => $request->input('renginio_nuoroda')
            ]);
        }else{
            $event = Event::create([
                'name' => $request->input('pavadinimas'),
                'date' => $request->input('data'),
                'time' => $request->input('laikas'),
                'image' => $request->input('paveikslėlis'),
                'region' => $request->input('regionas'),
                'address' => $request->input('adresas'),
                'free' => $free,
                'fk_link' => $request->input('renginio_nuoroda'),
                'link_to_buy' => $request->input('nuoroda_pirkti')
            ]);
        }

        $ageGroups = $request->input('amžiaus_grupė');
        foreach ($ageGroups as $ageGroup){
            EventAgeGroup::create([
                'fk_event_age_group' => $event['id'],
                'fk_age_group' => $ageGroup
            ]);
        }

        $types = $request->input('tipas');
        $allTypes = array();
        foreach ($types as $typeId){
            $type = Type::where('id', $typeId)->first();
            if(!in_array($type, $allTypes)){
                array_push($allTypes, $type);
                $this->subtypes($typeId, $allTypes);
            }
        }

        foreach ($allTypes as $type){
            EventType::create([
                'fk_event_type' => $event['id'],
                'fk_type' => $type->id
            ]);
        }

        for($i = 1; $i < count($keywordsArray); $i++){
            $keyword = KeyWord::where('word', $keywordsArray[$i])->first();
            EventKeyWord::create([
                'fk_event_keyword' => $event['id'],
                'fk_keyword' => $keyword->id
            ]);
        }

        return redirect()->route('events.index')
            ->with('success','Renginys sėkmingai sukurtas');

    }


    public function order()
    {
        $ageGroups = AgeGroup::all();
        $parentTypes = Type::where('fk_parent_type',NULL)->get();
        return view('events.order', compact('ageGroups', 'parentTypes'));
    }


    public function show($id)
    {
        $event = Event::where('id', $id)->first();
        $forLinks = $event;

        $eventWithKeys = Event::join('event_keywords', 'id', 'fk_event_keyword')
            ->join('keywords', 'keywords.id', 'fk_keyword')->where('events.id', $id)->get();

        $sameTypeEvents = array();
        while($forLinks->fk_link != null){
            $forLinks = Event::where('id', $forLinks->fk_link)->first();
            array_push($sameTypeEvents, $forLinks);
        }

        return view('events.show', compact('event', 'eventWithKeys', 'sameTypeEvents'));
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'laiko_periodas' => 'required',
            'prieš_kiek_laiko_pranešti' => 'required'
        ]);

        $existingOrder = Order::where('fk_order_user', Auth::id())->first();
        if($existingOrder != null){
            return redirect()->route('events.order')
                ->with('error','Jūs jau esate užsisakę informavimą, 
                jei norite pakeisti su užsakymu susijusią informaciją, 
                tai galite padaryti užsakymo peržiūros lange.');
        }
        else {
            $ageGroups = $request->input('amžiaus_grupė');
            $types = $request->input('tipas');
            $allTypes = array();
            /*$events = Event::query();
            if (count($types) != 0) {
                foreach ($types as $typeId) {
                    $type = Type::where('id', $typeId)->first();
                    if (!in_array($type, $allTypes)) {
                        array_push($allTypes, $type);
                        $this->subtypes($typeId, $allTypes);
                    }
                }
            }
            if (count($allTypes) != 0) {
                foreach ($allTypes as $type) {
                    $eventsWithType = EventType::where('fk_type', $type->id)->get();
                    $events = $events->where(function ($query) use ($eventsWithType) {
                        foreach ($eventsWithType as $eventWithType) {
                            $query = $query->orWhere('id', $eventWithType->fk_event_type);
                        }
                    });
                }
            }
            if (count($ageGroups) != 0) {
                foreach ($ageGroups as $ageGroup) {
                    $eventsWithAge = EventAgeGroup::where('fk_age_group', $ageGroup)->get();
                    $events = $events->where(function ($query) use ($eventsWithAge) {
                        foreach ($eventsWithAge as $eventWithAge) {
                            $query = $query->orWhere('id', $eventWithAge->fk_event_age_group);
                        }
                    });
                }
            }
            $free = 0;
            if ($request->input('nemokama') == "on") {
                $free = 1;
                $events = $events->where('free', '1');
            }
            if ($request->input('vieta') != null) {
                $events = $events->where('region', $request->input('vieta'));
            }*/
            $free = 0;
            if ($request->input('nemokama') == "on") {
                $free = 1;
            }
            $period = $request->input('laiko_periodas');
            $inform_before = $request->input('prieš_kiek_laiko_pranešti');
            $region = $request->input('vieta');
            //$events = $this->formOrder($ageGroups, $types, $request->input('nemokama'), $region);

            $order = Order::create([
                'only_free' => $free,
                'participation' => '0',
                'period' => $period,
                'region' => $region,
                'inform_before' => $inform_before,
                'fk_order_user' => Auth::id()
            ]);
            foreach ($ageGroups as $ageGroup) {
                OrderAgeGroup::create([
                    'fk_order_age_group' => $order->id,
                    'fk_ageGroup' => $ageGroup
                ]);
            }
            foreach ($types as $type) {
                OrderType::create([
                    'fk_order_type' => $order->id,
                    'fk_type_id' => $type
                ]);
            }
            /*foreach ($events as $event) {
                $order = Order::create([
                    'only_free' => 1,
                    'participation' => '0',
                    'period' => $period,
                    'region' => $region,
                    'inform_before' => $inform_before,
                    'fk_order_event' => $event->id,
                    'fk_order_user' => Auth::id()
                ]);
                foreach ($ageGroups as $ageGroup){
                    OrderAgeGroup::create([
                        'fk_order_age_group' => $order->id,
                        'fk_ageGroup' => $ageGroup
                    ]);
                }
                foreach ($types as $type){
                    OrderType::create([
                        'fk_order_type' => $order->id,
                        'fk_type_id' => $type
                    ]);
                }

            }*/
        }
        return redirect()->route('events.order')
            ->with('success','Informacija sėkmingai užsakyta');
    }

    public function filter(Request $request){

        $events = array();
        if($request->input('regionas') != null){
            $events = Event::where('region', $request->input('regionas'))->get();
        }
        if($request->input('nuo-kada') != null){
            $events = Event::where('date', '>=',$request->input('nuo-kada'))->get();
        }
        if($request->input('iki-kada') != null){
            $events = Event::where('date', '>=',$request->input('iki-kada'))->get();
        }
        if($request->input('regionas') == null && $request->input('nuo-kada') == null
        && $request->input('iki-kada') == null){
            $events = Event::all();
        }

        $eventsWithKeys = Event::join('event_keywords', 'id', 'fk_event_keyword')
            ->join('keywords', 'keywords.id', 'fk_keyword')->get();

        $allEvents=Event::all();
        $regions =array();
        foreach ($allEvents as $event){
            if(!in_array($event->region, $regions)){
                array_push($regions, $event->region);
            }
        }
        $keywords = Order::select(DB::raw('count(keywords.word) as counter, keywords.word'))
            ->join('events', 'events.id', 'fk_order_event')
            ->join('event_keywords', 'event_keywords.fk_event_keyword', 'events.id')
            ->join('keywords', 'keywords.id', 'event_keywords.fk_keyword')
            ->groupBy('keywords.word')->orderBy('counter', 'desc')->limit(5)->get();

        return view('events.index', compact('events', 'regions', 'eventsWithKeys', 'keywords'));
    }

    public function createType(){
        $types = Type::all();
        return view('events.createType', compact('types'));
    }

    public function addType(Request $request){
        $this->validate($request, [
            'pavadinimas' => 'required',
        ]);

        if($request->input('tėvinis_tipas') != null){
            $type = Type::create([
                'name' => $request->input('pavadinimas'),
                'fk_parent_type' => $request->input('tėvinis_tipas')
            ]);
        } else{
            $type = Type::create([
                'name' => $request->input('pavadinimas')
            ]);
        }

        $ageGroups = AgeGroup::all();
        $parentTypes = Type::where('fk_parent_type',NULL)->get();
        $events = Event::all();

        return view('events.create', compact('ageGroups', 'parentTypes', 'events'));
    }

    public function noOrder(){
        return view('events.noOrderError');
    }

    public function orderInformation(){
        $events = Order::with('event')->where('fk_order_user', Auth::id())->get();

        if(count($events) == 0){
            return redirect()->route('events.noOrder');
        }
        else {
            $orderId = $events[0]->id;
            $ageGroups = AgeGroup::all();
            $parentTypes = Type::where('fk_parent_type', NULL)->get();

            $orderAgeGroups = OrderAgeGroup::where('fk_order_age_group', $orderId)->get();
            $orderTypes = OrderType::where('fk_order_type', $orderId)->get();

            $selectedAgeGroups = array();
            foreach($orderAgeGroups as $ageGroup){
                array_push($selectedAgeGroups, $ageGroup->fk_ageGroup);
            }

            $selectedTypes = array();
            foreach($orderTypes as $type){
                array_push($selectedTypes, $type->fk_type_id);
            }
            return view('events.viewOrder', compact('events', 'ageGroups',
                'parentTypes', 'selectedAgeGroups', 'selectedTypes'));
        }
    }

    public function editOrder(Request $request){
        $existingOrders = Order::where('fk_order_user', Auth::id())->get();
        if(count($existingOrders) == 0){
            return redirect()->route('events.noOrder');
        }
        else {
            $this->validate($request, [
                'laiko_periodas' => 'required',
                'prieš_kiek_laiko_pranešti' => 'required'
            ]);

            foreach ($existingOrders as $existingOrder) {
                $orderAgeGroups = OrderAgeGroup::where('fk_order_age_group', $existingOrder->id)->get();
                foreach ($orderAgeGroups as $ageGroup) {
                    OrderAgeGroup::where('fk_order_age_group', $existingOrder->id)->
                    where('fk_ageGroup', $ageGroup->fk_ageGroup);
                }
                $orderTypes = OrderType::where('fk_order_type', $existingOrder->id)->get();
                foreach ($orderTypes as $type) {
                    OrderType::where('fk_order_type', $existingOrder->id)->
                    where('fk_type_id', $type->fk_type_id);
                }
                Order::where('id', $existingOrder->id)->delete();
            }

            $ageGroups = $request->input('amžiaus_grupė');
            $types = $request->input('tipas');
            /*$allTypes = array();
            $events = Event::query();
            if (count($types) != 0) {
                foreach ($types as $typeId) {
                    $type = Type::where('id', $typeId)->first();
                    if (!in_array($type, $allTypes)) {
                        array_push($allTypes, $type);
                        $this->subtypes($typeId, $allTypes);
                    }
                }
            }
            if (count($allTypes) != 0) {
                foreach ($allTypes as $type) {
                    $eventsWithType = EventType::where('fk_type', $type->id)->get();
                    $events = $events->where(function ($query) use ($eventsWithType) {
                        foreach ($eventsWithType as $eventWithType) {
                            $query = $query->orWhere('id', $eventWithType->fk_event_type);
                        }
                    });
                }
            }
            if (count($ageGroups) != 0) {
                foreach ($ageGroups as $ageGroup) {
                    $eventsWithAge = EventAgeGroup::where('fk_age_group', $ageGroup)->get();
                    $events = $events->where(function ($query) use ($eventsWithAge) {
                        foreach ($eventsWithAge as $eventWithAge) {
                            $query = $query->orWhere('id', $eventWithAge->fk_event_age_group);
                        }
                    });
                }
            }
            $free = 0;
            if ($request->input('nemokama') == "on") {
                $free = 1;
                $events = $events->where('free', '1');
            }
            if ($request->input('vieta') != null) {
                $events = $events->where('region', $request->input('vieta'));
            }*/
            $free = 0;
            if ($request->input('nemokama') == "on") {
                $free = 1;
            }
            $period = $request->input('laiko_periodas');
            $inform_before = $request->input('prieš_kiek_laiko_pranešti');
            $region = $request->input('vieta');
            //$events = $this->formOrder($ageGroups, $types, $free, $region);

            $order = Order::create([
                'only_free' => $free,
                'participation' => '0',
                'period' => $period,
                'region' => $region,
                'inform_before' => $inform_before,
                'fk_order_user' => Auth::id()
            ]);
            foreach ($ageGroups as $ageGroup) {
                OrderAgeGroup::create([
                    'fk_order_age_group' => $order->id,
                    'fk_ageGroup' => $ageGroup
                ]);
            }
            foreach ($types as $type) {
                OrderType::create([
                    'fk_order_type' => $order->id,
                    'fk_type_id' => $type
                ]);
            }
            /*foreach ($events as $event) {
                $order = Order::create([
                    'only_free' => 1,
                    'participation' => '0',
                    'period' => $period,
                    'region' => $region,
                    'inform_before' => $inform_before,
                    'fk_order_event' => $event->id,
                    'fk_order_user' => Auth::id()
                ]);
                foreach ($ageGroups as $ageGroup) {
                    OrderAgeGroup::create([
                        'fk_order_age_group' => $order->id,
                        'fk_ageGroup' => $ageGroup
                    ]);
                }
                foreach ($types as $type) {
                    OrderType::create([
                        'fk_order_type' => $order->id,
                        'fk_type_id' => $type
                    ]);
                }

            }*/

            $orderId = $existingOrders[0]->id;
            $ageGroups = AgeGroup::all();
            $parentTypes = Type::where('fk_parent_type', NULL)->get();

            $orderAgeGroups = OrderAgeGroup::where('fk_order_age_group', $orderId)->get();
            $orderTypes = OrderType::where('fk_order_type', $orderId)->get();

            $selectedAgeGroups = array();
            foreach($orderAgeGroups as $ageGroup){
                array_push($selectedAgeGroups, $ageGroup->fk_ageGroup);
            }

            $selectedTypes = array();
            foreach($orderTypes as $type){
                array_push($selectedTypes, $type->fk_type_id);
            }

            return redirect()->route('events.orderInformation', compact('events', 'ageGroups',
                'parentTypes', 'selectedAgeGroups', 'selectedTypes'))
                ->with('success','Informacija sėkmingai pakeista');
        }
    }

    public function formOrder($ageGroups, $types, $ifFree, $region){

        $allTypes = array();
        $events = Event::query();
        if (count($types) != 0) {
            foreach ($types as $typeId) {
                $type = Type::where('id', $typeId)->first();
                if (!in_array($type, $allTypes)) {
                    array_push($allTypes, $type);
                    $this->subtypes($typeId, $allTypes);
                }
            }
        }
        if (count($allTypes) != 0) {
            $events = $events->where(function ($query) use ($allTypes) {
                foreach ($allTypes as $type) {
                    $eventsWithType = EventType::where('fk_type', $type->id)->get();
                    foreach ($eventsWithType as $eventWithType) {
                        $query = $query->orWhere('id', $eventWithType->fk_event_type);
                    }
                }
            });
        }
        if (count($ageGroups) != 0) {
            $events = $events->where(function ($query) use ($ageGroups) {
                foreach ($ageGroups as $ageGroup) {
                    $eventsWithAge = EventAgeGroup::where('fk_age_group', $ageGroup)->get();
                    foreach ($eventsWithAge as $eventWithAge) {
                        $query = $query->orWhere('id', $eventWithAge->fk_event_age_group);
                    }
                }
            });
        }
        if ($ifFree == 1) {
            $events = $events->where('free', $ifFree);
        }
        if ($region != null) {
            $events = $events->where('region', $region);
        }
        $events = $events->get();
        return $events;
    }

    public function orderEvents(){
        $orders = Order::with('event')
            ->where('fk_order_user', Auth::id())
            ->where('fk_order_event', '!=', null)->get();
        $i = 0;

        if(count($orders) == 0){
            return redirect()->back()->with('error', 'Jums siūlomų renginių dar nėra.');
        }
        return view('events.orderEvents', compact('orders', 'i'));
    }

    public function saveOrders(Request $request){
        $orders = $request->input('orders');
        if(count($orders) != 0){
            foreach($orders as $order){
                $order = json_decode($order);
                $order = Order::where('id', $order)->first();
                $participation = 0;
                $participations = $request->input('participation');
                if(count($participations) != 0){
                    foreach($participations as $key => $value){
                        if($value == $order->id){
                            $participation = 1;
                        }
                    }
                }
                $order->participation = $participation;
                $order->save();
            }
        }
        return redirect()->back()
            ->with('success','Informacija sėkmingai pakeista');
    }

}
