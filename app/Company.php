<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Share;
use App\Owner;
use App\Company;

class Company extends Model
{
	protected $fillable = ['name','owner_id','max_shares'];

    public function hasSharesAvaliable() {
    	$maxShares = $this->max_shares;
    	$shares = Share::where('company_id',$this->id)->select('qty')->get();
    	foreach($shares as $share) {
    		$maxShares = $maxShares-$share->qty;
    	}
    	return $maxShares > 0;
    }

    public function shares() {
    	return Share::where('company_id',$this->id)->get();
    }

    public function owner() {
    	if ($this->owner_id)
    		return Owner::find($this->owner_id);
    	return null;
    }

    public static function companiesWithShares() {
    	$companies = array();
    	foreach(Company::pluck('id') as $company) {
    		if (!isset($companies[$company]))
    			$companies[$company] = array();
    		$companies[$company][] = Share::where('company_id',$company)->get();
    	}
    	return collect($companies);
    }

    public function createShares($qty, $ownerID = null, $exchangeID = null) {
    	$shares = new Share;
    	$shares->qty = $qty;
    	$shares->company_id = $this->id;
    	if ($ownerID) 
    		$shares->owner_id = $ownerID;
    	if ($exchangeID)
    		$shares->exchange_id = $exchangeID;
    	$shares->save();

    	$this->max_shares = $this->max_shares + $qty;
    	$this->save();


    	
    	return $shares;
    }


}
