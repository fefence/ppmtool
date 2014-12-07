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
        $count = array();
        foreach ($league_ids as $l) {
            $league = League::find($l);
            $games = null;
            $games = Game::where('user_id', $user_id)
                ->join('matches', 'matches.id', '=', 'games.match_id')
                ->where('league_id', $l)
                ->where('confirmed', 0)
                ->with('game_type')
                ->select(DB::raw('games.*, matches.home, matches.away, matches.date_time, matches.home_goals, matches.away_goals, matches.short_result'))
                ->orderBy('date_time')
                ->orderBy('game_type_id')
                ->get();
//            return $games;
            $data[$league->country_alias] = $games;
            foreach($games as $g) {
                $c = Game::where('user_id', $user_id)
                    ->where('match_id', $g->match_id)
                    ->where('confirmed', 1)
                    ->where('game_type_id', $g->game_type_id)
                    ->count();
                $count[$g->id] = $c;
            }
        }
        return View::make('games')->with(['data' => $data, 'count' => $count]);
    }

    public static function confirmGame($game_id) {
        Game::confirmGame($game_id);
        return Redirect::back()->with('message', 'Game confirmed');
    }

    public static function deleteGame($game_id) {
        Game::deleteGame($game_id);
        return Redirect::back()->with('message', 'Game confirmed');
    }

    public static function getOdds($country_alias) {
        $l = League::where('country_alias', $country_alias)->firts();
        $matches = Match::where('league_id', $l->id)
            ->where('short_result', '-')
            ->lists('id');
        $games = Game::where('user_id', Auth::user()->id)
            ->whereIn('match_id', $matches)
            ->get();
        foreach ($games as $game) {
//            $odds = Parser::getOdds($game->odds);
        }
    }

    public static function saveTable()
    {
        $id = Input::get('id');
        $value = Input::get('value');
        $array = explode('_', $id);
        $game_id = $array[1];
        $field = $array[0];
        $game = Game::find($game_id);
        switch ($field) {
            case 'bet':
                $game->bet = $value;
                break;
            case 'odds':
                $game->odds = $value;
                break;
            case 'bsf':
                $game->bsf = $value;
                break;
        }
        $game->income = $game->bet * $game->odds;
        $game->profit = $game->bet * $game->odds - $game->bet - $game->bsf;
        $game->save();

        $tmp = Game::find($game->id);
        return $game_id."*".$tmp->bsf."*".$tmp->bet."*".$tmp->odds."*".$tmp->income."*".$tmp->profit;
    }
} 