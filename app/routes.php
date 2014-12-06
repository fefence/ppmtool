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

Route::get('boo/{i}/{season}', function($i, $season)
{
//    $ids = [];
//    for($i = 1; $i < 10; $i ++) {
        Series::calculateSeries($i, $season);
        $seris = Series::where('active', 1)->get();
        foreach($seris as $s){
            $s->active = 0;
            $s->save();
        }
//    }
//    Updater::update(2);
//    $matches = Match::where('id', '2Vs6PNaA')->get();
//    $next =  Updater::getNextMatches($matches);
//    return $next->last();
//    return Parser::updateMatchesResult($matches);
});

Route::get('/play', 'GamesController@displayGames');
Route::post('/play/save', 'GamesController@saveTable');
Route::get('/play/confirm/{game_id}', 'GamesController@confirmGame');
Route::get('/series', 'PPMController@displaySeries');

Route::get('/settings', 'SettingsController@displaySettings');
Route::get('/settings/disable/{league_id}/{game_type_id}', 'SettingsController@disableSettings');
Route::get('/settings/enable/{league_id}/{game_type_id}', 'SettingsController@enableSettings');

Route::get('/login', 'SessionsController@create');
Route::get('/logout', 'SessionsController@destroy');
Route::resource('sessions', 'SessionsController', ['only'  => ['create', 'store', 'destroy']]);