<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller
{
    public function index () {
    	$companies = Company::paginate();
    	
    	return view('companies.index',array('companies'=>$companies));
    }
}
