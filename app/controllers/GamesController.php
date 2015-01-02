<?php

class GamesController extends BaseController
{

    public static function displayGames($fromdate = '', $todate = '')
    {
        \Carbon\Carbon::now()->toDateString();
        list($fromdate, $todate) = Utils::calcDates($fromdate, $todate);
//        return $todate;
        $user_id = Auth::user()->id;
        $league_ids = League::all();
//            Setting::where('user_id', $user_id)
//            ->distinct('league_id')
//            ->join('leagues', 'leagues.id', '=', 'settings.league_id')
//            ->orderBy('country')
//            ->lists('league_id');
        $data = array();
        $count = array();
        $count_pl = array();
        foreach ($league_ids as $league) {

//            $games = array();
            $games = Game::where('user_id', $user_id)
                ->join('matches', 'matches.id', '=', 'games.match_id')
                ->where('date_time', '>=', $fromdate)
                ->where('date_time', '<=', $todate)
                ->where('league_id', $league->id)
                ->where('confirmed', 0)
                ->with('game_type')
                ->select(DB::raw('games.*, matches.home, matches.away, matches.date_time, matches.home_goals, matches.away_goals, matches.short_result'))
                ->orderBy('date_time')
                ->orderBy('home')
                ->orderBy('game_type_id')
                ->get();
            $placeholders = Placeholder::where('user_id', $user_id)
                ->join('matches', 'matches.id', '=', 'placeholders.match_id')
                ->where('date_time', '>=', $fromdate)
                ->where('date_time', '<=', $todate)
                ->where('matches.league_id', $league->id)
                ->where('confirmed', 0)
                ->where('active', 1)
                ->with('game_type')
                ->select(DB::raw('placeholders.*, matches.home, matches.away, matches.date_time, matches.home_goals, matches.away_goals, matches.short_result'))
                ->orderBy('date_time')
                ->orderBy('home')
                ->orderBy('game_type_id')
                ->get();

            if (count($placeholders) > 0) {
                $data[$league->country_alias]['placeholders'] = $placeholders;
                $data[$league->country_alias]['league'] = $league;
                $data[$league->country_alias]['disabled'] = 'disabled';
                $data[$league->country_alias]['games'] = array();
                foreach ($placeholders as $g) {
                    $c = Placeholder::where('user_id', $user_id)
                        ->where('match_id', $g->match_id)
                        ->where('confirmed', 1)
                        ->where('game_type_id', $g->game_type_id)
                        ->count();
                    $count_pl[$g->id] = $c;
                    if ($c == 0 || $c == '0') {
                        $data[$league->country_alias]['disabled'] = '';
                    }
                }
            }
            if (count($games) > 0) {
                $data[$league->country_alias]['disabled'] = 'disabled';
                $data[$league->country_alias]['league'] = $league;
                $data[$league->country_alias]['games'] = $games;
                foreach ($games as $g) {
                    $c = Game::where('user_id', $user_id)
                        ->where('match_id', $g->match_id)
                        ->where('confirmed', 1)
                        ->where('game_type_id', $g->game_type_id)
                        ->count();
                    $count[$g->id]['count'] = $c;
                    $count[$g->id]['endseries'] = Match::endSeries([Match::find($g->match_id)], $g->game_type_id);
                    if ($c == 0 || $c == '0') {
                        $data[$league->country_alias]['disabled'] = '';
                    }
                }
            }
        }
        $no_info = false;
        if (count($data) == 0) {
            $no_info = true;
        }
//        return $count_pl;
//        return $data;
        return View::make('games')->with(['count_pl' => $count_pl, 'data' => $data, 'count' => $count, 'fromdate' => $fromdate, 'todate' => $todate, 'no_info' => $no_info, 'base' => 'play']);
    }

    public static function confirmGame($game_id, $placeholder)
    {
        if ($placeholder == 'true') {
            $country_alias = League::find(Match::find(Placeholder::find($game_id)->match_id)->league_id)->country_alias;
        } else {
            $country_alias = League::find(Match::find(Game::find($game_id)->match_id)->league_id)->country_alias;
        }
        Game::confirmGame($game_id, $placeholder);
        return Redirect::to(URL::previous() . '#' . $country_alias)->with('message', 'Game confirmed');
    }

    public static function deleteGame($game_id)
    {
        Game::deleteGame($game_id);
        return Redirect::back()->with('message', 'Game deleted');
    }

