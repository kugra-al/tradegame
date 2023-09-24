Task manager
<div id="last-task-update"></div>
<table class="table task-table" id="running-tasks">
	<thead>
		<th>ID</th>
		<th>Name</th>
		<th>CPU</th>
		<th>RAM</th>
		<th>Uptime</th>
		<th></th>
	</thead>
	<tbody>
	</tbody>
	<tfoot>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th><button class="btn btn-danger" onclick="desktop.closeAllWindows($(this).parents('.card-window'));redrawTaskmanager();return false;">End All</button></th>
	</tfoot>
</table>
<script>
	var lastWins = {};
	function redrawTaskmanager() {
		var wins = desktop.windows;
		var table = $('#running-tasks');
		if (JSON.stringify(lastWins) === JSON.stringify(Object.keys(wins))) {
			// If no change to window IDs, only update statuses instead of a full redraw
			$.each($(table).find('tbody tr td.uptime'),function(i,v){
				var start = $(v).attr('data-starttime');
				var uptime = ((new Date().getTime()-parseFloat(start))/1000).toFixed(2);
				$(v).text(uptime);
			});
			return;
		}

		$(table).find('tbody').html('');


		$.each(wins,function(i,v){
			var name = $(v).find('.card-header').text();
			var uptime = Math.round((new Date().getTime()-parseFloat(i))/60,2);

			var row = "<tr><td>"+i+"</td><td>"+name+"</td><td></td><td></td><td data-starttime='"+i+"' class='uptime'>"+uptime+"</td><td><button class='btn btn-danger' onclick='desktop.closeWindow($(\"#"+i+"\"));return false'>End</button></tr>";
			$(table).find('tbody').append(row);
		});
		$('#last-task-update').text(new Date());
		lastWins = Object.keys(wins);
	}
	$(document).ready(function(){
		redrawTaskmanager();
		setInterval(function(){redrawTaskmanager()},1000);
	});
</script>