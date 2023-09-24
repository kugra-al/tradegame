<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Share;
use App\ExchangeOrder;
use App\Company;
use App\Exchange;
use App\Game;
use Auth;

class AjaxController extends Controller
{
    public function postIndex(Request $request) {
    	$controller = $request->get('controller');
    	$action = $request->get('action');

    	$errors = array();
    	$success = array();
    	$owner = Auth::user()->owner();

    	$data = array('errors'=>array());

    	$view = 'modal.ajax.body';

    	switch($controller) {
    		case "shares"	:	
                                $qty = $request->get('qty');
                                $price = $request->get('price');
                                $exchangeID = $request->get('exchange_id');
                                $companyID = $request->get('company_id');
                                $exchange = Exchange::find($exchangeID);                                
                                $company = Company::find($companyID);

    							switch ($action) {
    								case 'sell'		:	
    									$view = 'shares.ajax.sell';
                                        //return array($company);
//return array('id'=>$companyID,'company'=>$company,'owner'=>$owner);

                                        $shares = Share::where('owner_id',$owner->id)->where('company_id',$company->id)->whereNull('exchange_id')->first();

                                        if (!$shares) {
                                            $data['errors'][] = 'No shares to sell';
                                            break;
                                        }
    									
    									if (!$shares->canOwnerSell($owner)) {
    										$data['errors'][] = 'no permission to sell';
    										break;
    									}
    									// This is just a form request
    									if (!$request->get('exchange_id') && !$request->get('qty'))
    										$data['shares'] = $shares;
    									else {


    										$data['shares'] = $shares;
    										if (!$qty) {
    											$data['errors'][] = 'Qty must be more than 0';
    											break;
    										}
    										if (!$price) {
    											$data['errors'][] = 'Price must be more than 0';
    											break;
    										}

    										$data['qty'] = $qty;
    										$data['exchange'] = $exchange;
    										$data['shares'] = $shares;
    										$data['company'] = $company;
                                            $data['owner'] = $owner;
                                            $data['price'] = $price;
    										$data['action'] = 'sell';


    										if (!$price) {
    											$data['errors'][] = 'no price';
    											break;
    										}

    										$data = $exchange->processTrade($action,$data);
    										$view = 'shares.ajax.confirmSale';
    									}

										break;

    								case 'buy'		:	
    									$view = 'shares.ajax.buy';
    									$companyID = $request->get('company_id');
    									if (!$companyID) {
    										$data['errors'][] = 'No company ID';
    										break;
    									}
    									$data['company'] = Company::find($companyID);


    									if ($request->get('exchange_id') && $request->get('qty')) {

    										$data['qty'] = $qty;
    										$data['exchange'] = $exchange;
    										$data['company'] = $company;
    										$data['action'] = 'buy';

    										$existingOrders = ExchangeOrder::where('company_id',$company->id)->where('exchange_id',$exchange->id)->where('action','sell')->where('price','<=',$price)->orderBy('price','ASC')->get();

    										$ttl = 0;
    										$qtyLeft = (float)$qty;

    										$brought = array();
                                            $qtyBrought = 0;
    										foreach($existingOrders as $order) {
    											if (!isset($brought[$order->price]))
    												$brought[$order->price] = 0;

    											if ($order->qty >= $qtyLeft) {
                                                    Game::log('shares',$owner->id." brought ".$qtyLeft." shares for ".$order->price,array('owner_id'=>$owner->id,'exchange_id'=>$exchange->id,'company_id'=>$company->id));
                                                    $qtyBrought += $qtyLeft;
	    											$brought[$order->price] +=  $qtyLeft ;
    												$order->qty = $order->qty - $qtyLeft;
    												$qtyLeft = 0;
	    											$order->save();

    											} else {
                                                    Game::log('shares',$owner->id." brought ".$order->qty." shares for ".$order->price,array('owner_id'=>$owner->id,'exchange_id'=>$exchange->id,'company_id'=>$company->id));
                                                    $qtyBrought += $order->qty;
    												$qtyLeft = $qtyLeft - $order->qty;
	    											$brought[$order->price] += $order->qty;
    												$order->delete();
    											}
    											if ($order->qty <= 0) {
    												$order->delete();
    											}

    											if ($qtyLeft <= 0)
    												break;
    										}

                                            // Add the shares to the user if there's any
                                            $shares = Share::where('owner_id',$owner->id)->where('company_id',$company->id)->whereNull('exchange_id')->first();

                                            if (!$shares) {
                                                $shares = new Share;
                                                $shares->company_id = $company->id;
                                                $shares->owner_id = $owner->id;
                                                $shares->qty = 0;
                                            }
                                            $shares->qty = $shares->qty+$qtyBrought;
                                            $shares->save();
                                

    										$data['brought'] = $brought;
    										$data['placed'] = array();
    										if($qtyLeft)
    											$data['placed'] = array('qty'=>$qtyLeft,'price'=>$price);

    										//return $existingOrders;
    										if ($qtyLeft) {
	    										$order = new ExchangeOrder;
	                                            $order->action = 'buy';
	    										$order->price = $price;
	    										$order->exchange_id = $exchange->id;
	                                            $order->company_id = $company->id;
	                                            $order->owner_id = $owner->id;
	                                            $order->qty = $qtyLeft;
	    										$order->save();
                                                Game::log('orders',$owner->id." placed  order for ".$order->qty." shares for ".$order->price,array('owner_id'=>$owner->id,'exchange_id'=>$exchange->id,'company_id'=>$company->id));
	    									}

    										$view = 'shares.ajax.confirmSale'; 
    									}

    									break;
    								default 		:	
    									if (!$action)
											$data['errors'][] = 'unknown action';
										else
											$data['errors'][] = 'unknown action '.$action;
										break;
    							}
    							
    							break;

    		case 'orders'		:	switch($action) {
    									case 'delete'	:	$orderID = $request->get('order_id');
    														$order = ExchangeOrder::find($orderID);
    														// check if auth
    														$order->delete();
    														$view = null;
    														break;
    								}	
    								break;
    		case 'exchange'		:

    								// $ACTION VIEW
									$companyID = $request->get('company_id');
									$company = Company::find($companyID);
									$exchange = Exchange::find(1);
									$data['company'] = $company;
									$data['exchange'] = $exchange;
									$view = 'exchanges.orders.company';
    								
    								break;
    		default 		:	if (!$controller)
	    							$data['errors'] = 'No controller found';
	    						else
	    							$data['errors'] = "Unknown controller: $controller";
	    						break;
    	}
    	$data['request'] = $request->all();
    	if (!sizeof($data['errors']))
    		unset($data['errors']);

    	if ($view)
			return view($view,$data)->render();
		return $data;
    }
}
