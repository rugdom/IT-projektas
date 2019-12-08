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

Route::get('/namai', 'HomeController@index')->name('home');

Route::get('/renginiai', 'EventController@index')->name('events.index');
Route::get('/renginiai/id={id}', 'EventController@show')->name('events.show');

Route::get('renginiai/uzsakymas', 'EventController@orderInformation')->name('events.orderInformation');
Route::get('renginiai/uzsakymo-nera', 'EventController@noOrder')->name('events.noOrder');
Route::post('renginiai/uzsakymas/redaguoti', 'EventController@editOrder')->name('events.editOrder');
Route::get('renginiai/uzsakymas/siulomi-renginiai', 'EventController@orderEvents')->name('events.orderEvents');
Route::post('renginiai/uzsakymas/siulomi-renginiai/issaugoti', 'EventController@saveOrders')->name('events.saveOrders');

Route::get('/renginiai/uzsisakyti', 'EventController@order')->name('events.order');
Route::post('/renginiai/uzsisakyti', 'EventController@subscribe')->name('events.subscribe');

Route::post('/renginiai/sukurti', 'EventController@store')->name('events.store');
Route::get('/renginiai/sukurti', 'EventController@create')->name('events.create');
Route::post('/renginiai/filtruoti', 'EventController@filter')->name('events.filter');

Route::get('/renginiai/sukurti/tipai', 'EventController@createType')->name('events.createType');
Route::post('/renginiai/sukurti/tipai/prideti', 'EventController@addType')->name('events.addType');

Route::get('/renginiai/raktas={keyword}', 'EventController@keywords')->name('events.eventsByKey');

/*
Route::get('/naudotojai', 'UserController@index')->name('users.index');
Route::get('/naudotojai/sukurti', 'UserController@create')->name('users.create');
Route::get('/naudotojai/redaguoti={$id}', 'UserController@edit')->name('users.edit');
Route::get('/naudotojai/id={$id}', 'UserController@show')->name('users.index');
*/
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
});


Route::get('siustiLaiska', 'MailController@index');
