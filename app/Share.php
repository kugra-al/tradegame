<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EchangeOrder;

class Share extends Model
{
    public function company() {
    	return Company::where('id',$this->company_id)->first();
    }

    public function hasSharesAvaliable() {
    	if ($this->exchange_id)
    		return false;
    	return $this->qty > 0;
    }

    public function owner() {
    	return $this->belongsTo('App\Owner');
    }

    public function exchange() {
    	return $this->belongsTo('App\Exchange');
    }

    public function canOwnerSell($owner) {
    	if (!$owner)
    		return false;
    	return $owner->id = $this->owner->id;
    }

    public function order() {
    	return ExchangeOrder::where('share_id',$this->id)->first();
    }
}
