@extends('layouts.admin')

@section('main')

                	
                	<form method="post" action="/admin/users/{{ $user->id }}">
			          <div class="form-group">
			              @csrf
                          @method('PATCH')
			              <label for="name">Name:</label>
			              <input type="text" class="form-control" name="name" @if($user->name) value="{{ $user->name }}" @endif/>
			          </div>
                      <div class="form-group">
                          <label for="email">Email:</label>
                          <input type="text" class="form-control" name="email" @if($user->email) value="{{ $user->email }}" @endif/>
                      </div>
                      <div class="form-group">
                          <label for="password">Change Password:</label>
                          <input type="password" class="form-control" name="password"/>
                      </div>
                      <div class="form-group">
                          <label for="password-confirm">Password Confirm:</label>
                          <input type="password" class="form-control" name="password_confirm"/>
                      </div>
                      <div class="form-group">
                            <label>Roles</label>
                      </div>
                      

                      @foreach($roles as $id=>$role)
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role }}" 
                          @if($user->hasRole($role))
                                checked
                          @endif
                           ><label class="form-check-label">
                            {{ $role }}
                          </label>
                        </div>
                      @endforeach
			          <button type="submit" class="btn btn-primary">Edit</button>
			      </form>
                   

@endsection