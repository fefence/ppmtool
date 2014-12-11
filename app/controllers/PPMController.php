<?php


class PPMController extends \BaseController{

    public static function displaySeries() {
        $countries = League::all()->lists('country_alias');
        $games = GameType::all();
        $data = array();
        foreach($countries as $country) {
            for($i = 1; $i < 11; $i ++) {
                $top = '';
                $top_25 = Series::where('country_alias', $country)
                    ->join('leagues', 'leagues.id', '=', 'series.league_id')
                    ->where('game_type_id', $i)
                    ->orderBy('length', "desc")
                    ->take(25)
                    ->lists('length');
                foreach($top_25 as $t) {
                    $top = $top.$t.", ";
                }
                $data[$country][$i] = Series::where('active', 1)
                    ->where('country_alias', $country)
                    ->join('leagues', 'leagues.id', '=', 'series.league_id')
                    ->where('game_type_id', $i)
                    ->first();
                if (count($data[$country][$i]) == 0) {
                    $data[$country][$i]['length'] = 0;
                }
                $data[$country][$i]['top'] = $top;
            }
        }
//        return $data;
        return View::make('ppm')->with(['data' => $data, 'games' => $games]);
    }

    public static function displaySeriesGames($series_id) {
        $user_id = Auth::user()->id;
        $games = Game::where('user_id', $user_id)
            ->join('matches', 'matches.id', '=', 'games.match_id')
            ->where('series_id', $series_id)
            ->where('confirmed', 1)
            ->with('game_type')
            ->select(DB::raw('games.*, matches.home, matches.away, matches.date_time, matches.home_goals, matches.away_goals, matches.short_result'))
            ->orderBy('current_length', "desc")
            ->get();
        return View::make('seriesdetails')->with(['games' => $games]);
    }
}