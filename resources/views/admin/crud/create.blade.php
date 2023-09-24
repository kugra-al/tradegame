@extends('layouts.admin')

@section('main')
@if(isset($controller))
	@yield('admin.'.$controller.'.top','')
@endif
<form class="crudForm form" method="POST" action="{{ route($controller.'.store') }}">
	{{ @csrf_field() }}
	@if(isset($fillables) && sizeof($fillables))
		@foreach($fillables as $fillable)
			<div class="form-group row">
				<label class="col-sm-2 col-form-label" for="{{ $fillable }}">{{ $fillable }}</label>
				<div class="col-sm-10"> 
					<input type="text" class="form-control" name="{{ $fillable }}" id="{{ $fillable }}">
				</div>
			</div>
		@endforeach
		<div class="btn-group">
			<a href="{{ route($controller.'.index') }}" class="btn btn-default">Cancel</a>
			<button class="btn btn-success" type="submit">Create</button>
		</div>
	@else
		No fillables found
	@endif
</form>
@endsection

