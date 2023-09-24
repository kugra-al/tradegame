<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Owner;

class ExchangeOrder extends Model
{
    protected $table = 'exchanges_orders';

    public function owner() {
    	return Owner::where('owner_id',$this->id)->first();
    }
}
