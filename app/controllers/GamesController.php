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
            foreach ($games as $g) {
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

    public static function confirmGame($game_id)
    {
        Game::confirmGame($game_id);
        return Redirect::back()->with('message', 'Game confirmed');
    }

    public static function deleteGame($game_id)
    {
        Game::deleteGame($game_id);
        return Redirect::back()->with('message', 'Game confirmed');
    }

    public static function getOdds($country_alias)
    {
        $l = League::where('country_alias', $country_alias)->first();
//        $matches = Match::where('league_id', $l->id)
//            ->where('short_result', '-')
//            ->lists('id');
        $matches = Game::where('user_id', Auth::user()->id)
            ->join('matches', 'matches.id', '=', 'games.match_id')
            ->where('league_id', $l->id)
            ->where('short_result', '-')
//            ->whereIn('match_id', $matches)
            ->select('matches.id')
            ->lists('id');
        foreach ($matches as $match) {
            $odds = Parser::getOdds($match);
            $games = Game::where('match_id', $match)
                ->where('user_id', Auth::user()->id)
                ->get();
            foreach ($games as $game) {
                $game->odds = $odds[$game->game_type_id];
                $game->save();
            }
        }
        return Redirect::back()->with('message', 'Odds refreshed');
    }

    public static function confirmAll($country_alias) {
        $l = League::where('country_alias', $country_alias)->first();
        $games = Game::where('user_id', Auth::user()->id)
            ->join('matches', 'matches.id', '=', 'games.match_id')
            ->where('league_id', $l->id)
            ->where('short_result', '-')
            ->where('confirmed', 0)
            ->select('games.*')
            ->get();
        foreach ($games as $game) {
            $conf = Game::where('user_id', Auth::user()->id)
                ->where('confirmed', 1)
                ->where('match_id', $game->match_id)
                ->where('game_type_id', $game->game_type_id)
                ->get();
            if ($game->bet > 0 && count($conf) == 0) {
                GamesController::confirmGame($game->id);
            }
        }
        return Redirect::back()->with('message', 'Odds refreshed');
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
        return $game_id . "*" . $tmp->bsf . "*" . $tmp->bet . "*" . $tmp->odds . "*" . $tmp->income . "*" . $tmp->profit;
    }
} 