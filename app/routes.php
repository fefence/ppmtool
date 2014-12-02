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

Route::get('boo/', function()
{
//    Updater::update(2);
    $matches = Match::where('id', '2Vs6PNaA')->get();
    $next =  Updater::getNextMatches($matches);
    return $next->last();
//    return Parser::updateMatchesResult($matches);
});

Route::get('/ppm', 'PPMController@displaySeries');
