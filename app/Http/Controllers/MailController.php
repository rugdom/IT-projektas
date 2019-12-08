<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Order;

class MailController extends Controller
{
    public function index(){

        $data = Order::with('event')->
            where('fk_order_user', Auth::id())->get();

        Mail::to(Auth::user()->email)->send(new SendMail($data));
    }
}
