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

Route::get('/boo', function()
{
    return Updater::update(8);
//    return date('Y-m-d H:i:s', time());
});
Route::get('/live', 'LivescoreController@livescore');
Route::post('/getres/{id}', "LivescoreController@getMatchCurrentRes");

Route::post('/play/save', 'GamesController@saveTable');
Route::get('/play/confirm/{game_id}', 'GamesController@confirmGame');
Route::get('/play/delete/{game_id}', 'GamesController@deleteGame');
Route::get('/play/odds/all', 'GamesController@getOddsAll');
Route::get('/play/odds/{country_alias}', 'GamesController@getOdds');
Route::get('/play/confirm/all/{country_alias}', 'GamesController@confirmAll');
Route::get('/play/{fromdate?}/{todate?}', ['as' => 'home', 'uses' => 'GamesController@displayGames']);

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