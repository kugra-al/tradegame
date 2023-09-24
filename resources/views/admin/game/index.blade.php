@extends('layouts.admin')

@section('main')
    <div>
        <a href="/admin/game/log">Log</a>
    </div>
	<form action="/admin/game" method="POST">
		{{ @csrf_field() }}
		<input type="hidden" name="reset" value="true">
		<button class="btn btn-danger" type="submit">Reset Game</button>
	</form>
@endsection
