<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Owner;
use App\Company;
use App\BankAccount;

use Auth;

class OwnerController extends Controller
{
    public function index () {
    	$owners = Owner::paginate();
    	return view('owners.index',array('owners'=>$owners));
    }

    public function create (Request $request) {
        $owner = Auth::user()->owner();
        if ($owner) {
            return redirect('/owners');
        }
    	return view('owner.create');
    }

    public function store (Request $request) {
    	$request->validate([
	        'character-name' => 'required',
	        'company-name' => 'required',
	    ]);
    	$characterName = $request->get('character-name');
    	$companyName = $request->get('company-name');
    	$userID = Auth::user()->id;

	    $owner = new Owner([
	    	'name'=>$characterName,
	    	'user_id'=>$userID,
	    ]);
	    $owner->save();
	    $company = new Company([
	    	'name'=>$companyName,
	    	'owner_id'=>$owner->id,
	    	'max_shares'=>0,
	    ]);
	    $company->save();
	    $company->createShares(100,$owner->id,false);

	    $account = new BankAccount;
	    $account->balance = 10000;
	    $account->max_loan = 100000;
	    $account->loan = 0;
	    $account->owner_id = $owner->id;
	    $account->save();

	    $account = new BankAccount;
	    $account->balance = 10000;
	    $account->max_loan = 100000;
	    $account->loan = 0;
	    $account->owner_id = $owner->id;
	    $account->company_id = $company->id;
	    $account->save();
	    
    	return redirect('/owners/create');
    }
}
