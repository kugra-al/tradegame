<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exchange;

class AdminExchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $exchanges = Exchange::paginate();
        return view('admin.crud.index',array('crud'=>$exchanges,'controller'=>'exchanges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $model = new Exchange;
        $fillables = $model->getFillable();

        return view('admin.crud.create',array('controller'=>'exchanges','fillables'=>$fillables));      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $model = Exchange::find($id);
        $fillables = $model->getFillable();

        return view('admin.crud.edit',array('controller'=>'exchanges','fillables'=>$fillables,'model'=>$model));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
