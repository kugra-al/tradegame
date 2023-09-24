<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Share;

class ShareController extends Controller
{
    public function index() {
    	$shares = Share::paginate();

    	return view('shares.index',array('shares'=>$shares));
    }
}
