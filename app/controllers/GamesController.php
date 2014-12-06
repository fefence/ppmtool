<?php

class GamesController extends BaseController
{

    public static function displayGames()
    {
        $user_id = Auth::user()->id;
        $league_ids = Setting::where('user_id', $user_id)
            ->distinct('league_id')
            ->with('league')
            ->lists('league_id');
        $data = array();
        foreach ($league_ids as $l) {
            $league = League::find($l);
            $games = null;
            $games = Game::where('user_id', $user_id)
                ->join('matches', 'matches.id', '=', 'games.match_id')
                ->where('league_id', $l)
                ->with('game_type')
                ->get();
//            return $games;
            $data[$league->country_alias] = $games;
        }
        return View::make('games')->with(['data' => $data]);
    }
} 