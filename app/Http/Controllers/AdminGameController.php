<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Str;
use App\Game;
use App\ExchangeOrder;
use App\Company;
use App\Owner;
use App\BankAccount;
use App\User;
use App\GameLog;

class AdminGameController extends Controller
{
    public function index () {
    	//$this->resetGame();
    	return view('admin.game.index');
    }

    public function postIndex (Request $request) {
    	if ($request->get('reset')) {
//    		Session::flash('success','ok');
    		$this->resetGame();
    		return redirect('/admin/game')->with('success','Game reset');
    	}
    	return redirect('/admin/game');
    }

    public function resetGame () {
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('companies')->truncate();
		DB::table('shares')->truncate();
		DB::table('owners')->truncate();
		DB::table('exchanges')->truncate();
		DB::table('exchanges_orders')->truncate();
		DB::table('accounts')->truncate();
		DB::table('game_log')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');  

	    $exchangeID = DB::table('exchanges')->insertGetId([
	    	'name' => "Main Exchange",
	    	'created_at' => \Carbon\Carbon::now(),
	    	'updated_at'  => \Carbon\Carbon::now(),
	    ]);

	    foreach(User::get() as $user) {
		    $characterName = Game::getRandomCharacterName();
		    $companyName = Game::getRandomCompanyName();
			$owner = Owner::createOwner($user,$characterName,$companyName);
		}
    }

    public function viewLog() {
    	$log = GameLog::paginate();
    	return view('admin.game.log',array('log'=>$log));
    }
}