    public static function getOddsAll()
    {
        $matches = Game::where('user_id', Auth::user()->id)
            ->join('matches', 'matches.id', '=', 'games.match_id')
            ->where('short_result', '-')
            ->select('matches.id')
            ->lists('id');
        foreach ($matches as $match) {
            $odds = Parser::getOdds($match);
            $games = Game::where('match_id', $match)
                ->where('user_id', Auth::user()->id)
                ->get();
            foreach ($games as $game) {
                $game->odds = $odds[$game->game_type_id];
                $game->income = $game->bet * $game->odds;
                $game->profit = $game->bet * $game->odds - $game->bet - $game->bsf;
                $game->save();
            }
        }
        $matches = Placeholder::where('user_id', Auth::user()->id)
            ->join('matches', 'matches.id', '=', 'placeholders.match_id')
            ->where('short_result', '-')
            ->select('matches.id')
            ->lists('id');
        foreach ($matches as $match) {
            $odds = Parser::getOdds($match);
            $games = Placeholder::where('match_id', $match)
                ->where('user_id', Auth::user()->id)
                ->get();
            foreach ($games as $game) {
                $game->odds = $odds[$game->game_type_id];
                $game->income = $game->bet * $game->odds;
                $game->profit = $game->bet * $game->odds - $game->bet - $game->bsf;
                $game->save();
            }
        }
        return Redirect::back()->with('message', 'Odds refreshed');
    }

    public static function getOdds($country_alias)
    {
        $l = League::where('country_alias', $country_alias)->first();
        $matches = Game::where('user_id', Auth::user()->id)
            ->join('matches', 'matches.id', '=', 'games.match_id')
            ->where('league_id', $l->id)
            ->where('short_result', '-')
            ->select('matches.id')
            ->lists('id');
        foreach ($matches as $match) {
            $odds = Parser::getOdds($match);
            $games = Game::where('match_id', $match)
                ->where('user_id', Auth::user()->id)
                ->get();
            foreach ($games as $game) {
                $game->odds = $odds[$game->game_type_id];
                $game->income = $game->bet * $game->odds;
                $game->profit = $game->bet * $game->odds - $game->bet - $game->bsf;
                $game->save();
            }
        }
        $matches = Placeholder::where('user_id', Auth::user()->id)
            ->join('matches', 'matches.id', '=', 'placeholders.match_id')
            ->where('matches.league_id', $l->id)
            ->where('short_result', '-')
            ->select('matches.id')
            ->lists('id');
        foreach ($matches as $match) {
            $odds = Parser::getOdds($match);
            $games = Placeholder::where('match_id', $match)
                ->where('user_id', Auth::user()->id)
                ->get();
            foreach ($games as $game) {
                $game->odds = $odds[$game->game_type_id];
                $game->income = $game->bet * $game->odds;
                $game->profit = $game->bet * $game->odds - $game->bet - $game->bsf;
                $game->save();
            }
        }
        return Redirect::to(URL::previous() . '#' . $country_alias)->with('message', $country_alias);
    }

    public static function confirmAll($country_alias)
    {
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
                GamesController::confirmGame($game->id, false);
            }
        }
        try {
            return Redirect::to(URL::previous() . '#' . $country_alias)->with('message', 'Bet confirmed');
        } catch (InvalidArgumentException $e) {
            return Redirect::to(URL::to("/play#" . $country_alias));
        }
    }

    public static function saveTable()
    {
        $id = Input::get('id');
        $value = Input::get('value');
        $array = explode('_', $id);
        $game_id = $array[1];
        $field = $array[0];
        $pl = $array[2];
        if ($pl == 'pl') {
            $game = Placeholder::find($game_id);
        } else if ($pl == 'game') {
            $game = Game::find($game_id);
        }
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

        if ($pl == 'pl') {
            $tmp = Placeholder::find($game_id);
            return $game_id . "*" . $tmp->bsf . "*" . $tmp->bet . "*" . $tmp->odds . "*" . $tmp->income . "*" . $tmp->profit . "*pl";

        } else if ($pl == 'game') {
            $tmp = Game::find($game_id);
            return $game_id . "*" . $tmp->bsf . "*" . $tmp->bet . "*" . $tmp->odds . "*" . $tmp->income . "*" . $tmp->profit . "*game";

        }
    }

    public static function refund($match_id)
    {
        $user = Auth::user();
        $games = Game::where('user_id', $user->id)
            ->where('match_id', $match_id)
            ->where('confirmed', 1)
            ->get();
        foreach ($games as $game) {
            $user->account = $user->account + $game->bet;
            $game->bet = 0;
            $game->save();
        }
        $user->save();
        return Redirect::back();
    }
} 