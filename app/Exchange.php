<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\ExchangeOrder;

class Exchange extends Model
{
    public function shares() {
    	return $this->belongsToMany('App\Share');
    }

    public function orders() {
    	return ExchangeOrder::where('exchange_id',$this->id)->get();
    }

    public function companies() {
    	$companies = ExchangeOrder::distinct('company_id')->pluck('company_id');
    	return Company::find($companies);
    }

    public function processTrade($action,$data) {
    	// sell

    	// owner
    	// company
    	// exchange
    	$owner = $data['owner'];
    	$company = $data['company'];
    	$qty = $data['qty'];
    	$price = $data['price']; 
    	$exchange = $data['exchange'];

		if ($action === "sell") {
	    	$shares = Share::where('owner_id',$owner->id)->where('company_id',$company->id)->whereNull('exchange_id')->first();
	    	if ($shares) {
	    		$shares->qty = $shares->qty-$qty;
	    		$shares->save();
                Game::log('shares',$owner->id." reserved ".$qty." shares for sales at ".$price,array('owner_id'=>$owner->id,'exchange_id'=>$exchange->id,'company_id'=>$company->id));
	    	}

			// Create copy of owner shares for the exchange
			$exchangeShares = new Share;
			$exchangeShares->qty = $qty;
			$exchangeShares->exchange_id = $this->id;
			$exchangeShares->company_id = $company->id;
			$exchangeShares->owner_id = $owner->id;
			$exchangeShares->save();

			$comparator = "<=";
			$dir = "ASC";
			$reverse = "buy";
		}

		if ($action === "buy") {
			$comparator = ">=";
			$dir = "DESC";
			$reverse = "sell";
		} 
		// Look on the exchange for any current buy orders for this exchange
		$existingOrders = ExchangeOrder::where('company_id',$company->id)->where('exchange_id',$this->id)->where('action',$reverse)->where('price',$comparator,$price)->orderBy('price',$dir)->get();

        $ttl = 0;
        $qtyLeft = (float)$qty;

        $brought = array();
        // Process each existing order
        foreach($existingOrders as $order) {
            if (!isset($brought[$order->price]))
                $brought[$order->price] = 0;

            if ($order->qty >= $qtyLeft) {
                Game::log('orders',$owner->id." completed order for ".($order->qty - $qtyLeft)."/".$order->qty." shares for ".$order->price,array('owner_id'=>$owner->id,'exchange_id'=>$exchange->id,'company_id'=>$company->id));
                $brought[$order->price] +=  $qtyLeft ;
                $order->qty = $order->qty - $qtyLeft;
                $qtyBrought = $qtyLeft;
                $qtyLeft = 0;
                $order->save();

                // if sell, create new shares for the order owner and give money to seller
                // if buy, give shares to seller and give money to order owner
            } else {
                Game::log('orders',$owner->id." completed order for ".($order->qty)."/".$order->qty." shares for ".$order->price,array('owner_id'=>$owner->id,'exchange_id'=>$exchange->id,'company_id'=>$company->id));
                $qtyLeft = $qtyLeft - $order->qty;
                $qtyBrought = $order->qty;
                $brought[$order->price] += $order->qty;
                $order->delete();

            }
                if ($action == "sell") {
                	// Create shares for the order and give them to the owner of the order
                	$ownerAccount = $owner->bankAccount();
                	$shares = Share::where('owner_id',$order->owner_id)->where('company_id',$company->id)->whereNull('exchange_id')->first();
                	if (!$shares) {
                		$shares = new Share;
                		$shares->qty = 0;
                		$shares->owner_id = $order->owner_id;
                		$shares->company_id = $company->id;
                	}
                	$shares->qty = $shares->qty + $qtyBrought;
                	$shares->save();
               		Game::log('shares',$order->owner_id." received ".($qtyBrought)." shares",array('owner_id'=>$owner->id,'exchange_id'=>$exchange->id,'company_id'=>$company->id));
                	// Get the owner bank account and give him money
                	$ownerAccount->balance = $ownerAccount->balance + ($order->price*$qtyBrought);
                	$ownerAccount->save();
	                Game::log('accounts',$owner->id." received ".($order->price*$qtyBrought),array('owner_id'=>$owner->id,'exchange_id'=>$exchange->id,'company_id'=>$company->id,'account_id'=>$ownerAccount->id));
                } else {
                	$ownerAccount = $order->owner()->account();

                	$shares = Share::where('owner_id',$owner->id)->where('company_id',$company->id)->whereNull('exchange_id')->first();
                }

            if ($order->qty <= 0) {
                $order->delete();
            }

            if ($qtyLeft <= 0)
                break;
        }
        $data['brought'] = $brought;
        $data['placed'] = array();
        if($qtyLeft)
            $data['placed'] = array('qty'=>$qtyLeft,'price'=>$price);

        // Create a new order for any shares left to buy
        if ($qtyLeft) {
			$order = new ExchangeOrder;
            $order->action = $action;
			$order->price = $price;
			$order->share_id = $exchangeShares->id;
			$order->exchange_id = $this->id;
            $order->company_id = $company->id;
            $order->owner_id = $owner->id;
            $order->qty = $qty;
			$order->save();
        }
        return $data;
    }

    public function getSellOrders($companyID = 0, $grouped = true) {
    	$orders = ExchangeOrder::where('action','sell');
    	if ($companyID)
	    	$orders = $orders->where('company_id',$companyID);
	    $orders = $orders->orderBy('price','ASC')->get();
	    if ($grouped) {
	    	$new = array();
	    	foreach($orders as $order) {
	    		if (!isset($new[$order->price]))
	    			$new[$order->price] = $order;
	    		else
	    			$new[$order->price]->qty += $order->qty;
	    	}
	    	$orders = collect(array_values($new));
	    }
	    return $orders;
    }

    public function getBuyOrders($companyID = 0, $grouped = true) {
    	$orders = ExchangeOrder::where('action','buy');
    	if ($companyID)
	    	$orders = $orders->where('company_id',$companyID);
		$orders = $orders->orderBy('price','DESC')->get();
	    if ($grouped) {
	    	$new = array();
	    	foreach($orders as $order) {
	    		if (!isset($new[$order->price]))
	    			$new[$order->price] = $order;
	    		else
	    			$new[$order->price]->qty += $order->qty;
	    	}
	    	$orders = collect(array_values($new));
	    }
	    return $orders;
    }
}
