<?php


class PPMController extends \BaseController{

    public static function displaySeries() {
        $countries = League::all()->lists('country');
        $games = GameType::all();
        $data = array();
        foreach($countries as $country) {
            $data[$country] = League::where('active', 1)
                ->where('country', $country)
                ->join('series', 'leagues.id', '=', 'series.league_id')
                ->orderBy('game_type_id')
                ->get();
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
            ->orderBy('current_length')
            ->get();
        return View::make('seriesdetails')->with(['games' => $games]);
    }
}