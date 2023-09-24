<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Company;
use App\BankAccount;
use App\Game;

use DB;

class Owner extends Model
{
	protected $fillable = ['name','user_id'];
	
    public function shares() {
    	return $this->hasMany('App\Share');
    }

    public function company() {
    	return Company::where('owner_id',$this->id)->first();
    }

    public function bankAccount() {
    	$account = BankAccount::where('owner_id',$this->id)->first();
    	if (!$account) {
    		$account = new BankAccount;
    		$account->owner->id = $this->id;
    		$account->max_loan = 0;
    		$account->loan = 0;
    		$account->balance = 0;
    		$account->save();
    	}
    	return $account;
    }

    public static function createOwner($user,$characterName,$companyName) {

    	// Create owner and company
		$ownerID = DB::table('owners')->insertGetId([
	    	'user_id' => $user->id,
	    	'name' => $characterName,
	    	'created_at' => \Carbon\Carbon::now(),
	    	'updated_at'  => \Carbon\Carbon::now(),
	    ]);

    	$companyID = DB::table('companies')->insertGetId([
	    	'name' => $companyName,
	    	'max_shares' => 0,
	    	'owner_id'   => $ownerID,
	    	'created_at' => \Carbon\Carbon::now(),
	    	'updated_at'  => \Carbon\Carbon::now(),
	    ]);

    	// Create shares for company
    	$company = Company::find($companyID);
    	$owner = Owner::find($ownerID);
	    $company->createShares(100,$owner->id,false);

	    // Create bank account for company and for owner
	    $account = new BankAccount;
	    $account->balance = 10000;
	    $account->max_loan = 100000;
	    $account->loan = 0;
	    $account->owner_id = $owner->id;
	    $account->save();
	    Game::log('accounts','Created bank account: '.$account->id.' for owner: '.$owner->id.' with 10000',array('owner_id'=>$owner->id,'account_id'=>$account->id));

	    $account = new BankAccount;
	    $account->balance = 10000;
	    $account->max_loan = 100000;
	    $account->loan = 0;
	    $account->company_id = $company->id;
	    $account->save();
	    Game::log('accounts','Created bank account: '.$account->id.' for company: '.$company->id.' with 10000',array('company_id'=>$company->id,'account_id'=>$account->id));

	    // Testing
	    $buyCompany = 2;
	    if ($user->id != 1)
	    	$buyCompany = 1;

	    for($x = 0; $x < 5; $x++) {
		    $exchangeOrder = DB::table('exchanges_orders')->insert([
		    	'price'=>20+$x,
		    	'qty'=>1,
		    	'company_id'=>$buyCompany,
		    	'exchange_id'=>1,
		    	'action'=>'buy',
		    	'owner_id'	=> $owner->id,
		    	'created_at' => \Carbon\Carbon::now(),
		    	'updated_at' => \Carbon\Carbon::now()
		    ]);
			$exchangeOrder = DB::table('exchanges_orders')->insert([
		    	'price'=>10-$x,
		    	'qty'=>1,
		    	'company_id'=>$company->id,
		    	'exchange_id'=>1,
		    	'owner_id'=>$owner->id,
		    	'action'=>'sell',
		    	'created_at' => \Carbon\Carbon::now(),
		    	'updated_at' => \Carbon\Carbon::now()
		    ]);
		}
		return $owner;
    }
}
