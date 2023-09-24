<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
	// Login

	Route::get('/shares','ShareController@index');
	Route::get('/companies','CompanyController@index');
	Route::get('/owners','OwnerController@index');
	Route::get('/exchanges','ExchangeController@index');
	Route::get('/banks','BankController@index');

	Route::get('/owners/create','OwnerController@create');
	Route::post('/owners/create','OwnerController@store');

	Route::post('/ajax','AjaxController@postIndex');

	Route::get('/desktop','DesktopController@index');
	Route::post('/desktop','DesktopController@postIndex');

	// Settings
	Route::get('/settings','UserSettingsController@index');
	Route::post('/settings','UserSettingsController@postIndex');

	// Some of this should be in super-admin
	Route::group(['middleware' => ['role:admin|super-admin']], function () {

		

		// User crud
		Route::resource('/admin/users','AdminUserController');
		#fromhome
		Route::get('/admin','AdminController@index');
		Route::resource('/admin/companies','AdminCompanyController');
		Route::resource('/admin/shares/','AdminShareController');
		Route::resource('/admin/bankaccounts','AdminBankAccountController');
		Route::resource('/admin/owners','AdminOwnerController');
		Route::resource('/admin/exchanges','AdminExchangeController');
		Route::get('/admin/game','AdminGameController@index');
		Route::get('/admin/game/log','AdminGameController@viewLog');
		Route::post('/admin/game','AdminGameController@postIndex');
		Route::get('/admin/debug','AdminDebugController@index');
	});
});

