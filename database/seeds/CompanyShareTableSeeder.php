<?php

use Illuminate\Database\Seeder;
use App\Owner;
use App\Exchange;
use App\Company;

class CompanyShareTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		DB::table('companies')->truncate();
		DB::table('shares')->truncate();
		DB::table('owners')->truncate();
		DB::table('exchanges')->truncate();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');  

	    $ownerID = DB::table('owners')->insertGetId([
	    	'user_id' => DB::table('users')->first()->id,
	    	'name' => DB::table('users')->first()->name,
	    	'created_at' => \Carbon\Carbon::now(),
	    	'updated_at'  => \Carbon\Carbon::now(),
	    ]);
	    $exchangeID = DB::table('exchanges')->insertGetId([
	    	'name' => Str::random(10),
	    	'created_at' => \Carbon\Carbon::now(),
	    	'updated_at'  => \Carbon\Carbon::now(),
	    ]);

    	$companyID = DB::table('companies')->insertGetId([
	    	'name' => Str::random(10),
	    	'max_shares' => 0,
	    	'owner_id'   => $ownerID,
	    	'created_at' => \Carbon\Carbon::now(),
	    	'updated_at'  => \Carbon\Carbon::now(),
	    ]);
	    $company = Company::find($companyID);

	    for ($x = 0; $x < 5; $x++) {
	    	if ($x == 3)
	    		$ownerID = 0;
		    $company->createShares(100,$ownerID,$exchangeID);
		}
	
    }
}
