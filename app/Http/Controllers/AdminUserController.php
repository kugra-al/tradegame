<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = User::find(1);
        // $user->assignRole('admin');
        // $user->assignRole('super-admin');
        $users = User::get();
        $roles = Role::pluck('name','id');
        return view('admin.users.index',array('users'=>$users,'roles'=>$roles));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
            'password_confirm'=>'required_with:password|same:password'
        ]);
        $user = new User(['name'=>$request->get('name'),'email'=>$request->get('email'),'password'=>\Hash::make($request->get('password'))]);
        $user->save();
        return redirect('/admin/users')->with('success','Created user. Edit user now to assign roles');
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
    public function edit($id)
    {
        $user = User::find($id);
        if ($user->hasRole('super-admin') && !\Auth::user()->hasRole('super-admin'))
            return redirect('/admin/users')->with('error',"Only super-admin's can change roles of other super-admin's");
        
        $roles = Role::pluck('name','id');
        //dd($roles);
        return view('admin.users.edit',array('user'=>$user,'roles'=>$roles));
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
        $user = User::find($id);
        if ($user->hasRole('super-admin') && !\Auth::user()->hasRole('super-admin'))
            return redirect('/admin/users')->with('error',"Only super-admin's can change roles of other super-admin's");

        if ($request->get('name') && $user->name != $request->get('name'))
            $user->name = $request->get('name');
        // Don't change email for now because we use that for logins. Make a new account if you need a new one

        if ($request->get('password')) {
            $request->validate([
                'password'=>'required',
                'password_confirm'=>'required_with:password|same:password'
            ]);
            $user->password = \Hash::make($request->get('password'));
        }
        $roles = $request->get('roles');
        if ($roles) {
            $user->syncRoles([$roles]);
        } else {
            $user->syncRoles([]);
        }
        $user->save();

        return redirect('/admin/users')->with('success','Updated user');
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
