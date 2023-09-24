@extends('layouts.app')

@section('css')

.admin-left { width: 280px; float: left; border-right: 1px #ccc solid; padding: 20px;}
.main-content { padding-left: 280px; width: 100%; }
.nav>li>a{
	position: relative;
    display: block;
    padding: 10px 15px;
}
.nav>li{
	    position: relative;
    display: block;
}
.nav-stacked{display:block;}
.nav-stacked>li{float:none;}
main.py-4 { padding-top: 0px !important;}
.main-content {padding-top: 20px;}
@endsection

@section('content')
<div class="admin-left well sidebar-nav">

	<ul class="nav nav-pills nav-stacked">
		<li><h3>Admin</h3></li>
		<li><a href="/admin">Dashboard</a></li>
		<li><a href="/admin/game">Manage Game</a></li>
		<li><a href="/admin/game/log">View Game Log</a></li>
		<li><h5>Game Crud</h5></li>
		<li><a href="/admin/companies">Companies</a></li>
		<li><a href="/admin/shares">Shares</a></li>
		<li><a href="/admin/exchanges">Exchanges</a></li>
		<li><a href="/admin/owners">Owners</a></li>
		<li><a href="/admin/bankaccounts">Bank Accounts</a></li>
		<li><h5>System</h5></li>
		<li><a href="/admin/users">Users</a></li>
	</ul>
</div>
<div class="main-content">
	<div class="container">
		<div class="card">
			<div class="card-body">@yield('main')</div>
		</div>
	</div>
</div>
@endsection