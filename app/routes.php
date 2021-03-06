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

Route::get('/boo/{league_id}', function ($league_id) {
	return Series::calculateSeries($league_id, '2014-2015');
});
Route::get('/active', 'GamesController@active_series');
Route::get('/list/{fromdate?}/{todate?}', 'LivescoreController@livescore');
Route::get('/test', 'LivescoreController@test');
Route::get('/listbycountry/{fromdate?}/{todate?}', 'LivescoreController@livescorebycountry');
Route::post('/getres/{id}', "LivescoreController@getMatchCurrentRes");

Route::get('/refund/{match_id}', 'GamesController@refund');

Route::get('/stats', 'StatsController@countries');
Route::get('/stats/{country}', 'StatsController@display');

Route::get('/log', 'ActionLogController@display');

Route::post('/play/save', 'GamesController@saveTable');
Route::get('/confirm/{game_id}/{placeholder}', 'GamesController@confirmGame');
Route::get('/play/delete/{game_id}', 'GamesController@deleteGame');
Route::get('/play/odds/all', 'GamesController@getOddsAll');
Route::get('/play/odds/{country_alias}', 'GamesController@getOdds');
Route::get('/confirmall/{country_alias}', 'GamesController@confirmAll');
Route::get('/play/{fromdate?}/{todate?}', ['as' => 'home', 'uses' => 'GamesController@displayGames']);

Route::get('/series', 'PPMController@displaySeries');
Route::get('/series/{id}', 'PPMController@displaySeriesGames');

Route::get('/settings', 'SettingsController@displaySettings');
Route::get('/settings/disable/{league_id}/{game_type_id}', 'SettingsController@disableSettings');
Route::get('/settings/enable/{league_id}/{game_type_id}', 'SettingsController@enableSettings');

Route::get('/login', 'SessionsController@create');
Route::get('/logout', 'SessionsController@destroy');
Route::resource('sessions', 'SessionsController', ['only' => ['create', 'store', 'destroy']]);

Route::get('/calculator', 'CalculatorController@countries');
Route::get('/calculator/{league_id}/{type}/odds', 'CalculatorController@getOdds');
Route::get('/calculator/{country}', 'CalculatorController@country');
Route::post('/calculator/calculate', 'CalculatorController@calculate');

Route::get('/', function () {
    return Redirect::route('home');
});
Route::get('/home', function () {
    return Redirect::route('home');
});