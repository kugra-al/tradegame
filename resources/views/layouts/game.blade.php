@php($hideMainNav=true)
@extends('layouts.app')

@section('css')
.card-window { border-radius: 0px; color: #000; border-color: #000; border: 1px solid #FFF; width: 800px; }
.draggable .card-header { cursor: move; font-family: monospace; }
.card-window .card-body { background-color: #FFF; padding: 0px; max-height: 100vh; }
.card-window .card-header { text-align: center; background-color: #010082; color: #FFF; font-weight: bold; border: 1px solid #000;}
.card-window { display: block; }
.card-window.parent { display: none; }

.card-window.inactive .window-content { background:#e6e6e6; }
.card-window.inactive .card-header { background: ##42426f; }

#mainWindow { background-image: url('/img/desktop/background/winxp.jpeg'); background-repeat: no-repeat; background-size: 100% 100%; }
#mainWindow { background: #b2b2c3;}
#desktop { height: 100vh; }
#desktop #mainWindow { height: 100%; }
#mainWindow .container:first-child { height: 100% }
#mainWindow .container:first-child { max-width: 90%;}
.card-window .window-content { border: 1px solid #000; font-family: monospace; background: #FFF; color: #000; width:100%; max-height: 70vh; overflow: auto; padding: 5px;border-top: 0px;}

.card-window .text-editor { min-height: 200px; }
.card-window .toolbar { background: #ccc; color: #000; font-family: monospace; border: 1px solid #000}
.card-window .toolbar nav { border: 0px;}
.card-window .toolbar .nav-item { padding: 0px; border-right: 1px solid #eee;}
.card-window .toolbar .nav-item a { font-decoration: none; color: #000; }
.card-window .statusbar { background: #ccc;
    border: 1px solid #000;
    padding-left: 5px; font-family: monospace; border-top: 0px;}

#file-explorer .dir i { color: #e8e800; }
#file-explorer .file i { color: #000; }

.dropup .dropdown-menu { margin-bottom: 13px; border-radius: 0px;}
.draggable .close-icon { cursor: pointer; padding: 9px; background: #ccc; color: #000; border: 1px solid #000}
#taskbar #openPrograms .nav-link { float: left; background: #40474e; margin: 0px 1px 0px 1px}
#taskbar #openPrograms .nav-link:active { font-weight: bold;}
#taskbar #openPrograms { border-left: 1px solid #444; }
#taskbar #openPrograms .nav-link:nth-last-child(10):first-child, #taskbar #openPrograms .nav-link:nth-last-child(10):first-child ~ #taskbar #openPrograms .nav-link { max-width: 50px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis}
main.py-4 { padding: 0px !important;}
#desktop {}
#taskbar #clock .time, #taskbar #clock .date { text-align: center;}
#taskbar .navbar-text { padding: 0px;}
@endsection

@section('content')

<div id="desktop">
<div class="main-content" id="mainWindow">
	<div class="container">
		
	</div>
</div>
<nav id="taskbar" class="navbar fixed-bottom navbar-dark bg-dark navbar-expand-lg">
	
		<ul class="navbar-nav mr-auto">
			<li class="nav-item dropup">
		        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		          Programs
		        </a>
		        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
		        	@if(isset($programs))
		        		@foreach($programs as $program)
		        			<a class="dropdown-item" href="#" data-program="{{ $program['slug'] }}" onClick="return desktop.runProgram(this)"><i class="{{ $program['icon'] }}"></i> {{ $program['name'] }}</a>
		        		@endforeach
		        	@endif
		          
		        </div>
		      </li>
	        	@if(isset($programs))
	        		@foreach($programs as $program)
	        			<li class="nav-item">
		        			<a class="nav-link" href="#" onClick="return desktop.runProgram(this)" data-program="{{ $program['slug'] }}" title="{{ $program['name'] }}"><i class="{{ $program['icon'] }}"></i></a>
		        		</li>
	        		@endforeach
	        	@endif
		  		<li class="nav-item" id="openPrograms">
		  		</li>
		</ul>
		<span class="navbar-text">
		  <a a href="#" onclick="desktop.execute('settings',{'view':'clock','action':'view','program':'settings'},);return false" id="clock"><div class="time">14:00</div><div class="date"></div></a>
		</span>
	</div>
</nav>
</div>
<div class="card draggable card-window parent" id="draggableWindow">
	<span class="right clickable close-icon" onClick="return desktop.closeWindow(this)"><i class="fa fa-times"></i></span>
	<div class="card-header">Draggable</div>
	<div class="card-body">
		<div class="toolbar"></div>
		<div class="window-content"></div>
		<div class="statusbar"></div>
	</div>
</div>
<ul id="contextMenu" class="dropdown-menu" role="menu">
    <li><a tabindex="-1" href="#">Action</a></li>
    <li><a tabindex="-1" href="#">Another action</a></li>
    <li><a tabindex="-1" href="#">Something else here</a></li>
    <li class="divider"></li>
    <li><a href="#" onclick="desktop.execute('settings',{'view':'background','action':'view','program':'settings'},);return false">Background</a></li>
</ul>
<ul id="taskbarContextMenu" class="dropdown-menu" role="menu">
    <li><a tabindex="-1" href="#">ttAction</a></li>
    <li><a tabindex="-1" href="#">Another action</a></li>
    <li><a tabindex="-1" href="#">Something else here</a></li>

    
</ul>
@endsection


@section('javascript')
var desktop = {};
desktop.windows = {};
desktop.programs = {!! json_encode($programs) !!};
desktop.settings = {!! json_encode($settings) !!};
desktop.createWindow = function(data) {
	var win = $('#draggableWindow').clone();
	$(win).removeAttr('id');
	$(win).removeClass('parent');
	//$(win).addClass('active');

	var id = new Date().getTime();
	if(data['body'])
		$(win).find('.card-body .window-content').html(data['body']);
	if(data['title']) {
		$(win).attr('data-title',data['title']);
		$(win).find('.card-header').text(data['title']);
	}
	if(data['program'])
		$(win).attr('data-program',data['program']);
	if(data['toolbar'] && data['toolbar'].length) {
		$(win).find('.card-body .toolbar').show();
		$(win).find('.card-body .toolbar').html(data['toolbar']);
	} else {
		$(win).find('.card-body .statusbar').hide();
	}
	if(data['statusbar'] && data['statusbar'].length) {
		$(win).find('.card-body .statusbar').show();
		$(win).find('.card-body .statusbar').html(data['statusbar']);
	} else {
		$(win).find('.card-body .statusbar').hide();
	}
	$(win).attr('id',id);
	desktop.windows[id] = win;
	$(win).resizable().draggable({'handle':'.card-header','containment': 'parent'});

	var top = desktop.getTopWindow();
	var z = 1;
	if (top) {
		z = parseInt($(top).css('z-index'))+1;
	} else {
		z = 1;
	}
	var topPos = 0;
	var leftPos = 0;
	if (top) {
		topPos = $(top).css('top').replace("px","");
		leftPos = $(top).css('left').replace("px","");
	}
	
	$(win).css({'position':'absolute','top':parseInt(topPos)+10,'left':parseInt(leftPos)+10});
	$(win).css('z-index',z);
	$(win).click(function(){
		desktop.focusWindow($(win).attr('id'));
	});
	//$(win).resizable();
	//$(win).css({'top':'0px','left':'0px'});
	$('#mainWindow .container:first-child').append(win);
	desktop.focusWindow($(win).attr('id'));
	desktop.drawTaskbar();

	//$(win).fadeIn('slow');

}
desktop.closeWindow = function(window) {
	if (!$(window).hasClass('draggable')) {
		window = $(window).parents('.card-window.draggable');
	}
	if(!window) {
		desktop.addError('trying to close something that is not a window');
		return;
	}
	var id = $(window).attr('id');
	if (desktop.windows[id])
		delete desktop.windows[id];
	$(window).fadeOut('slow').remove();
	desktop.drawTaskbar();

}
desktop.closeAllWindows = function(except = false) {
	var wins = desktop.windows;
	console.log(except);
	$.each(Object.keys(wins),function(i,v){
		if (except && $(except).attr('id') != v)
			desktop.closeWindow($('#'+v));
	});
}
desktop.getTopWindow = function() {
	var z = 0;
	var win;
	$.each(desktop.windows,function(i,v){
		if ($(v).css('z-index')>z && parseInt($(v).css('z-index'))) {
			z = $(v).css('z-index');
			win = v;
		}
	});
	return win;
}
desktop.focusWindow = function(windowID) {
	var z = 1;
	var top = desktop.getTopWindow();
	if (top)
		z = $(top).css('z-index');
	$.each($('.card-window'),function(i,v){
		$(v).removeClass('active');
		$(v).addClass('inactive');
	});
	$('#'+windowID).removeClass('inactive');
	$("#"+windowID).css('z-index',parseInt(z)+1);
	$("#"+windowID).addClass('active');
	desktop.drawTaskbar(); // update active window on taskbar
}
desktop.runProgram = function(program) {
	if(typeof program != "string")
		program = $(program).attr('data-program');
	if (!program) {
		desktop.addError('no load program');
		return false;
	}
	if(desktop.programs[program]) {
		if (desktop.programs[program]['max_instances'] && desktop.programs[program]['max_instances'] == 1) {
			desktop.getWindowOrCreate(program);
			console.log(program);
			return false;
		}
	}
	desktop.execute('run',{'program':program});
	return false;
	alert(program);
	console.log(program);
}
desktop.getWindowOrCreate = function(program,args,forms={}) {
	var activeWindow = null;
	$.each(desktop.windows,function(i,v){
		if($(v).attr('data-program') == program)
			activeWindow = v;
	});	

	if(!args || !Object.keys(args).length)
		args = {};
	args['program'] = program;
	if(!activeWindow) {
		desktop.execute('run',args,forms);

	//alert('no find win');
		return;
	}
	desktop.focusWindow($(activeWindow).attr('id'));
	return activeWindow;
},
desktop.execute = function(cmd,args,forms={}) {
	var data = {'cmd':cmd,'args':args};



	// If we have a form in the args, get the values from that form
	if(args['form']) {
		var form = $("#"+args['form']);
		console.log($(form).find(':input'));
		if(!data['forms'])
			data['forms'] = [];
		var t = {};
		$.each($(form).find(':input'),function(i,v){
			t[$(v).attr('name')] = $(v).val();
		});
		data['forms'].push(t);
		console.log(data);
	}
	// If we passed forms, add them.
	if(forms) {
		$.each(forms,function(i,v){
			if(!data['forms'])
				data['forms'] = [];
			data['forms'].push(v);
		});
	}
	console.log('sending')
	console.log(data);
	var jqxhr = $.ajax({
		url: "/desktop",
		method: 'POST',
		data: data, 
	})
	  .done(function(data) {
	  	switch(cmd) {
	  		case 'run'	:	if (data['html']) {
	  							var title = 'program';
	  							var program = 'program';
	  							if(args['program']) {
	  								if(desktop.programs[args['program']])
		  								title = desktop.programs[args['program']]['name'];
		  							program = args['program'];
	  							}
	  							var view = '';
	  							if (args['view'])
	  								view = args['view'];
	  							desktop.createWindow({'body':data['html'],'title':title,'program':program,'toolbar':data['toolbar'],'statusbar':data['statusbar']});
	  						}
	  						break;
	  		case 'settings'	: 
	  							var action = args['action'];
	  							switch(action) {
	  								case 'view' :
							  							if(data['html']) {
								  							var window = desktop.getWindowOrCreate('settings',args);
								  							$(window).find('.card-body .window-content').html(data['html']);
								  							if (data['toolbar'] && data['toolbar'].length) {
									  							$(window).find('.card-body .toolbar').html(data['toolbar']);
																$(window).find('.card-body .toolbar').show();
															} else {
																$(window).find('.card-body .toolbar').hide();
															}
															if (data['statusbar'] && data['statusbar'].length) {
																$(window).find('.card-body .statusbar').show();
									  							$(window).find('.card-body .statusbar').html(data['statusbar']);
															} else {
																$(window).find('.card-body .statusbar').hide();
															}
								  						}
								  						break;
							  		case 'save' : 
				  			  						if(data['settings']) {
							  							desktop.settings = data['settings'];
							  							desktop.redrawDesktop();
							  						}
							  						break;
							  		default : desktop.addError('unknown');
							  				break;	
		  						}


	  						break;

	  		default 	:	if(data['html']) {
	  							var activeWindow = null;
	  							if(args['window']) {
	  								activeWindow = $('.card-window#'+args['window']);
	  								if ($(activeWindow).attr('id')) {
	  									$(activeWindow).find('.card-body .window-content').html(data['html']);
			  							if (data['toolbar'] && data['toolbar'].length) {
				  							$(activeWindow).find('.card-body .toolbar').html(data['toolbar']);
											$(activeWindow).find('.card-body .toolbar').show();
										} else {
											$(activeWindow).find('.card-body .toolbar').hide();
										}
										if (data['statusbar'] && data['statusbar'].length) {
											$(activeWindow).find('.card-body .statusbar').show();
				  							$(activeWindow).find('.card-body .statusbar').html(data['statusbar']);
										} else {
											$(activeWindow).find('.card-body .statusbar').hide();
										}
	  								} else {
	  									alert('error finding current window');
	  								}
	  							} else {
	  								desktop.createWindow({'body':data['html'],'title':'program','program':cmd,'toolbar':data['toolbar'],'statusbar':data['statusbar']});
//	  								openWindow = desktop.execute('run',args,forms);
	  								//$(openWindow).find('.card-body .window-content').html(data['html']);
	  							}
	  						}
	  						console.log('unknown cmd '+cmd);
	  						break;
	  	}
	  	if(data['errors'].length) {
	  		$.each(data['errors'],function(i,v){
	  			desktop.addError('execute error: '+v);
		  	});
	  	}

		  console.log(data);
	  	
	    
	  })
	  .fail(function() {
	    alert( "error" );
	  })
	  .always(function() {
	  	console.log('first complete');
	    //alert( "complete" );
	  });
	 
	// Perform other work here ...
	 
	// Set another completion function for the request above
	jqxhr.always(function() {
	  console.log('second complete');
	});
}

desktop.getCurrentTime = function () {
	var d = new Date();
	return d.getHours()+":"+d.getMinutes();
}
desktop.getCurrentDate = function() {
	var d = new Date();
	var m = d.getMonth()+1;
	if (m < 10)
		m = "0"+m;
	var y = d.getFullYear();
	var d = d.getDay();
	if (d < 10)
		d = "0"+d;
	return y+"-"+m+"-"+d;
}
desktop.drawTaskbar = function() {
	var itemHtml = '';

	var top = desktop.getTopWindow();
	top = $(top).attr('id');
	$.each(desktop.windows,function(i,v){
		var title = $(v).attr('data-title');
		var icon = 'fa fa-file-alt';
		if (desktop.programs[title])
			icon = desktop.programs[title]['icon'];
		var navClass = 'nav-link';
		if (top == $(v).attr('id'))
			navClass += ' active';
		itemHtml += "<a href='#' onclick='desktop.focusWindow(\""+i+"\");return false' class='"+navClass+"'><i class='"+icon+"'></i> "+title+"</a>";
	});
	$('#taskbar #openPrograms').html(itemHtml);
}
desktop.redrawDesktop = function() {
	$.each(desktop.settings,function(i,v){
		if(i == 'desktop') {
			if (v['css']) {
				console.log(v['css']);
				$('#mainWindow').css(v['css']);
			}
		}
	});
	desktop.drawTaskbar;
}
desktop.rightClickMenu = function() {
	
}
desktop.addError = function(err) {
	alert(err);
}
@endsection

@section('on-load')
//$('.draggable').draggable({'handle':'.card-header'});

$('#desktop #mainWindow').on("contextmenu",  function(e) {
	var contextMenu = $('#contextMenu');
   $(contextMenu).css({
      display: "block",
      left: e.pageX,
      top: e.pageY
   });
   $('#desktop #mainWindow').on('click',function(e){
   		$(contextMenu).hide();
	});
   return false;
});
$("#desktop #taskbar").on("contextmenu",function(e) {
   return false;
});
$('#taskbar #clock .time').text(desktop.getCurrentTime());
$('#taskbar #clock .date').text(desktop.getCurrentDate());
var timeBeat = setInterval(function(){
	var t = desktop.getCurrentTime();
	var d = desktop.getCurrentDate();
	var clock = $('#taskbar #clock .time');
	$(clock).text(t);
	var date = $('#taskbar #clock .date');
	$(date).text(d);
},60000);
desktop.redrawDesktop();
@endsection