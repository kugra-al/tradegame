@extends('programs.settings.container')

@section('settings-content')
system info<br/>
@if(isset($stats))
	<table class="table task-table">
		<thead>
			<th>Name</th>
			<th>Value</th>
		</thead>
		<tbody>
	@foreach($stats as $label=>$stat)
		<tr>
			<td>{{ $label }}</td>
			<td>{{ $stat }}</td>
		</tr>
	@endforeach
		</tbody>
	</table>
@endif
@endsection