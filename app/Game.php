<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\GameLog;

class Game extends Model
{
    public static function getRandomCharacterName() {
    	$fNames = array('jim','james','mandy','paul','william','mitch','sandy','peter','percy','manuel');
    	$lNames = array('anderson','wilson','jones','smith','andrews','patterson','smithers','snow');

    	$fRand = array_rand($fNames);
    	$lRand = array_rand($lNames);

    	return ucfirst($fNames[$fRand])." ".ucfirst($lNames[$lRand]);
    }

    public static function getRandomCompanyName() {
    	$fNames = array('blaze','sharp','lion','fire','dragon','cobra','supa','super');
    	$lNames = array('vision','industries','inc','tech');

    	$fRand = array_rand($fNames);
    	$lRand = array_rand($lNames);

    	return ucfirst($fNames[$fRand])." ".ucfirst($lNames[$lRand]);
    }

    public static function log ($action, $data, $attributes) {
        $log = new GameLog;
        $log->action = $action;
        $log->data = $data;
        if(isset($attributes['company_id']))
            $log->company_id = $attributes['company_id'];
        if(isset($attributes['owner_id']))
            $log->owner_id = $attributes['owner_id'];
        if (isset($attributes['exchange_id']))
            $log->exchange_id = $attributes['exchange_id'];
        if(isset($attributes['account_id']))
            $log->account_id = $attributes['account_id'];
        $log->save();
        return $log;
    }
}
