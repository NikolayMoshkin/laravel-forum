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

//Route::resource('threads', 'ThreadsController');

Route::get('/threads', 'ThreadsController@index');
Route::get('threads/create', 'ThreadsController@create'); //порядок маршрутов имеет значение! threads/create не будет восприниматься, как название канала
Route::get('/threads/{channel}', 'ThreadsController@index');

Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');

Route::post('threads', 'ThreadsController@store');

Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');

Route::delete('/replies/{reply}', 'RepliesController@destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::post('/replies/{reply}/favourites', 'FavouritesController@store');

Route::get('/profiles/{user}', 'ProfilesController@show');

Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

