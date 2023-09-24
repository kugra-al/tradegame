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
		<th><button class="btn btn-danger" onclick="desktop.closeAllWindows();return false;">End All</button></th>
	</tfoot>
</table>
<script>
	var lastWins = {};
	function redrawTaskmanager() {
		var wins = desktop.windows;
		if (JSON.stringify(lastWins) === JSON.stringify(Object.keys(wins))) // If no change to window IDs, do nothing because probably the same
			return;
		var table = $('#running-tasks');
		$(table).find('tbody').html('');
alert('t');

		$.each(wins,function(i,v){
			var name = $(v).find('.card-header').text();
			var uptime = (new Date().getTime()-parseFloat(i));
			var uptime = 'test';
			var row = "<tr><td>"+i+"</td><td>"+name+"</td><td></td><td></td><td>"+uptime+"</td><td><button class='btn btn-danger' onclick='desktop.closeWindow($(\"#"+i+"\"));return false'>End</button></tr>";
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