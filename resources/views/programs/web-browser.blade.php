<form id="browserContainer" onsubmit="desktop.execute('web-browser',{'view':'web-browser','action':'save','form':'browserContainer','program':'web-browser','window':$(this).parents('.card-window').attr('id')});return false;" class="col-sm-12">
	@csrf
	<label for="url" class="col-sm-4 left">Url</label>
	<input id="url" name="url" class="form-control col-sm-7 left" type="text" @if(isset($url)) value="{{ $url }}"@endif>
	<button type="submit" class="btn btn-success col-sm-1 right">Go</button>
</form>
@if(isset($contents))
	<div id="pageContents" style="height: 500px;width:100%"></div>
	<div id="source" style="display:none;">
	@foreach(explode("\n",$contents) as $c)
		{!! $c !!}
		
	@endforeach
	</div>
	<script>
			var iframe = document.createElement('iframe');
			$(iframe).css({'width':'100%','height':'100%'});
			$('#pageContents').html(iframe);
			iframe.contentWindow.document.open();
			iframe.contentWindow.document.write($('#source').html());
			iframe.contentWindow.document.close();
		</script>

@endif