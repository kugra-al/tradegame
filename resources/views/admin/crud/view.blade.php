@extends('layouts.admin')

@section('main')
@if(isset($controller))
	@yield('admin.'.$controller.'.top','')
@endif
<h4>Viewing {{ $controller }} @if(isset($model)){{ $model->id }}@endif</h4>
	@if(isset($fillables) && sizeof($fillables))
		@foreach($fillables as $fillable)
			<div class="row">
				<label class="col-sm-2 col-form-label" for="{{ $fillable }}">{{ $fillable }}</label>
				<div class="col-sm-10"> 
					<pre class="code">@if(isset($model)){{ $model->{$fillable} }}@endif</pre>
				</div>
			</div>
		@endforeach
	@else
		No fillables found
	@endif
</form>
@endsection

