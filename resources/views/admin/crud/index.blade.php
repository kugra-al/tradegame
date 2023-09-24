@extends('layouts.admin')

@section('main')
@if(isset($controller))
	@yield('admin.'.$controller.'.top','')
@else
	@php($controller = '')
@endif
@if(isset($crud))
	<table class="table task-table">
		<thead>
			@php($keys = array_keys($crud->first()->toArray()))
			@foreach($keys as $header)
				<th>{{ $header }}</th>
			@endforeach
			<th></th>
		</thead>
		<tbody>
			@foreach($crud as $item)
				<tr>
				@foreach($keys as $header)
					<td>@if(isset($item->{$header})){{ $item->{$header} }}@else @endif</td>
				@endforeach
					<td>
						<div class="btn-group">
							<a class="btn btn-info" href="/admin/{{ $controller }}/{{ $item->id }}">View</a>
							<a class="btn btn-success" href="/admin/{{ $controller }}/{{ $item->id }}/edit">Edit</a>
							<a class="btn btn-danger">Delete</a>
						</div>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	No data to display
@endif
@endsection

