<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/boo/{id}', function($id)
{
    Updater::update($id);
});
Route::get('/live', 'LivescoreController@livescore');

Route::get('/play', ['as' => 'home', 'uses' => 'GamesController@displayGames']);
Route::post('/play/save', 'GamesController@saveTable');
Route::get('/play/confirm/{game_id}', 'GamesController@confirmGame');
Route::get('/play/delete/{game_id}', 'GamesController@deleteGame');
Route::get('/series', 'PPMController@displaySeries');
Route::get('/series/{id}', 'PPMController@displaySeriesGames');

Route::get('/settings', 'SettingsController@displaySettings');
Route::get('/settings/disable/{league_id}/{game_type_id}', 'SettingsController@disableSettings');
Route::get('/settings/enable/{league_id}/{game_type_id}', 'SettingsController@enableSettings');

Route::get('/login', 'SessionsController@create');
Route::get('/logout', 'SessionsController@destroy');
Route::resource('sessions', 'SessionsController', ['only'  => ['create', 'store', 'destroy']]);

Route::get('/', function(){
    return Redirect::route('home');
});
Route::get('/home', function(){
    return Redirect::route('home');
});