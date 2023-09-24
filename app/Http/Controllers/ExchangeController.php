<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exchange;
use App\Company;

class ExchangeController extends Controller
{
    public function index(Request $request) {
    	$exchanges = Exchange::paginate();
    	if ($request->get('c'))
    		$company = Company::find($request->get('c'));
    	else
    		$company = Company::first();

    	return view('exchanges.index',array('exchanges'=>$exchanges,'company'=>$company));
    }
}
