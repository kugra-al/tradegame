File Explorer<br/>
<a href="#" onclick="desktop.execute('file-explorer',{'program':'file-explorer','window':$(this).parents('.card-window').attr('id')},[{'name':'test','type':'dir','action':'create'}]);return false">New Dir</a>
<hr>
@if(isset($files))
	@php($path = '')

	@if(isset($files['path']) && strlen($files['path']))
		@php($path = $files['path'])
	@endif
	Path: {{ $path }}
	<table class="table task-table" id="file-explorer">
		<thead>
			<th>Name</th>
			<th>Size</th>
			<th>Modified</th>
		</thead>
		<tbody>
	@foreach($files as $type=>$fileCol)
		@if(is_array($fileCol))
			@foreach($fileCol as $file)
				@php($icon = 'far fa-file-alt')
				@if(isset($file['icon']))
					@php($icon = $file['icon'])
				@endif
				@if($type == 'dir')
					@php($icon='fa fa-folder')
					@php($onClick="desktop.execute('file-explorer',{'program':'file-explorer','window':$(this).parents('.card-window').attr('id')},[{'action':'list','path':'".$file['file']."'}]);return false")
				@else
					@php($onClick="desktop.execute('".$file['program']."',{'program':'".$file['program']."','action':'open'},[{'path':'".$path."','file':'".$file['file']."'}]);return false")
				@endif
				<tr>
					<td><a onclick="{{ $onClick }}" class="{{ $type }}" href="#" title="@if(isset($file['mime'])){{$file['mime']}}@endif"><i class="{{ $icon }}"></i> {{ $file['name'] }}</a></td>
					<td>{{ $file['size'] }}</td>
					<td>{{ $file['time'] }}</td>

				</tr>
			@endforeach
		@endif
	@endforeach
		</tbody>
	</table>
@endif