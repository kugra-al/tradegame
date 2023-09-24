@extends('layouts.admin')

@section('main')

                	
                	
                	<form method="post" action="/admin/users">
			          <div class="form-group">
			              @csrf
			              <label for="name">Name:</label>
			              <input type="text" class="form-control" name="name"/>
			          </div>
                      <div class="form-group">
                          <label for="email">Email:</label>
                          <input type="text" class="form-control" name="email"/>
                      </div>
                      <div class="form-group">
                          <label for="password">Password:</label>
                          <input type="password" class="form-control" name="password"/>
                      </div>
                      <div class="form-group">
                          <label for="password-confirm">Password Confirm:</label>
                          <input type="password" class="form-control" name="password_confirm"/>
                      </div>
			          <button type="submit" class="btn btn-primary">Create</button>
			      </form>
                   

@endsection