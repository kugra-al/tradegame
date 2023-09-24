@extends('layouts.admin')

@section('main')

                	<a href="/admin/users/create">New</a>
                	<table class="table">
						<thead>
							<tr>
								<th scope="col">ID</th>
								<th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Permissions</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th></th>
							</tr>
						</thead>
						<tbody>
							@foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <ul>
                                        @foreach($user->roles as $role)
                                            <li><a href="/admin/roles/{{ $role->id }}">{{ $role->name }}</a></li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @php($permissions = $user->getAllPermissions())
                                        @if($permissions->count())
                                            <ul>
                                            @foreach($permissions as $permission)
                                                <li><a href="/admin/permissions/{{ $permission->id }}">{{ $permission->name }}</a></li>
                                            @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->updated_at }}</td>
                                    <td>
                                        @if($user->hasRole('super-admin'))
                                            @role('super-admin')
                                                <div class="btn-group">
                                                    <a href="/admin/users/{{ $user->id }}/edit"><button class="btn btn-success">Edit</button></a>
                                                    <button class="btn btn-danger">Delete</button>
                                                </div>
                                            @endrole
                                        @else
                                            <div class="btn-group">
                                                <a href="/admin/users/{{ $user->id }}/edit"><button class="btn btn-success">Edit</button></a>
                                                <button class="btn btn-danger">Delete</button>
                                            </div>
                                        @endrole
                                    </td>
                                </tr>
                            @endforeach
						</tbody>
					</table>

@endsection