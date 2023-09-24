@extends('programs.settings.container')

@section('settings-content')
<form id="backgroundContainer" onsubmit="desktop.execute('settings',{'view':'background','action':'save','form':'backgroundContainer'});return false;">
	@csrf
	<label for="background">Background Colour</label>
	<input id="background" name="background" input class="form-control" type="color" @if(isset($color)) value="{{ $color }}" @endif>
	<button type="submit" class="btn btn-success">Save</button>
</form>
@endsection