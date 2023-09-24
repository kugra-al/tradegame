<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Company;

class AdminCompanyController extends Controller
{
    public function index() {
    	$companies = Company::paginate();
    	return view('admin.crud.index',array('crud'=>$companies,'controller'=>'companies'));
    }

    public function edit($id) {
    	$model = Company::find($id);
    	$fillables = $model->getFillable();

    	return view('admin.crud.edit',array('controller'=>'companies','fillables'=>$fillables,'model'=>$model));
    }

    public function create() {
		$model = new Company;
    	$fillables = $model->getFillable();

    	return view('admin.crud.create',array('controller'=>'companies','fillables'=>$fillables));    	
    }

    public function show($id) {
        $model = Company::find($id);
        $fillables = $model->getFillable();

        return view('admin.crud.view',array('controller'=>'companies','fillables'=>$fillables,'model'=>$model));  
    }

    public function store(Request $request) {
        dd('no');
    }
}
