<?php


class StatsController extends \BaseController{

    public static function countries(){
        $all_leagues = League::orderBy('country')->get();
        return View::make('stats')->with(['leagues' => $all_leagues]);
    }

    public static function display($country) {
        $all_leagues = League::orderBy('country')->get();
        $games = GameType::all();
        $league = League::where('country', $country)->first();
        $data = array();
        foreach($games as $g) {
            $data[$g->id] = array();
            $seasons = Match::where('league_id', $league->id)
                ->distinct('season')
                ->orderBy('season', "desc")
                ->lists('season');
            foreach($seasons as $season) {
                $series = Series::where('game_type_id', $g->id)
                    ->where('matches.league_id', $league->id)
                    ->join('matches', 'matches.id', '=', 'series.end_match_id')
                    ->where('season', $season)
                    ->orderBy('date_time')
                    ->get();
                $data[$g->id][$season]['stats'] = $series;
                $longest = Series::where('game_type_id', $g->id)
                    ->where('matches.league_id', $league->id)
                    ->join('matches', 'matches.id', '=', 'series.end_match_id')
                    ->where('season', $season)
                    ->orderBy('length', "desc")
                    ->take(10)
                    ->lists('length');
                $data[$g->id][$season]['longest'] = $longest;
            }

            $top = Series::where('game_type_id', $g->id)
                ->where('matches.league_id', $league->id)
                ->join('matches', 'matches.id', '=', 'series.end_match_id')
                ->orderBy('length', "desc")
                ->take(25)
                ->lists('length');
            $data[$g->id]['all'] = $top;
        }

        return View::make('series')->with(['data' => $data, 'leagues' => $all_leagues]);
    }
}