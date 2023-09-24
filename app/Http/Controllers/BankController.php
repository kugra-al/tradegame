<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BankAccount;
use Auth;
use App\GameLog;

class BankController extends Controller
{
    public function index () {
    	$owner = Auth::user()->owner();
    	$ownerAccount = BankAccount::where('owner_id',$owner->id)->first();
    	$company = $owner->company();
    	$companyAccount = BankAccount::where('company_id',$company->id)->first();

    	$logs = GameLog::where('account_id',$ownerAccount->id)->orWhere('account_id',$companyAccount->id)->get();

    	return view('banks.index',array('owner'=>$owner,'company'=>$company,'ownerAccount'=>$ownerAccount,'companyAccount'=>$companyAccount,'logs'=>$logs));
    }
}
