<?php

class GamesController extends BaseController{

    public static function displayGames() {
        $user_id = Auth::user()->id;
        $games = Game::where('user_id', $user_id)
            ->with('match')
            ->with('game_type')
            ->get();
        $leagues = League::all()->lists('country_alias', 'id');
//        return $games;
        return View::make('games')->with(['data' => $games, 'leagues' => $leagues]);
    }
} 