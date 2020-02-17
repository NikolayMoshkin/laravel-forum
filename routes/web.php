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

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

//Route::resource('threads', 'ThreadsController');

Route::get('/threads', 'ThreadsController@index')->name('threads.index');
Route::get('threads/create', 'ThreadsController@create'); //порядок маршрутов имеет значение! threads/create не будет восприниматься, как название канала
Route::get('/threads/{channel}', 'ThreadsController@index');

Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');
Route::patch('/threads/{channel}/{thread}', 'ThreadsController@update')->name('threads.update');
Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');

Route::post('/threads', 'ThreadsController@store')->name('threads.store');;

Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');

Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('replies.destroy');
Route::patch('/replies/{reply}', 'RepliesController@update');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::post('/replies/{reply}/favourites', 'FavouritesController@store');

Route::get('/profiles/{profileUser}', 'ProfilesController@show')->name('profile');

Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('/api/users', 'Api\UsersController@index');
Route::post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');

