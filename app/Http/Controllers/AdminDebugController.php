<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Exchange;
use App\ExchangeOrder;

use App\Desktop;

class AdminDebugController extends Controller
{
    public function index () {
dd( Desktop::getFilesForUser(Auth::user(),'1/Desktop/D2') );
    	return view ('game');
    	return $this->reduceOrders(5,23);
    	$exchange = Exchange::find(1);
    	return $exchange->getBuyOrders(false,true);
    }

    public function reduceOrders($qty,$price) {
    	$existingOrders = ExchangeOrder::where('company_id',1)->where('exchange_id',1)->where('action','sell')->where('price','>=',$price)->orderBy('price','ASC')->get();
dump($existingOrders);
		$ttl = 0;
		$qtyLeft = $qty;
		foreach($existingOrders as $order) {
			if ($order->qty <= $qtyLeft) {
				$qtyLeft = $qtyLeft-$order->qty;
				$order->delete();
			} else {
				$order->qty = $order->qty-$qtyLeft;
				$order->save();
				$qtyLeft = 0;
			}
			if ($qtyLeft <= 0)
				break;
		}
		dd(     	$existingOrders = ExchangeOrder::where('company_id',1)->where('exchange_id',1)->where('action','sell')->where('price','>=',$price)->orderBy('price','ASC')->get() );
    }
}
